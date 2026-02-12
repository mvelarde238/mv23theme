<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

/**
 * Cleanup class for migration 2.10.X to 3.0.0
 * 
 * This class removes old post meta keys after verifying the migration was successful.
 * It should be run AFTER Migrate_2_10_X_to_3_0_0 and after manually verifying that
 * migrated pages load correctly.
 * 
 * Posts are only cleaned if they have 'page_content' meta (meaning they were migrated).
 */
class Cleanup_2_10_X_to_3_0_0 extends Migrate_Components_Settings {
    
    private static $instance = null;

    /**
     * Old meta keys that will be deleted
     */
    private $old_meta_keys = array(
        // Main content meta keys
        'page_modules',
        'components',
        'blocks_layout',
        'offcanvas_element_content',
        
        // Page header meta keys
        'page_header_content_type',
        'page_header_settings',
        'page_header_slider',
        'page_header_content',
        
        // Page settings meta keys
        'page_bgc',
        'page_color_scheme',
        'remove_body_padding_top',
        'hide_static_header',
        'hide_static_header_logo',
        'custom_static_header',
        'custom_static_header_logo',
        'static_header_bgc',
        'static_header_color_scheme',
        'hide_sticky_header_logo',
        'custom_sticky_header',
        'custom_sticky_header_logo',
        'sticky_header_bgc',
        'sticky_header_color_scheme',
        'static_header_logo', 
        'sticky_header_logo', 
        'hide_sticky_header',
        
        // OCE meta keys
        'offcanvas_element_type',
        'offcanvas_element_sidenav_settings',
        'offcanvas_element_modal_settings',
        'offcanvas_element_bottomsheet_settings',
        'offcanvas_element_settings',
        'offcanvas_element_content_type',
        'offcanvas_element_async_settings',
        
        // Archive page meta keys
        'loop_columns',
        'loop_columns_gap'
    );

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $batch_size = 10; // Cleanup is fast, can process more per batch
        $do_the_update = true;
        $delete_old_data = true;
        $title = 'Cleanup 2.10.X to 3.0.0 (Delete Old Meta)';
        $slug = 'cleanup_2_10_x_to_3_0_0';
        $is_top_level = false; // Show as sub-step
        $meta_keys = array('page_content'); // Only process posts that have been migrated
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, $meta_keys, $delete_old_data );
    }

    /**
     * Override process_page_data_batch to cleanup old meta keys
     * instead of migrating data
     */
    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;
    
        // Find posts that have been migrated (have page_content)
        // and still have at least one old meta key
        $old_keys_placeholders = implode(',', array_fill(0, count($this->old_meta_keys), '%s'));
        
        // Always use OFFSET 0 because we delete metas in each batch,
        // so processed posts won't appear in the next query
        $query = "SELECT DISTINCT pm.post_id, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.post_id IN (
                SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'page_content'
            )
            AND pm.meta_key IN ($old_keys_placeholders)
            AND p.post_type != 'revision'
            LIMIT %d";
        
        $prepare_values = array_merge($this->old_meta_keys, array($batch_size));
        $posts = $wpdb->get_results($wpdb->prepare($query, $prepare_values));

        $general_control = array();
        $do_the_update = $this->do_the_update;
    
        foreach ($posts as $post) {
            $page_control = array(
                'title' => get_the_title( $post->post_id ),
                'id' => $post->post_id,
                'posttype' => $post->post_type,
                'action' => 'cleanup',
                'deleted_keys' => array()
            );

            error_log( 'Cleanup: ' . get_the_title( $post->post_id ) . ' (ID: ' . $post->post_id . ')' );

            // Delete all old meta keys for this post
            if( $do_the_update ){
                foreach( $this->old_meta_keys as $meta_key ){
                    $deleted = delete_post_meta( $post->post_id, $meta_key );
                    if( $deleted ){
                        $page_control['deleted_keys'][] = $meta_key;
                    }
                }
            }

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($posts),
            'control' => $general_control
        );
    }

    /**
     * After cleanup, delete global options
     */
    public function ajax_after_data_migration() {
        if( $this->do_the_update ){
            // Delete old global options (colors, single_pages_settings)
            $old_options = array(
                'single_pages_settings',
                'primary_color',
                'secondary_color',
                'font_color',
                'headings_color',
                'link_color',
                'light_primary_color_percentage',
                'lighter_primary_color_percentage',
                'dark_primary_color_percentage',
                'light_secondary_color_percentage',
                'lighter_secondary_color_percentage',
                'dark_secondary_color_percentage',
                'colorpicker_palette'
            );

            foreach ($old_options as $option) {
                delete_option($option);
            }

            error_log('Cleanup complete: deleted old global options');
        }

        wp_send_json_success(array(
            'complete' => true
        ));
    }

    // These methods are not needed for cleanup but required by parent
    public function migrate_page_modules_data($data) { return $data; }
    public function migrate_seccion_reusable_components_data($data) { return $data; }
    public function migrate_content_layout_data($data) { return $data; }
    public function migrate_settings_data($data) { return $data; }
}
