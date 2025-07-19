<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Core;

class Migrate_OCE_Settings{
    private static $instance = null;

    private $batch_size;
    private $do_the_update = true;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $this->batch_size = 3;
    }

    public function migrate(){
        add_action( 'theme_migrator_display', array( $this, 'display') );
        add_action( 'wp_ajax_process_new_oce_settings', array($this, 'ajax_process_page_data') );
        add_action( 'wp_ajax_after_new_oce_settings', array($this, 'ajax_after_data_migration') );
    }

    public function display(){
        ?>
        <div class="wrap">
            <div class="theme-migrator">
                <h3>―――― Migrate Off-Canvas Element Settings ( To level up to versions >= 2.6.0 )</h3>
                ――――― <button class="theme-migrator__init-process button-primary" data-action="new_oce_settings" data-status="initial">
                    <span><i class="dashicons dashicons-migrate uf-button-icon"></i> INIT MIGRATION</span>
                    <span><i class="dashicons dashicons-admin-generic uf-button-icon"></i> PROCESSING</span>
                    <span><i class="dashicons dashicons-saved uf-button-icon"></i> MIGRATION COMPLETE</span>
                    <span><i class="dashicons dashicons-warning uf-button-icon"></i> MIGRATION FAILED</span>
                </button>
            </div>
        </div>
        <?php
    }

    public function ajax_process_page_data() {
        check_ajax_referer('process_page_data_nonce', 'nonce');
        
        $batch_size = $this->batch_size;
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    
        // Procesar un lote de datos
        $processed = $this->process_page_data_batch($batch_size, $offset);
    
        wp_send_json_success(array(
            'control' => $processed['control'],
            'processed' => $processed['quantity'],
            'offset' => $offset + $processed['quantity'],
            'complete' => $processed['quantity'] < $batch_size // Completo si se procesaron menos de batch_size elementos
        ));
    }

    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;
    
        // Obtener un lote de páginas a procesar
        $pages = $wpdb->get_results($wpdb->prepare(
            "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key IN ('offcanvas_element_type')
            AND p.post_type != 'revision'
            LIMIT %d OFFSET %d",
            $batch_size,
            $offset
        ));

        $general_control = array();
        $do_the_update = $this->do_the_update;
    
        foreach ($pages as $page) {
            $element_type = $page->meta_value;
            $old_settings = get_post_meta($page->post_id, 'offcanvas_element_'.$element_type.'_settings', true);
            $old_data = maybe_unserialize($old_settings);

            $page_control = array(
                'title' => get_the_title( $page->post_id ),
                'id' => $page->post_id,
                'posttype' => $page->post_type,
                'meta' => $page->meta_key,
                'old_data' => $old_data
            );

            $new_oce_data = $this->migrate_settings_data($old_data);
            if($do_the_update) update_post_meta($page->post_id, 'offcanvas_element_settings', $new_oce_data);
            $page_control['new_data'] = $new_oce_data;

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }
    
    public function migrate_settings_data( $old_settings ){
        $new_settings = array();

        if( is_array($old_settings) && !empty($old_settings) ){
            // migrate background color settings
            if( 
                isset($old_settings['background_color']) && 
                isset($old_settings['background_color']['use']) && 
                $old_settings['background_color']['use']){
                    
                $old_bgc_settings = $old_settings['background_color'];

                // migrate text color settings
                if( $old_bgc_settings['color_scheme'] === 'dark-scheme' ){
                    $new_settings['font_color'] = array(
                        'use' => true,
                        'color' => '',
                        'color_scheme' => 'dark_scheme'
                    );
                }

                // migrate background color settings
                if( $old_bgc_settings['color'] != '#ffffff' || $old_bgc_settings['alpha'] != 100) {
                    unset( $old_bgc_settings['color_scheme'] );
                    $new_settings['background_color'] = $old_bgc_settings;    
                }
            }

            // migrate padding settings
            if( 
                isset($old_settings['padding']) && 
                isset($old_settings['padding']['use']) && 
                $old_settings['padding']['use']){

                unset($old_settings['padding']['number']);
                $new_settings['padding'] = $old_settings['padding'];
            }
        }

        return $new_settings;
    }

    public function ajax_after_data_migration() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        // ...

        wp_send_json_success(array(
            'complete' => true
        ));
    }
}