<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;
use Ultimate_Fields\Ultimate_Builder\Templates_Generator;

class Migrate_3_3_0_to_3_4_0 extends Migrate_Components_Settings{
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $batch_size = 3;
        $do_the_update = true;
        $delete_old_data = true;
        $title = 'Migrate 3.3.0 to 3.4.0 ( Archive Template Implementation )';
        $slug = 'migrate_3_3_0_to_3_4_0';
        $is_top_level = false;
        $meta_keys = array( 'page_content' );
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, $meta_keys, $delete_old_data );
    }

    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;
    
        // Obtener un lote de páginas a procesar
        $post_types = array('archive_page');
        $meta_keys_placeholders   = implode(',', array_fill(0, count($this->meta_keys), '%s'));
        $post_types_placeholders  = implode(',', array_fill(0, count($post_types), '%s'));
        $pages = array();
        $query = "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key IN ($meta_keys_placeholders)
            AND p.post_type != 'revision'
            AND p.post_type IN ($post_types_placeholders)
            LIMIT %d OFFSET %d";
        
        $prepare_values = array_merge($this->meta_keys, $post_types, array($batch_size, $offset));
        $pages = $wpdb->get_results($wpdb->prepare($query, $prepare_values));

        $general_control = array();
        $do_the_update = $this->do_the_update;
    
        foreach ($pages as $page) {
            $old_data = maybe_unserialize($page->meta_value);

            $page_control = array(
                'title' => get_the_title( $page->post_id ),
                'id' => $page->post_id,
                'posttype' => $page->post_type,
                'meta' => $page->meta_key,
                'old_data' => $old_data
            );

            $skip_migration = apply_filters( 'migrator_3_3_0_to_3_4_0/skip_migration', false, $page->post_id, $page->meta_key, $old_data );
            if( $skip_migration ){
                error_log( 'Skipping page migration for page ID: ' . $page->post_id );
                continue;
            }

            error_log( 'Migrating page: ' . get_the_title( $page->post_id ) . ' (ID: ' . $page->post_id . ') - Meta Key: ' . $page->meta_key );

            $old_datastore = get_post_meta( $page->post_id, 'page_content_datastore', true );
            $new_data = $this->migrate_page_modules_data($old_data, $old_datastore);
            if($do_the_update){
                update_post_meta($page->post_id, 'page_content', $new_data['page_content'] );
                update_post_meta($page->post_id, 'page_content_datastore', $new_data['page_content_datastore'] );
            }
            $page_control['new_data'] = $new_data;

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }

    /**
     * Migrates the old page content structure to the new one used in archive templates, and also migrates the old datastore structure to the new one. The migration consists in dissolving the "archive-page-structure" component and promoting its children to the parent level (and do this recursively if there are nested archive-page-structure components), and also dissolving the "archive-title", "archive-posts", and "theme-options" components and promoting their children to a new structure:
     * 
     * - main-content
     * --main
     * --- processed children go here
     * --- insert a brand new archive-title
     * --- insert a brand new archive-posts
     * --aside
     * --- sidebar
     * 
     * During the process, it collects the IDs of the dissolved components to be able to remove their entries from the datastore and also collects the IDs of the elements that belonged to those components to be able to remove styles that target those elements.
     */
    public function migrate_page_modules_data($old_data, $old_datastore = array()) {
        $content_structure = is_array($old_data) ? $old_data : maybe_unserialize($old_data);
        $datastore         = is_array($old_datastore) ? $old_datastore : maybe_unserialize($old_datastore);

        if ( ! is_array($content_structure) ) {
            return array(
                'page_content'           => $old_data,
                'page_content_datastore' => $old_datastore,
            );
        }

        $removed_cmp_ids    = array();
        $removed_elem_ids   = array();

        // Walk every frame's component tree and build the new archive structure
        if ( isset($content_structure['pages']) && is_array($content_structure['pages']) ) {
            foreach ( $content_structure['pages'] as &$page ) {
                if ( isset($page['frames']) && is_array($page['frames']) ) {
                    foreach ( $page['frames'] as &$frame ) {
                        if ( isset($frame['component']['components']) && is_array($frame['component']['components']) ) {
                            $frame['component']['components'] = $this->process_components_tree(
                                $frame['component']['components'],
                                $removed_cmp_ids,
                                $removed_elem_ids
                            );
                        }
                    }
                    unset($frame);
                }
            }
            unset($page);
        }

        // Remove styles that target dissolved element IDs
        if ( ! empty($removed_elem_ids) && isset($content_structure['styles']) && is_array($content_structure['styles']) ) {
            $content_structure['styles'] = array_values( array_filter( $content_structure['styles'], function ( $style ) use ( $removed_elem_ids ) {
                if ( ! isset($style['selectors']) || ! is_array($style['selectors']) ) {
                    return true;
                }
                foreach ( $style['selectors'] as $selector ) {
                    // Selectors are stored as '#id'; strip the leading # for comparison
                    if ( in_array( ltrim($selector, '#'), $removed_elem_ids, true ) ) {
                        return false;
                    }
                }
                return true;
            } ) );
        }

        // Remove datastore entries that belong to dissolved components
        $new_datastore = is_array($datastore) ? $datastore : array();
        foreach ( $removed_cmp_ids as $cmp_id ) {
            unset( $new_datastore[ $cmp_id ] );
        }

        return array(
            'page_content'           => $content_structure,
            'page_content_datastore' => $new_datastore,
        );
    }

    /**
     * Recursively walks a GJS component array.
     * When an archive-page-structure component is found, it is dissolved (along with any
     * nested archive-page-structure components) and its contents are used to build the new
     * main-content > (main + aside) hierarchy via build_new_archive_structure().
     * All other component types are kept in place with their children processed recursively.
     */
    private function process_components_tree( $components, &$removed_cmp_ids, &$removed_elem_ids ) {
        $result = array();

        foreach ( $components as $component ) {
            $type    = isset($component['type'])             ? $component['type']             : '';
            $cmp_id  = isset($component['__id'])             ? $component['__id']             : null;
            $elem_id = isset($component['attributes']['id']) ? $component['attributes']['id'] : null;

            if ( $type === 'archive-page-structure' ) {
                // Collect IDs of the archive-page-structure itself for cleanup
                if ( $cmp_id )  $removed_cmp_ids[]  = $cmp_id;
                if ( $elem_id ) $removed_elem_ids[] = $elem_id;

                // Recursively dissolve any nested archive-page-structure components
                $children = isset($component['components']) && is_array($component['components'])
                    ? $this->dissolve_archive_page_structure( $component['components'], $removed_cmp_ids, $removed_elem_ids )
                    : array();

                // Build and insert the new main-content structure in place
                $result[] = $this->build_new_archive_structure( $children, $removed_cmp_ids, $removed_elem_ids );

            } elseif ( $type === 'container' ) {
                // Special handling: detect whether the container's children already use
                // archive-page-structure (old wrapped format) or the new main-content
                // format, or are raw sections without any wrapper at all.
                $children = isset($component['components']) && is_array($component['components'])
                    ? $component['components']
                    : array();

                $has_archive_structure = false;
                $has_main_content      = false;
                foreach ( $children as $child ) {
                    $child_type = isset($child['type']) ? $child['type'] : '';
                    if ( $child_type === 'archive-page-structure' ) { $has_archive_structure = true; }
                    if ( $child_type === 'main-content' )           { $has_main_content      = true; }
                }

                if ( $has_main_content ) {
                    // Already migrated — recurse without restructuring
                    $component['components'] = $this->process_components_tree( $children, $removed_cmp_ids, $removed_elem_ids );
                } elseif ( $has_archive_structure ) {
                    // Wrapped format — process_components_tree will handle the archive-page-structure nodes
                    $component['components'] = $this->process_components_tree( $children, $removed_cmp_ids, $removed_elem_ids );
                } else {
                    // Raw sections with no wrapper — treat all container children as archive content
                    $flat_children = $this->dissolve_archive_page_structure( $children, $removed_cmp_ids, $removed_elem_ids );
                    $component['components'] = array(
                        $this->build_new_archive_structure( $flat_children, $removed_cmp_ids, $removed_elem_ids )
                    );
                }
                $result[] = $component;

            } else {
                // Keep component; recursively process its children
                if ( isset($component['components']) && is_array($component['components']) ) {
                    $component['components'] = $this->process_components_tree( $component['components'], $removed_cmp_ids, $removed_elem_ids );
                }
                $result[] = $component;
            }
        }

        return $result;
    }

    /**
     * Recursively dissolves nested archive-page-structure components, collecting and returning
     * all non-archive-page-structure children as a flat array. Collects IDs for cleanup.
     */
    private function dissolve_archive_page_structure( $components, &$removed_cmp_ids, &$removed_elem_ids ) {
        $result = array();

        foreach ( $components as $component ) {
            $type    = isset($component['type'])             ? $component['type']             : '';
            $cmp_id  = isset($component['__id'])             ? $component['__id']             : null;
            $elem_id = isset($component['attributes']['id']) ? $component['attributes']['id'] : null;

            if ( $type === 'archive-page-structure' ) {
                if ( $cmp_id )  $removed_cmp_ids[]  = $cmp_id;
                if ( $elem_id ) $removed_elem_ids[] = $elem_id;

                if ( isset($component['components']) && is_array($component['components']) ) {
                    $result = array_merge(
                        $result,
                        $this->dissolve_archive_page_structure( $component['components'], $removed_cmp_ids, $removed_elem_ids )
                    );
                }
            } else {
                $result[] = $component;
            }
        }

        return $result;
    }

    /**
     * Builds the new archive structure from a flat list of children (already dissolved of
     * archive-page-structure). Dissolves archive-title, archive-posts, and theme-options
     * by promoting their children into main's content, then appends brand-new archive-title
     * and archive-posts components. Returns the assembled main-content component:
     *
     * - main-content
     *   - main
     *     - promoted children (from dissolved archive-title / archive-posts / theme-options)
     *     - other surviving children
     *     - [new] archive-title
     *     - [new] archive-posts
     *   - aside
     *     - [new] sidebar
     */
    private function build_new_archive_structure( $children, &$removed_cmp_ids, &$removed_elem_ids ) {
        $main_children = array();

        foreach ( $children as $component ) {
            $type    = isset($component['type'])             ? $component['type']             : '';
            $cmp_id  = isset($component['__id'])             ? $component['__id']             : null;
            $elem_id = isset($component['attributes']['id']) ? $component['attributes']['id'] : null;

            if ( in_array( $type, array( 'archive-title', 'archive-posts', 'theme-options' ), true ) ) {
                // Dissolve: collect IDs and promote their children
                if ( $cmp_id )  $removed_cmp_ids[]  = $cmp_id;
                if ( $elem_id ) $removed_elem_ids[] = $elem_id;

                if ( isset($component['components']) && is_array($component['components']) ) {
                    $main_children = array_merge( $main_children, $component['components'] );
                }
            } else {
                $main_children[] = $component;
            }
        }

        // Append brand-new archive-title and archive-posts
        $main_children[] = array(
            'type'       => 'archive-title',
            'attributes' => array(),
            'components' => array(),
            '__id'       => 'cmp_' . substr( md5( uniqid() ), 0, 8 ),
        );
        $main_children[] = array(
            'type'       => 'archive-posts',
            'attributes' => array(),
            'components' => array(),
            '__id'       => 'cmp_' . substr( md5( uniqid() ), 0, 8 ),
        );

        $main_cmp = array(
            'type'       => 'main',
            'attributes' => array(),
            'components' => $main_children,
            '__id'       => 'cmp_' . substr( md5( uniqid() ), 0, 8 ),
        );

        $aside_cmp = array(
            'type'       => 'aside',
            'attributes' => array(),
            'components' => array(
                array(
                    'type'       => 'sidebar',
                    'attributes' => array(),
                    'components' => array(),
                    '__id'       => 'cmp_' . substr( md5( uniqid() ), 0, 8 ),
                ),
            ),
            '__id'       => 'cmp_' . substr( md5( uniqid() ), 0, 8 ),
        );

        return array(
            'type'       => 'main-content',
            'attributes' => array(),
            'components' => array( $main_cmp, $aside_cmp ),
            '__id'       => 'cmp_' . substr( md5( uniqid() ), 0, 8 ),
        );
    }

    public function ajax_after_data_migration() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        $converted = $this->convert_archive_page_to_archive_template();

        wp_send_json_success(array(
            'complete'  => true,
            'converted' => $converted,
        ));
    }

    /**
     * Changes the post_type of every archive_page post to archive_template.
     * Returns an array of converted post IDs (or an empty array if none found).
     */
    private function convert_archive_page_to_archive_template() {
        global $wpdb;

        if ( ! $this->do_the_update ) {
            return array();
        }

        $post_ids = $wpdb->get_col(
            "SELECT ID FROM {$wpdb->posts}
             WHERE post_type = 'archive_page'
             AND post_status != 'trash'"
        );

        if ( empty($post_ids) ) {
            return array();
        }

        $placeholders = implode( ',', array_fill( 0, count($post_ids), '%d' ) );
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->posts} SET post_type = 'archive_template' WHERE ID IN ($placeholders)",
                $post_ids
            )
        );

        // Flush rewrite rules so the new post type slugs resolve correctly
        flush_rewrite_rules();

        return $post_ids;
    }
}