<?php
namespace Theme_Migrator\Migration;

use Theme_Migrator\Core;
use Theme_Migrator\Migration\Migrate_Page_Header_0_4_X_to_0_5_0;

class Migrate_0_4_X_to_0_5_0{
    private static $instance = null;

    private $migrator_url;

    private $batch_size;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Migrate_0_4_X_to_0_5_0();
        }
        return self::$instance;
    }

    private function __construct(){
        $this->batch_size = 3;
    }

    public function migrate(){
        add_action( 'admin_menu', array($this, 'add_admin_page') );
        add_action( 'admin_notices', array($this, 'theme_is_less_than_0_5_0_notice') );
        add_action( 'wp_ajax_process_page_data', array($this, 'ajax_process_page_data') );
        add_action( 'wp_ajax_after_data_migration', array($this, 'ajax_after_data_migration') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_scripts') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_styles') );
    }

    public function theme_is_less_than_0_5_0_notice() {
        $link_text = __('Theme Migrator', 'default');
        $translated_text = sprintf(
            __('Current theme version is less than 0.5.0 and need to be migrated. Please make a database backup and follow the next link: %s', 'default'),
            '<a href="' . esc_url($this->migrator_url) . '">' . esc_html($link_text) . '</a>'
        );

        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>' . $translated_text . '</p>';
        echo '</div>';
    }

    public function add_admin_page(){
        $slug = Core::getInstance()->get_slug();

        add_submenu_page(
            // 'theme-options-menu', // dosnt work
            'index.php',
            __('Theme Migrator', 'default'),
            __('Theme Migrator', 'default'),
            'manage_options',
            $slug,
            array($this, 'display')
        );

        $this->migrator_url = admin_url('index.php?page='.$slug);
    }

    public function display(){
        ?>
        <div class="wrap">
            <div class="theme-migrator">
                <button class="theme-migrator__init button-primary" data-status="initial">
                    <span><i class="dashicons dashicons-migrate uf-button-icon"></i> INIT MIGRATION</span>
                    <span><i class="dashicons dashicons-admin-generic uf-button-icon"></i> PROCESSING</span>
                    <span><i class="dashicons dashicons-saved uf-button-icon"></i> MIGRATION COMPLETE</span>
                    <span><i class="dashicons dashicons-warning uf-button-icon"></i> MIGRATION FAILED</span>
                </button>
            </div>
        </div>
        <?php
    }

    public function enqueue_migrator_scripts() {
        $slug = Core::getInstance()->get_slug();

        wp_enqueue_script($slug.'-script', THEME_MIGRATOR_PATH . '/scripts/migrate-0-4-X-to-0-5-0.js', array('jquery'), '1.0', true);
        
        wp_localize_script($slug.'-script', 'THEME_MIGRATOR_GLOBALS', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('process_page_data_nonce')
        ));
    }
    
    public function enqueue_migrator_styles() {
        $slug = Core::getInstance()->get_slug();
        wp_enqueue_style($slug.'-style', THEME_MIGRATOR_PATH . '/styles/styles.css', array(), '1.0');
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
            WHERE pm.meta_key IN ('v23_modulos', 'content_layout', 'page_header_element','offcanvas_element_content')
            LIMIT %d OFFSET %d",
            $batch_size,
            $offset
        ));

        $general_control = array();
    
        foreach ($pages as $page) {
            $page_control = array(
                'title' => get_the_title( $page->post_id ),
                'id' => $page->post_id,
                'posttype' => $page->post_type,
                'meta' => $page->meta_key
            );

            $old_data = maybe_unserialize($page->meta_value);
            if( $page->meta_key == 'v23_modulos' ){
                if( $page->post_type == 'seccion_reusable' || $page->post_type == 'reusable_section' ){
                    $new_reusable_section_data = $this->migrate_seccion_reusable_data($old_data);
                    update_post_meta( $page->post_id, 'components', $new_reusable_section_data );
                    $page_control['new_data'] = $new_reusable_section_data;
                } else {
                    $new_page_modules_data = $this->migrate_page_modules_data($old_data);
                    update_post_meta( $page->post_id, 'page_modules', $new_page_modules_data );
                    $page_control['new_data'] = $new_page_modules_data;
                }
            }
            if( $page->meta_key == 'content_layout' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                update_post_meta($page->post_id, 'blocks_layout', $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }
            if( $page->meta_key == 'offcanvas_element_content' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                update_post_meta($page->post_id, 'offcanvas_element_content', $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }
            if( $page->meta_key == 'page_header_element' ){
                $page_header_migrator = new Migrate_Page_Header_0_4_X_to_0_5_0( $page->post_id );
                $new_data = $page_header_migrator->migrate();
                $page_control['new_data'] = $new_data;
            }
            
            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }

    public function ajax_after_data_migration() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        global $wpdb;

        // delete orphaned post meta
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('v23_modulos', 'content_layout', 'page_header_element')");

        // change seccion_reusable post type
        $old_post_type = 'seccion_reusable';
        $new_post_type = 'reusable_section';
        $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->posts} SET post_type = %s WHERE post_type = %s", $new_post_type, $old_post_type ) );
        $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) WHERE post_type = %s", '/' . $old_post_type . '/', '/' . $new_post_type . '/',  $new_post_type) );
        wp_cache_flush();
                    
        // add_option( 'theme_version', '0.5.0' );

        wp_send_json_success(array(
            'complete' => true
        ));
    }
    
    public function migrate_page_modules_data($page_modules_data) {
        $new_page_modules_data = array();

        foreach ($page_modules_data as $module) {
            if( $module['__type'] == 'modulos' ){
                // add setting key
                $module['settings'] = $this->migrate_settings_data( $module );

                // add components key
                $module['components'] = array();

                if( isset($module['componentes']) && is_array($module['componentes']) && !empty($module['componentes']) ){
                    foreach ($module['componentes'] as $component) {

                        $has_inner_components = $this->has_inner_components( $component );
                        if( $has_inner_components['check'] ){

                            $new_wrapper_component = $this->process_inner_components( $component, $has_inner_components['where'] );
                            $module['components'][] = $new_wrapper_component;        

                        } else {
                            $module['components'][] = $this->migrate_component_data( $component );
                        }
                    }
                }

                $module['scroll_animations_settings'] = $this->migrate_scroll_animations_data( $module );

                // change __type name
                $module['__type'] = 'page_module';

                $module = $this->unset_old_settings_keys( $module );
            }

            if( $module['__type'] == 'modulos-reusables' ){
                $module = array(
                    '__type' => 'page_module',
                    '__hidden' => 0,
                    'settings' => array(),
                    'components' => array(
                        array(
                            'reusable_section' => $module['seccion_reusable'],
                            '__hidden' => 0,
                            '__type' => 'reusable_section',
                            'settings' => array()
                        )
                    )
                );
            }

            array_push( $new_page_modules_data, $module );
        }

        return $new_page_modules_data;
    }

    public function migrate_seccion_reusable_data($page_modules_data){
        $new_reusable_section_data = array();

        foreach ($page_modules_data as $module) {
            if( $module['__type'] == 'modulos' ){
                $column = array(
                    '__type' => 'columns',
                    'quantity' => 1,
                    'l_grid_1' => "repeat(1, 1fr)",
                    't_grid_1' => "1fr",
                    'm_grid_1' => "1fr",
                    'components' => array(),
                    'column_1' => array(),
                    'column_2' => array(),
                    'column_3' => array(),
                    'column_4' => array(),
                    'column_1_settings' => array( 'tablet_order'=>0, 'mobile_order'=>0, 'content_alignment'=>'flex-start', 'settings'=>array() ),
                    'column_2_settings' => array( 'tablet_order'=>0, 'mobile_order'=>0, 'content_alignment'=>'flex-start', 'settings'=>array() ),
                    'column_3_settings' => array( 'tablet_order'=>0, 'mobile_order'=>0, 'content_alignment'=>'flex-start', 'settings'=>array() ),
                    'column_4_settings' => array( 'tablet_order'=>0, 'mobile_order'=>0, 'content_alignment'=>'flex-start', 'settings'=>array() )
                );

                // add setting key
                $column['settings'] = $this->migrate_settings_data( $module );

                if( isset($module['componentes']) && is_array($module['componentes']) && !empty($module['componentes']) ){
                    foreach ($module['componentes'] as $component) {
                        $has_inner_components = $this->has_inner_components( $component );
                        if( $has_inner_components['check'] ){
                            $new_wrapper_component = $this->process_inner_components( $component, $has_inner_components['where'] );
                            if($new_wrapper_component['__type'] == 'columns') $new_wrapper_component['__type'] = 'inner_columns';
                            $column['column_1'][] = $new_wrapper_component;        
                        } else {
                            $column['column_1'][] = $this->migrate_component_data( $component );
                        }
                    }
                }

                $column['scroll_animations_settings'] = $this->migrate_scroll_animations_data( $module );
            }

            array_push( $new_reusable_section_data, $column );
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
        $new_component = $component;

        $new_component['settings'] = $this->migrate_settings_data( $component );
        $new_component['scroll_animations_settings'] = $this->migrate_scroll_animations_data( $component );

        if( isset($component['actions']) && !empty($component['actions']) ){
            $new_component['actions_settings']['actions'] = $component['actions'];
        } else {
            $new_component['actions_settings'] = array();
        }
        
        if( $component['__type'] == 'imagen' && !empty($component['bgvideo']) ){
            if( isset($component['type']) && $component['type'] == 'image' ){

                $unset_video_properties = array( 'video_source', 'external_url', 'bgvideo', 'video_type', 'video_settings' );
                foreach ($unset_video_properties as $key) {
                    unset( $new_component[ $key ] );
                }
            }
            if( isset($component['type']) && $component['type'] == 'video' ){

                $new_component['__type'] = '_video';
                $new_component['video'] = $component['bgvideo'];

                // support for old youtube meta
                if( $component['video_source'] == 'youtube' ){
                    $new_component['video_source'] = 'external';
                    $new_component['external_url'] = $component['youtube_url'];
                } 

                $unset_image_properties = array( 'image', 'alignment', 'youtube_url' );
                foreach ($unset_image_properties as $key) {
                    unset( $new_component[ $key ] );
                }
            }
        }

        if( $component['__type'] == 'imagen' && !empty($component['aspect_ratio']) ){
            $translate_aspect_ratio = array(
                'aspect-ratio-default' => 'default',
                'aspect-ratio-4-3' => '4/3',
                'aspect-ratio-1-1' => '1/1',
                'aspect-ratio-16-9' => '16/9',
                'aspect-ratio-2-1' => '2/1',
                'aspect-ratio-2_5-1' => '2.5/1',
                'aspect-ratio-4-1' => '4/1',
                'aspect-ratio-3-4' => '3/4',
                'aspect-ratio-9-16' => '9/16',
                'aspect-ratio-1-2' => '1/2',
                'aspect-ratio-1-2_5' => '1/2.5'
            );

            $new_component['aspect_ratio'] = $translate_aspect_ratio[ $component['aspect_ratio'] ];
        }

        if( $component['__type'] == 'testimonios' && !empty($component['testimonios']) ){
            $new_component['testimonials'] = array();
            foreach ($component['testimonios'] as $testi_) {
                $testi_['__type'] = 'testimonial';
                $new_component['testimonials'][] = $testi_;
            }
        }

        if( $component['__type'] == 'accordion' && !empty($component['accordion']) ){
            $new_accordion_items = array();
            foreach ($component['accordion'] as $item) {
                if( $item['content_element'] == 'layout' ){
                    $new_item = $this->process_components_layout( $item, array('content_layout') );
                } else {
                    $new_item = $item;
                }

                $translate_identifier = array( '' => '', 'icono' => 'icon', 'imagen' => 'image' );
                $translate_content_element = array(
                    'seccion_reusable' => 'reusable_section',
                    'pagina' => 'page',
                    'texto' => 'text',
                    'layout' => 'layout'
                );
                
                $new_item['title'] = $item['titulo'];
                $new_item['identifier'] = $translate_identifier[ $item['identificador'] ];
                $new_item['reusable_section'] = $item['seccion_reusable'];
                $new_item['content_element'] = $translate_content_element[ $item['content_element'] ];

                unset( $new_item['titulo'] );
                unset( $new_item['identificador'] );
                unset( $new_item['seccion_reusable'] );

                $new_accordion_items[] = $new_item;
            }
            $new_component['accordion'] = $new_accordion_items;
        }

        if( $component['__type'] == 'carrusel' && !empty($component['items']) ){
            $new_carrusel_items = array();
            foreach ($component['items'] as $item) {
                if( !empty($item['content_layout']) ){
                    $new_item = $this->process_components_layout( $item, array('content_layout') );
                    $new_item['settings'] = $this->migrate_settings_data( $item );
                    $new_item = $this->unset_old_settings_keys( $new_item );
                }

                $new_carrusel_items[] = $new_item;
            }
            $new_component['items'] = $new_carrusel_items;
        }

        if( $component['__type'] == 'grid-de-items' && !empty($component['grid_items']) ){
            $migrated_items = array();
            foreach ($component['grid_items'] as $item) {
                $item['components'] = $item['componentes'];
                $new_item = $this->process_inner_components( $item, array('components') );

                $new_item['settings'] = $this->migrate_settings_data( $item );
                $new_item = $this->unset_old_settings_keys( $new_item );
                $migrated_items[] = $new_item;
            }
            $new_component['items'] = $migrated_items;
        }

        if( 
            ($component['__type'] == 'modulos-reusables' || $component['__type'] == 'componente-reusable') &&
            !empty($component['seccion_reusable']) 
        ){
            $new_component['reusable_section'] = $component['seccion_reusable'];
        }

        $has_inner_components = $this->has_inner_components( $new_component );
        if( $has_inner_components['check'] ) $new_component = $this->process_inner_components( $new_component, $has_inner_components['where'] );

        $has_components_layout = $this->has_components_layout( $new_component );
        if( $has_components_layout['check'] ) $new_component = $this->process_components_layout( $new_component, $has_components_layout['where'] );
        
        $new_component['__type'] = $this->migrate_component_type_name( $new_component['__type'] );
        $new_component = $this->unset_old_settings_keys( $new_component );

        return $new_component;
    }

    public function migrate_settings_data( $data ){
        $new_data = array();

        if( 
            ( isset($data['module_id']) || isset($data['class']) ) &&
            ( !empty($data['module_id']) || !empty($data['class']) )
            ){
            $new_data['main_attributes'] = array();
            if( !empty($data['module_id']) ) $new_data['main_attributes']['id'] = $data['module_id'];
            if( !empty($data['class']) ) $new_data['main_attributes']['class'] = $data['class'];
        }

        if( isset($data['visibility']) && !empty($data['visibility']) ){
            $new_data['visibility'] = array(
                'use' => 1, 'key' => $data['visibility']
            );
        }

        if( isset($data['layout']) && !empty($data['layout']) ){
            if( !in_array($data['layout'], array('layout1')) ){
                $new_data['layout'] = array(
                    'use' => 1, 'key' => $data['layout']
                );
            }
        }

        if( isset($data['delete_margins']) && $data['delete_margins'] ){
            // modules used to use this
            if( isset($data['padding']) && ( $data['padding']['top'] || $data['padding']['bottom'] ) ){

                $new_data['padding'] = array( 'use' => 1 );
                $new_data['padding']['top'] = ( $data['padding']['top'] ) ? 0 : '';
                $new_data['padding']['bottom'] = ( $data['padding']['bottom'] ) ? 0 : '';
                $new_data['padding']['right'] = '';
                $new_data['padding']['left'] = '';
            }

            // components used to use this
            if( isset($data['margin']) && 
                ( $data['margin']['top'] || $data['margin']['bottom'] || $data['margin']['right'] || $data['margin']['left'] ) 
            ){
                $new_data['padding'] = array( 'use' => 1 );
                $new_data['padding']['top'] = ( $data['margin']['top'] ) ? 0 : '';
                $new_data['padding']['bottom'] = ( $data['margin']['bottom'] ) ? 0 : '';
                $new_data['padding']['right'] = ( $data['margin']['right'] ) ? 0 : '';
                $new_data['padding']['left'] = ( $data['margin']['left'] ) ? 0 : '';
            }
        }

        if( isset($data['__type']) && $data['__type'] == 'modulos' && isset($data['edit_background']) && $data['edit_background'] ){
            if( isset($data['bgi']) && !empty( $data['bgi'] ) ){
                $new_data['background_image'] = array( 'use' => 1 );
                $new_data['background_image']['image'] = $data['bgi'];
                $new_data['background_image']['settings'] = array(
                    'size' => $data['bgi_options']['size'],
                    'repeat' => $data['bgi_options']['repeat'],
                    'position_x' => $data['bgi_options']['position_x'],
                    'position_y' => $data['bgi_options']['position_y'],
                );

                if( isset($data['parallax']) && $data['parallax'] ){
                    $new_data['background_image']['settings']['parallax'] = 1;
                } else {
                    $new_data['background_image']['settings']['parallax'] = 0;
                }
            }

            if( isset($data['add_bgc']) && $data['add_bgc'] && isset($data['bgc']) && !empty($data['bgc']) ){
                $new_data['background_color'] = array( 'use' => 1, 'color' => $data['bgc'], 'alpha' => 100 );
            }

            if( isset($data['text_color']) && $data['text_color'] != 'text-color-default' ){
                $new_data['font_color'] = array( 'use' => 1, 'color_scheme' => 'dark_scheme' );
            }
        }

        if( isset($data['__type']) && $data['__type'] != 'modulos' ){
            if( isset($data['bgi']) && !empty( $data['bgi'] ) ){
                $new_data['background_image'] = array( 'use' => 1 );
                $new_data['background_image']['image'] = $data['bgi'];
                $new_data['background_image']['settings'] = array(
                    'size' => $data['bgi_options']['size'],
                    'repeat' => $data['bgi_options']['repeat'],
                    'position_x' => $data['bgi_options']['position_x'],
                    'position_y' => $data['bgi_options']['position_y'],
                );
            }
        }

        if( isset($data['show_border']) && $data['show_border'] ){
            if( isset($data['border']) ){
                $new_data['border'] = array( 'use' => 1 );

                $apply_to = ( isset($data['border_apply_to']) && $data['border_apply_to'] != 'all' ) ?
                    $data['custom_border'] : array( 'top' => 1 );

                if( isset($data['border_apply_to']) && $data['border_apply_to'] != 'all' ){
                    $new_data['border']['unlock'] = 1;
                } else {
                    $new_data['border']['unlock'] = 0;
                }

                foreach ($apply_to as $key => $value) {
                    if( $value ){
                        $new_data['border'][$key] = array( 
                            'width' => $data['border']['width'],
                            'style' => $data['border']['style'],
                            'color' => $data['border']['color']
                        );
                    }
                }
            }
        }

        if( isset($data['add_border_radius']) && $data['add_border_radius'] ){
            if( isset($data['border_radius']) ){
                $new_data['border_radius'] = array( 'use' => 1 );

                $radius_apply_to = ( isset($data['radius_apply_to']) && $data['radius_apply_to'] != 'all' ) ?
                    $data['custom_radius'] : array( 'top-left' => 1, 'top-right' => 1, 'bottom-right' => 1, 'bottom-left' => 1 ); 

                foreach ($radius_apply_to as $key => $value) {
                    if( $value == 1 ){
                        $new_data['border_radius'][ str_replace('-','_',$key) ] = $data['border_radius'];
                    } else {
                        $new_data['border_radius'][ str_replace('-','_',$key) ] = 1;
                    }
                }
            }
        }

        if( isset($data['add_video_bg']) && $data['add_video_bg'] ){
            if( 
                isset($data['bgvideo']) && isset($data['bgvideo']['videos']) 
                && is_array($data['bgvideo']['videos']) && !empty($data['bgvideo']['videos']) )
            {
                $new_data['video_background'] = array(
                    'use' => 1,
                    'video' => $data['bgvideo']
                );

                if(isset($data['video_settings'])){
                    $new_data['video_background']['video_settings'] = array(
                        'background_color' => $data['video_settings']['bgc'],
                        'opacity' => $data['video_settings']['opacity'],
                        'autoplay' => $data['video_settings']['autoplay'],
                        'muted' => $data['video_settings']['muted'],
                        'loop' => $data['video_settings']['loop']
                    );
                }

                // support for old video settings where video_opacity was the only setting
                if(isset($data['video_opacity'])){
                    $new_data['video_background']['video_settings'] = array(
                        'opacity' => $data['video_opacity']
                    );
                }
            }
        }

        if( isset($data['theme_clases']) && !empty($data['theme_clases']) && !empty($data['theme_clases'][0]) ){
            $new_data['helpers'] = array( 'use' => 1, 'list' => $data['theme_clases'] );
        }

        if( isset($data['color_de_fondo']) && is_array($data['color_de_fondo']) ){
            if( isset($data['color_de_fondo']['add_bgc']) && $data['color_de_fondo']['add_bgc'] && isset($data['color_de_fondo']['bgc']) && !empty($data['color_de_fondo']['bgc']) ){
                $new_data['background_color'] = array( 'use' => 1, 'color' => $data['color_de_fondo']['bgc'], 'alpha' => 100 );
            }
        }

        if( isset($data['color_scheme']) && !empty($data['color_scheme']) && $data['color_scheme'] != 'default-scheme' ){
            $new_data['font_color'] = array( 'use' => 1, 'color_scheme' => str_replace('-','_',$data['color_scheme']) );
        }

        if( isset($data['add_box_shadow']) && $data['add_box_shadow'] && isset($data['box_shadow']) && !empty($data['box_shadow']) ){
            $new_data['box_shadow'] = array( 'use' => 1, 'box_shadow' => $data['box_shadow'] );
        }

        return $new_data;
    }

    public function migrate_scroll_animations_data($component){
        $new_data = array();

        if( 
            isset($component['add_scroll_animation']) && 
            $component['add_scroll_animation'] && 
            !empty($component['scroll_animations']) )
        {
            $new_data['groups'] = array();

            foreach ($component['scroll_animations'] as $group) {
                $new_group = $group;
                // find .componente and replace with .component
                $old_selector_1 = $new_group['settings']['trigger-element']['selector'];
                $old_selector_2 = $new_group['settings']['element']['selector'];
                $new_group['settings']['trigger-element']['selector'] = str_replace('.componente', '.component', $old_selector_1);
                $new_group['settings']['element']['selector'] = str_replace('.componente', '.component', $old_selector_2);
                // end---
                $new_data['groups'][] = $new_group;
            }
        }

        return $new_data;
    }

    public function has_inner_components( $component ){
        $has_inner_components = array( 'columnas', 'columnas-internas' );
        $where_meta_map = array(
            'columnas' => array( 'columna_1', 'columna_2', 'columna_3', 'columna_4' ),
            'columnas-internas' => array( 'columna_1', 'columna_2', 'columna_3', 'columna_4' )
        );

        $type = $component['__type'];
        $check = in_array( $type, $has_inner_components );
        $where = ($check) ? $where_meta_map[$type] : array();

        return array(
            'check' => $check,
            'where' => $where
        );
    }

    public function has_components_layout( $component ){
        $has_inner_components = array( 'components-wrapper' );
        $where_meta_map = array(
            'components-wrapper' => array( 'content_layout' )
        );

        $type = $component['__type'];
        $check = in_array( $type, $has_inner_components );
        $where = ($check) ? $where_meta_map[$type] : array();

        return array(
            'check' => $check,
            'where' => $where
        );
    }

    public function process_inner_components( $component, $where_metas ){
        $new_component = $component;

        foreach ($where_metas as $meta) {
            $new_component[$meta] = array();
            foreach ($component[$meta] as $inner_component ) {
                $new_component[$meta][] = $this->migrate_component_data( $inner_component );
            }
        }

        if( $component['__type'] == 'columnas' || $component['__type'] == 'columnas-internas' ){
            $columns_quantity = $component['nth_columnas'];

            for ($i=1; $i <= 4; $i++) { 
                $new_component['column_'.$i] = array();
                foreach ($new_component['columna_'.$i] as $component_ ) {
                    $new_component['column_'.$i][] = $component_;
                }

                // COLUMN SETTINGS
                $new_component['column_'.$i.'_settings'] = array();
                $new_component['column_'.$i.'_settings']['content_alignment'] = $component['columna_'.$i.'_settings']['content_alignment'];
                $new_component['column_'.$i.'_settings']['mobile_order'] = $component['columna_'.$i.'_settings']['mobile_order'];
                $new_component['column_'.$i.'_settings']['tablet_order'] = $component['columna_'.$i.'_settings']['tablet_order'];
                $new_component['column_'.$i.'_settings']['theme_clases'] = $component['columna_'.$i.'_settings']['theme_clases'];
                
                $component['columna_'.$i.'_settings']['__type'] = '_fake_column_type'; // migrate_settings_data() need this prop 
                $new_component['column_'.$i.'_settings']['settings'] = $this->migrate_settings_data( $component['columna_'.$i.'_settings'] );

                // migrate old layout4 extend bg to column settings
                if( $component['layout'] == 'layout4' && ($i == 1 || $i == $columns_quantity) ){
                    $extend_bg_helper = ($i == 1) ? 'extend-bg-to-left' : 'extend-bg-to-right';
                    if( isset($new_component['column_'.$i.'_settings']['settings']['helpers']) ){
                        $new_component['column_'.$i.'_settings']['settings']['helpers']['list'][] = $extend_bg_helper;
                    } else {
                        $new_component['column_'.$i.'_settings']['settings']['helpers'] = array(
                            'use' => 1, 'list' => array($extend_bg_helper)
                        );
                    }
                }
                // END COLUMN SETTINGS

                unset( $new_component['columna_'.$i] );
                unset( $new_component['columna_'.$i.'_settings'] );
            }

            $new_component['quantity'] = $component['nth_columnas'];
            unset( $new_component['nth_columnas'] );

            $columns_widths = array( 'special_widths', 'tablet_widths', 'mobile_widths' );
            $device = array( 'l','t','m' );
            $translate_current_width = array(
                'columnas-2de3-1de3' => '2fr 1fr',
                'columnas-1de3-2de3' => '1fr 2fr',
                'columnas-1de4-3de4' => '1fr 3fr',
                'columnas-3de4-1de4' => '3fr 1fr',
                'columnas-auto-fluid' => 'auto 1fr',
                'tablet-1de2-1de2' => '1fr 1fr',
                'tablet-2de3-1de3' => '2fr 1fr',
                'tablet-1de3-2de3' => '1fr 2fr',
                'tablet-1de4-3de4' => '1fr 3fr',
                'tablet-3de4-1de4' => '3fr 1fr',
                'tablet-auto-fluid' => 'auto 1fr',
                'mobile-1de2-1de2' => '1fr 1fr',
                'mobile-2de3-1de3' => '2fr 1fr',
                'mobile-1de3-2de3' => '1fr 2fr',
                'mobile-1de4-3de4' => '1fr 3fr',
                'mobile-3de4-1de4' => '3fr 1fr',
                'mobile-auto-fluid' => 'auto 1fr',
                'columnas-1de4-2de4-1de4' => '1fr 2fr 1fr',
                'columnas-1de2-1de4-1de4' => '2fr 1fr 1fr',
                'columnas-1de4-1de4-1de2' => '1fr 1fr 2fr',
                'columnas-1de8-6de8-1de8' => '1fr 6fr 1fr',
                'tablet-1de3-1de3-1de3' => '1fr 1fr 1fr',
                'tablet-1-1de2-1de2' => 'tablet-1-1de2-1de2',
                'tablet-1de2-1de2-1' => 'tablet-1de2-1de2-1',
                'tablet-1de2-1-1de2' => 'tablet-1de2-1-1de2',
                'mobile-1de3-1de3-1de3' => '1fr 1fr 1fr',
                'tablet-1de2-1de2-1de2-1de2' => '1fr 1fr',
                'mobile-1de2-1de2-1de2-1de2' => '1fr 1fr'
            );
            // start from column 1 to set 1fr when columns_quantity is 1
            for ($i=1; $i <= 4; $i++) { 
                $count = 0;
                foreach ($columns_widths as $key) {
                    $key_to_meta = ( $i == 2 ) ? $key : $key.'_'.$i;
                    if( $new_component['quantity'] == $i ){
                        if( isset($component[$key_to_meta]) && !empty($component[$key_to_meta]) ){
                            // translate columns width key
                            $new_grid_meta = $translate_current_width[ $component[$key_to_meta] ];
                        } else {
                            // migrate empty columns widths
                            if ( $device[$count] == 'l' ) $new_grid_meta = 'repeat('.$i.', 1fr)';
                            if ( $device[$count] == 't' ) $new_grid_meta = '1fr';
                            if ( $device[$count] == 'm' ) $new_grid_meta = '1fr';
                        }
                        $device_meta = $device[$count].'_grid_'.$i;
                        $new_component[$device_meta] = $new_grid_meta;
                    }
                    unset( $new_component[$key_to_meta] );
                    $count++;
                }
            }
        }

        if( isset($component['add_scroll_animation']) && $component['add_scroll_animation'] && !empty($component['scroll_animations']) ){
            $new_component['scroll_animations_settings']['groups'] = $component['scroll_animations'];
        }

        if( isset($component['actions']) && !empty($component['actions']) ){
            $new_component['actions_settings']['actions'] = $component['actions'];
        }

        $new_component['settings'] = $this->migrate_settings_data( $component );
        $new_component['__type'] = $this->migrate_component_type_name( $component['__type'] );
        $new_component = $this->unset_old_settings_keys( $new_component );

        return $new_component;
    }

    public function process_components_layout( $component, $where_metas ){
        $new_component = $component;

        foreach ($where_metas as $meta) {
            $new_meta_name = ($meta == 'content_layout') ? 'blocks_layout' : $meta;
            $new_component[$new_meta_name] = array();
            $row_count = 0;
            foreach ($component[$meta] as $row ) {
                $new_component[$new_meta_name][$row_count] = array();
                foreach ($row as $layout_comp) {
                    $new_component[$new_meta_name][$row_count][] = $this->migrate_component_data( $layout_comp );
                }
                $row_count++;
            }
        }        

        return $new_component;
    }

    public function migrate_component_type_name( $type ){
        $translations = array(
            'editor-de-texto' => 'text_editor',
            'imagen' => 'image',
            'separador' => 'spacer',
            'columnas' => 'columns',
            'mapa' => 'map',
            'galeria' => 'gallery',
            'modulos-reusables' => 'reusable_section',
            'componente-reusable' => 'reusable_section',
            'icono-y-texto' => 'icon_and_text',
            'testimonios' => 'testimonials',
            'columnas-internas' => 'inner_columns',
            'components-wrapper' => 'components_wrapper',
            '_video' => 'video',
            'grid-de-items' => 'items_grid'
        );

        $translation = ( isset($translations[$type]) ) ? $translations[$type] : $type;

        return $translation;
    }

    public function unset_old_settings_keys( $component ){
        $keys_to_unset = array( 'componentes', 'module_id', 'class', 'visibility', 'layout', 'delete_margins', 'padding','edit_background', 'bgi', 'bgi_options', 'add_bgc', 'bgc', 'text_color', 'parallax', 'show_border', 'border', 'border_apply_to', 'custom_border', 'add_border_radius', 'border_radius', 'radius_apply_to', 'custom_radius', 'add_video_bg', 'bgvideo', 'add_scroll_animation', 'scroll_animations','actions','color_de_fondo','color_scheme', 'margin', 'add_box_shadow', 'box_shadow','testimonios', 'seccion_reusable', 'components_margin', 'theme_clases', 'add_animation', 'animation', 'grid_items', 'video_opacity'
        );

        if( $component['__type'] != 'video' ) $keys_to_unset[] = 'video_settings';
        if( $component['__type'] == 'image' || $component['__type'] == 'video' ) $keys_to_unset[] = 'type';

        foreach ($keys_to_unset as $key) {
            unset( $component[ $key ] );
        }

        return $component;
    }
}