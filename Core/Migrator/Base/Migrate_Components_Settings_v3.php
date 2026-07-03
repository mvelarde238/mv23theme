<?php
/**
 * Base class for migrations that operate on the GJS page_content tree + page_content_datastore.
 * 
 * Subclasses only need to:
 * 1. Define get_target_component_types() — which GJS component types to target.
 * 2. Define migrate_component( &$component, &$datastore ) — the transformation logic.
 * 
 * The base class handles:
 * - Batch querying posts with page_content meta.
 * - Walking the GJS component tree recursively.
 * - Updating both page_content and page_content_datastore.
 */
namespace Core\Migrator\Base;

abstract class Migrate_Components_Settings_v3 extends Migrate_Components_Settings {

    public function __construct( $batch_size = 3, $do_the_update = false, $title = '', $slug = '', $is_top_level = false, $meta_keys = null, $delete_old_data = false ) {
        $meta_keys = $meta_keys ?: array( 'page_content' );
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, $meta_keys, $delete_old_data );
    }

    /**
     * Returns an array of GJS component type strings to target during the tree walk.
     * Example: return array( 'icon-and-text', 'button' );
     * 
     * @return array
     */
    abstract protected function get_target_component_types();

    /**
     * Migrates a single targeted component. Receives the component node and the full datastore
     * by reference so both can be modified in place.
     * 
     * @param array &$component  The GJS component node (type, __id, components, attributes, etc.)
     * @param array &$datastore  The full page_content_datastore array, keyed by component __id.
     */
    abstract protected function migrate_component( &$component, &$datastore );

    /**
     * Batch processor: queries posts with page_content, walks the GJS tree,
     * and updates both page_content and page_content_datastore.
     */
    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;

        $meta_keys_placeholders = implode(',', array_fill(0, count($this->meta_keys), '%s'));
        // We can uncomment the following line to limit the migration to specific pages for testing purposes.
        // $pages = [345, 2767];
        $query = "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key IN ($meta_keys_placeholders)
            -- AND p.ID IN (" . implode(',', $pages) . ")
            AND p.post_type != 'revision'
            LIMIT %d OFFSET %d";

        $prepare_values = array_merge($this->meta_keys, array($batch_size, $offset));
        $pages = $wpdb->get_results($wpdb->prepare($query, $prepare_values));

        $general_control = array();
        $do_the_update = $this->do_the_update;

        foreach ($pages as $page) {
            $old_data = maybe_unserialize($page->meta_value);
            $old_datastore = get_post_meta( $page->post_id, 'page_content_datastore', true );

            $page_control = array(
                'title'    => get_the_title( $page->post_id ),
                'id'       => $page->post_id,
                'posttype' => $page->post_type,
                'meta'     => $page->meta_key,
                'old_data' => [
                    'page_content'           => $old_data,
                    'page_content_datastore' => $old_datastore,
                ]
            );

            error_log( 'Migrating page: ' . get_the_title( $page->post_id ) . ' (ID: ' . $page->post_id . ') - Meta Key: ' . $page->meta_key );

            $new_data = $this->migrate_page_content_data( $old_data, $old_datastore );

            if ( $do_the_update ) {
                update_post_meta( $page->post_id, 'page_content', $new_data['page_content'] );
                update_post_meta( $page->post_id, 'page_content_datastore', $new_data['page_content_datastore'] );
                $this->after_page_migration( $page->post_id );
            }
            $page_control['new_data'] = $new_data;

            $general_control[] = $page_control;
        }

        return array(
            'quantity' => count($pages),
            'control'  => $general_control
        );
    }

    /**
     * Validates and walks the GJS page_content structure, delegating component
     * migrations to the subclass via migrate_component().
     * 
     * @param mixed $old_data       The page_content meta value.
     * @param mixed $old_datastore  The page_content_datastore meta value.
     * @return array  With keys 'page_content' and 'page_content_datastore'.
     */
    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        $content_structure = is_array($old_data) ? $old_data : maybe_unserialize($old_data);
        $datastore         = is_array($old_datastore) ? $old_datastore : maybe_unserialize($old_datastore);

        if ( ! is_array($content_structure) ) {
            return array(
                'page_content'           => $old_data,
                'page_content_datastore' => $old_datastore,
            );
        }

        if ( ! is_array($datastore) ) {
            $datastore = array();
        }

        // Walk every frame's component tree
        if ( isset($content_structure['pages']) && is_array($content_structure['pages']) ) {
            foreach ( $content_structure['pages'] as &$page ) {
                if ( isset($page['frames']) && is_array($page['frames']) ) {
                    foreach ( $page['frames'] as &$frame ) {
                        if ( isset($frame['component']['components']) && is_array($frame['component']['components']) ) {
                            $this->process_components_tree( $frame['component']['components'], $datastore );
                        }
                    }
                    unset($frame);
                }
            }
            unset($page);
        }

        return array(
            'page_content'           => $content_structure,
            'page_content_datastore' => $datastore,
        );
    }

    /**
     * Recursively walks the GJS component tree. When a targeted component type is found,
     * it delegates to migrate_component(). Always recurses into children.
     */
    private function process_components_tree( &$components, &$datastore ) {
        $target_types = $this->get_target_component_types();

        foreach ( $components as &$component ) {
            $type = isset($component['type']) ? $component['type'] : '';

            if ( in_array( $type, $target_types, true ) ) {
                $this->migrate_component( $component, $datastore );
            }

            // Always recurse into children
            if ( isset($component['components']) && is_array($component['components']) ) {
                $this->process_components_tree( $component['components'], $datastore );
            }
        }
        unset($component);
    }

    /**
     * Helper: generates a unique GJS component __id.
     */
    protected function generate_cmp_id() {
        return 'cmp_' . substr( md5( uniqid() ), 0, 8 );
    }

    /**
     * Hook called after a page has been migrated and its meta updated.
     * Subclasses can override this for post-migration cleanup (e.g., deleting deprecated meta).
     * 
     * @param int $post_id The post ID that was migrated.
     */
    protected function after_page_migration( $post_id ) {
        // No-op by default. Override in subclass for cleanup.
    }
}
