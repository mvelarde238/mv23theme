<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Core;

class Migrate_ScrollMagic_to_GSAP{
    private static $instance = null;

    private $batch_size;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Migrate_ScrollMagic_to_GSAP();
        }
        return self::$instance;
    }

    private function __construct(){
        $this->batch_size = 3;
    }

    public function migrate(){
        add_action( 'theme_migrator_display', array( $this, 'display') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_scripts') );
        add_action( 'wp_ajax_process_scrollmagic_to_gsap', array($this, 'ajax_process_page_data') );
        add_action( 'wp_ajax_after_scrollmagic_to_gsap', array($this, 'ajax_after_data_migration') );
    }

    public function display(){
        ?>
        <div class="wrap">
            <div class="theme-migrator">
                <h3>―――― Migrate ScrollMagic to GSAP ( To level up to versions >= 2.1.0 )</h3>
                ――――― <button class="theme-migrator__init-sm-to-gsap button-primary" data-status="initial">
                    <span><i class="dashicons dashicons-migrate uf-button-icon"></i> INIT MIGRATION</span>
                    <span><i class="dashicons dashicons-admin-generic uf-button-icon"></i> PROCESSING</span>
                    <span><i class="dashicons dashicons-saved uf-button-icon"></i> MIGRATION COMPLETE</span>
                    <span><i class="dashicons dashicons-warning uf-button-icon"></i> MIGRATION FAILED</span>
                </button>
            </div>
        </div>
        <?php
    }

    public function enqueue_migrator_scripts( $hook ) {
        if ( 'admin_page_theme-migrator' != $hook ) return;

        $slug = Core::getInstance()->get_slug();

        wp_enqueue_script($slug.'-sm-to-gsap', THEME_MIGRATOR_PATH . '/scripts/migrate-scroll-magic-to-gsap.js', array($slug.'-scripts'), '1.0', true);
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
            WHERE pm.meta_key IN ('page_modules','components','offcanvas_element_content', 'blocks_layout')
            AND p.post_type != 'revision'
            LIMIT %d OFFSET %d",
            $batch_size,
            $offset
        ));

        $general_control = array();
        $do_the_update = true;
    
        foreach ($pages as $page) {
            $old_data = maybe_unserialize($page->meta_value);

            $page_control = array(
                'title' => get_the_title( $page->post_id ),
                'id' => $page->post_id,
                'posttype' => $page->post_type,
                'meta' => $page->meta_key,
                'old_data' => $old_data
            );

            if( $page->meta_key == 'page_modules' ){
                $new_page_modules_data = $this->migrate_page_modules_data($old_data);
                if($do_the_update) update_post_meta( $page->post_id, 'page_modules', $new_page_modules_data );
                $page_control['new_data'] = $new_page_modules_data;
            }
            // reusable_section
            if( $page->meta_key == 'components' ){
                $new_reusable_section_data = $this->migrate_seccion_reusable_components_data($old_data);
                if($do_the_update) update_post_meta( $page->post_id, 'components', $new_reusable_section_data );
                $page_control['new_data'] = $new_reusable_section_data;
            }
            // OCE
            if( $page->meta_key == 'offcanvas_element_content' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                if($do_the_update) update_post_meta($page->post_id, 'offcanvas_element_content', $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }
            // PAGES WITH BLOCKS LAYOUT (HAVE columns COMP)
            if( $page->meta_key == 'blocks_layout' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                if($do_the_update) update_post_meta($page->post_id, 'blocks_layout', $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }
    
    public function migrate_page_modules_data($page_modules_data) {
        $new_page_modules_data = array();

        foreach ($page_modules_data as $module) {
            if( $module['__type'] == 'page_module' ){

                // Migrate the page module data
                $module['scroll_animations_settings'] = $this->migrate_animation_data( $module['scroll_animations_settings'] );

                // Migrate the inner components of the page module
                if( isset($module['components']) && is_array($module['components']) && !empty($module['components']) ){

                    $migrated_components = array();

                    foreach ($module['components'] as $component) {
                        $migrated = $this->migrate_component_data( $component );
                        $migrated_components[] = $migrated;
                    }
                    
                    $module['components'] = $migrated_components;
                }
            }

            array_push( $new_page_modules_data, $module );
        }

        return $new_page_modules_data;
    }

    public function migrate_seccion_reusable_components_data($old_components_data){
        $new_reusable_section_data = array();

        if( is_array($old_components_data) && !empty($old_components_data) ){

            $migrated_components = array();

            foreach ($old_components_data as $component) {
                $migrated = $this->migrate_component_data( $component );
                $migrated_components[] = $migrated;
            }
                    
            $new_reusable_section_data = $migrated_components;
        }

        return $new_reusable_section_data;
    }

    public function migrate_content_layout_data($old_data) {
        $new_data = array();

        $row_count = 0;
        foreach ($old_data as $row ) {
            $new_data[$row_count] = array();
            foreach ($row as $layout_comp) {
                $new_data[$row_count][] = $this->migrate_component_data( $layout_comp );
            }
            $row_count++;
        }

        return $new_data;
    }

    public function migrate_component_data( $component ){
        // FIRST MIGRATE THE INNER WRAPPERS COMPONENTS
        $has_inner_components = $this->has_inner_components( $component['__type'] );
        if( $has_inner_components['check'] ) {
            if( is_array($has_inner_components['where']) ){
                foreach ($has_inner_components['where'] as $meta) {
                    $migrated_components = array();
                    foreach ($component[$meta] as $inner_component ) {
                        $migrated = $this->migrate_component_data( $inner_component );
                        $migrated_components[] = $migrated;
                    }
                    $component[$meta] = $migrated_components;
                }
            } else {
                if( $has_inner_components['where'] == 'in-row-content' ){
                    $column_count = 0;
                    foreach ($component['row']['content'] as $column) {
                        $migrated_components = array();
                        foreach ($column as $inner_component ) {
                            $migrated = $this->migrate_component_data( $inner_component );
                            $migrated_components[] = $migrated;
                        }
                        $component['row']['content'][$column_count] = $migrated_components;
                        $column_count++;
                    }
                }
            }
        }
        // END

        $new_component = $component;
        $new_component['scroll_animations_settings'] = $this->migrate_animation_data( $component['scroll_animations_settings'] );

        unset( $new_component['__lel2'] );
        unset( $new_component['__lel3'] );
        unset( $new_component['scroll_animation_settings'] );
        unset( $new_component['start'] );
        unset( $new_component['duration'] );
        
        // if( $component['__type'] == 'map' ){ ...}

        return $new_component;
    }

    public function migrate_animation_data( $animations ){
        $new_animation = [];
        
        if( isset($animations['groups']) && is_array($animations['groups']) ){
            $new_animation['groups'] = array();
            foreach ($animations['groups'] as $group) {
                $new_group = array();

                // MIGRATE SETTINGS
                $settings = $group['settings'];

                $start_dictionary = array(
                    'onEnter' => ['top', 'bottom'],
                    'onCenter' => ['top', 'center'],
                    'onLeave' => ['top', 'top']
                );
                $start_translated = $start_dictionary[$settings['trigger-hook']];
                if( $settings['offset'] != '0' ){
                    $start_translated[0] = $settings['offset'];
                    $start = array( 'hook' => 'custom', 'custom_hook' => implode(' ',$start_translated) );
                } else {
                    $start = array( 'hook' => implode(' ',$start_translated), 'custom_hook' => '' );
                }

                $new_group['settings'] = array(
                    'trigger_element' => $settings['trigger-element'],
                    'start_at' => $start,
                    'end_at' => array( 'basic' => $settings['duration'], 'customize' => false, 'custom' => '' ),
                    'set_pin' => $settings['set_pin'] ?? false,
                    'pin_settings' => array( 'pinned_el' => 'trigger_el', 'selector' => '', 'push_followers' => false ),
                    'trigger_carrusel' => $settings['trigger_carrusel'] ?? false,
                    'disable_on_mobile' => $settings['turn_off_in_mobile'] ?? false,
                    'add_indicators' => $settings['add_indicators'] ?? false
                );
                
                // MIGRATE TIMELINE
                $animated_properties = $group['animated_properties'];
                $timeline = array( 'groups' => array(
                    'element' => $settings['element'],
                    'animated_properties' => array(
                        'from' => $animated_properties['from'] ?? array(),
                        'to' => $animated_properties['to'] ?? array()
                    ),
                    'position' => array(
                        'key' => '',
                        'custom_key' => ''
                    )
                ));
                $new_group['timeline'] = $timeline;

                $new_animation['groups'][] = $new_group;
            }
        }

        return $new_animation;
    }

    public function has_inner_components( $component_type ){
        $has_inner_components = apply_filters('has_inner_components_filter', array(
            // 'row' => array( 'column_1', 'column_2', 'column_3', 'column_4' ), // old way
            // 'inner_row' => array( 'column_1', 'column_2', 'column_3', 'column_4' ) // old way
            'row' => 'in-row-content',
            'inner_row' => 'in-row-content'
        ));

        $check = isset( $has_inner_components[$component_type] );
        $where = ($check) ? $has_inner_components[$component_type] : array();

        return array(
            'check' => $check,
            'where' => $where
        );
    }

    public function ajax_after_data_migration() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        // ...

        wp_send_json_success(array(
            'complete' => true
        ));
    }
}