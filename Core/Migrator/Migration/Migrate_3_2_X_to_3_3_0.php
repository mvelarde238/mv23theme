<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;
use Ultimate_Fields\Ultimate_Builder\Templates_Generator;

class Migrate_3_2_X_to_3_3_0 extends Migrate_Components_Settings{
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
        $title = 'Migrate 3.2.X to 3.3.0 ( Single Template Implementation )';
        $slug = 'migrate_3_2_x_to_3_3_0';
        $is_top_level = false;
        $meta_keys = array( 'page_content' );
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, $meta_keys, $delete_old_data );
    }

    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;

        // this settings could be empty or not exist at all
        // $insert_single_structure_on = get_option('insert_single_structure_on', array()); 
        $posttypes_to_migrate_to_single_template = apply_filters( 'migrator_3_2_x_to_3_3_0/posttypes_to_migrate_to_single_template', array('post') );
    
        // Obtener un lote de páginas a procesar
        $meta_keys_placeholders   = implode(',', array_fill(0, count($this->meta_keys), '%s'));
        $post_types_placeholders  = implode(',', array_fill(0, count($posttypes_to_migrate_to_single_template), '%s'));
        $pages = array();
        $query = "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key IN ($meta_keys_placeholders)
            AND p.post_type != 'revision'
            AND p.post_type IN ($post_types_placeholders)
            LIMIT %d OFFSET %d";
        
        $prepare_values = array_merge($this->meta_keys, $posttypes_to_migrate_to_single_template, array($batch_size, $offset));
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

            $skip_migration = apply_filters( 'migrator_3_2_x_to_3_3_0/skip_migration', false, $page->post_id, $page->meta_key, $old_data );
            if( $skip_migration ){
                error_log( 'Skipping page_modules migration for page ID: ' . $page->post_id );
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
     * Migrates the old page content structure to the new one used in single templates, and also migrates the old datastore structure to the new one. The migration consists in dissolving the "single-page-structure" component and promoting its children to the parent level (and do this recursively if there are nested single-page-structure components), and also dissolving the "post-title", "post-content", "social-share", "related-posts", "comments-area", "sidebar" and "theme-options" components and promoting their children to the parent level. During the process, it collects the IDs of the dissolved components to be able to remove their entries from the datastore and also collects the IDs of the elements that belonged to those components to be able to remove styles that target those elements.
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

        $types_to_remove    = array( 'single-page-structure', 'single-main', 'post-title', 'post-content', 'social-share', 'related-posts', 'comments-area', 'sidebar', 'theme-options' );
        $removed_cmp_ids    = array();
        $removed_elem_ids   = array();

        // Walk every frame's component tree and dissolve the removed types
        if ( isset($content_structure['pages']) && is_array($content_structure['pages']) ) {
            foreach ( $content_structure['pages'] as &$page ) {
                if ( isset($page['frames']) && is_array($page['frames']) ) {
                    foreach ( $page['frames'] as &$frame ) {
                        if ( isset($frame['component']['components']) && is_array($frame['component']['components']) ) {
                            $frame['component']['components'] = $this->process_components_tree(
                                $frame['component']['components'],
                                $types_to_remove,
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
     * Components whose type is in $types_to_remove are dissolved: their children
     * are promoted to the parent level (after being processed themselves).
     * Surviving components have their own children processed recursively.
     *
     * Collects the __id and attributes.id values of every dissolved component
     * so the caller can clean up the datastore and styles arrays.
     */
    private function process_components_tree( $components, $types_to_remove, &$removed_cmp_ids, &$removed_elem_ids ) {
        $result = array();

        foreach ( $components as $component ) {
            $type    = isset($component['type'])               ? $component['type']               : '';
            $cmp_id  = isset($component['__id'])               ? $component['__id']               : null;
            $elem_id = isset($component['attributes']['id'])   ? $component['attributes']['id']   : null;

            if ( in_array($type, $types_to_remove, true) ) {
                // Collect IDs for later cleanup
                if ( $cmp_id )  $removed_cmp_ids[]  = $cmp_id;
                if ( $elem_id ) $removed_elem_ids[] = $elem_id;

                // Promote children (recursively processed) to this level
                if ( isset($component['components']) && is_array($component['components']) ) {
                    $promoted = $this->process_components_tree( $component['components'], $types_to_remove, $removed_cmp_ids, $removed_elem_ids );
                    $result   = array_merge( $result, $promoted );
                }
            } else {
                // Keep component; recursively process its children
                if ( isset($component['components']) && is_array($component['components']) ) {
                    $component['components'] = $this->process_components_tree( $component['components'], $types_to_remove, $removed_cmp_ids, $removed_elem_ids );
                }
                $result[] = $component;
            }
        }

        return $result;
    }

    public function ajax_after_data_migration() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        // Migrate single data to single template posts
        /**
         * steps:
         * 1. Get all single data: 'single_' . $post_type . '_settings'
         * 2. For each single data, create a single template post with the connected post type (tax:any, terms:any) and save the single data fields in the single template post meta. Use Templates_Generator::generate_templates( $content_structure ); to generate the single template content structure based on the saved fields and save it in the single template post meta as well.
         * 3. Delete the old single data options
         */

        $post_types = get_post_types( array( 'public' => true ), 'names' );
        foreach( $post_types as $post_type ){
            $setting_name = 'single_' . $post_type . '_settings';
            $single_data = get_option( $setting_name, false );
            if( $single_data ){
                // error_log('Migrating single data for post type: '.$post_type.' with setting name: '.$setting_name);
                // error_log('Single data: '. print_r($single_data, true) );
                // create single template post
                $single_template_id = wp_insert_post( array(
                    'post_title' => 'Single Template for ' . $post_type,
                    'post_type' => 'single_template',
                    'post_status' => 'publish'
                ) );

                if( !is_wp_error($single_template_id) ){
                    // save connected post type in single template post meta
                    if( $this->do_the_update ) update_post_meta( $single_template_id, 'connected_posttype', $post_type );

                    // generate single template content structure based on the saved fields and save it in the single template post meta as well
                    $single_components = array();

                    if( empty($single_data['hide_post_title']) || $single_data['hide_post_title'] === 0 ){
                        $single_components[] = [ 'type' => 'post-title' ];
                    }

                    $single_components[] = [ 'type' => 'post-content' ];

                    if( empty($single_data['hide_social_share']) || $single_data['hide_social_share'] === 0 ){
                        $single_components[] = [ 'type' => 'social-share' ];
                    }

                    if( empty($single_data['hide_related_posts']) || $single_data['hide_related_posts'] === 0 ){
                        $single_components[] = [ 'type' => 'related-posts' ];
                    }

                    if( empty($single_data['hide_comments_area']) || $single_data['hide_comments_area'] === 0 ){
                        $single_components[] = [ 'type' => 'comments-area' ];
                    }

                    $content_structure = [
                        "type" => "wrapper",
                        "components" => [
                            [ 
                                "type" => "container",
                                "components" => [
                                    [
                                        "type" => "main-content",
                                        "template" => $single_data['page_template'] ?? 'main-content--sidebar-right',
                                        "components" => [
                                            [ 
                                                "type" => "main",
                                                "components" => $single_components
                                            ],
                                            [ 
                                                "type" => "aside",
                                                "components" => [
                                                    [ 
                                                        "type" => "sidebar",
                                                        // "sidebar_id" => $post_type . '_sidebar']
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ];

                    $template = Templates_Generator::generate_templates( $content_structure );
                    
                    // Prepare meta values
                    $meta_values = [
                        'page_content_styles' => $template['styles'],
                        'page_content' => $template['gjs_template'],
                        'page_content_datastore' => $template['datastore'],
                    ];

                    // Update post meta with new values
                    foreach ($meta_values as $meta_key => $meta_value) {
                        if( $this->do_the_update ) update_post_meta($single_template_id, $meta_key, $meta_value);
                    }
                }

                // delete old single data option
                if( $this->delete_old_data ) delete_option( $setting_name );
            }
        }

        // delete old option that was used to determine on which post types the single structure should be inserted
        if( $this->delete_old_data ) delete_option('insert_single_structure_on');

        wp_send_json_success(array(
            'complete' => true
        ));
    }
}