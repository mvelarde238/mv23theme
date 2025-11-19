<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;
use Core\Utils\Helpers;
use Core\Builder\Template_Engine\Margin;
use Core\Builder\Template_Engine\Padding;
use Core\Builder\Template_Engine\Borders;
use Core\Builder\Template_Engine\Box_Shadow;
use Core\Builder\Template_Engine\Background;
use Core\Builder\Template_Engine\Color;
use Core\Builder\Template_Engine\Width;
use Core\Builder\Component\Listing;

class Migrate_2_10_X_to_3_0_0 extends Migrate_Components_Settings {
    private static $instance = null;

    private $components_mapping = array(
        'page_module' => 'section',
        'row' => 'row2',
        'components_wrapper' => 'comp-wrapper',
        // ... this apply for all cmp that are not registered in mapping: comp_{__type}:
        'text_editor' => 'comp_text_editor',
        'html' => 'comp_code',

        'carousel' => 'carousel-wrapper',
        'fake-carousel' => 'carousel',
        'carousel-item' => 'carousel-item',

        'accordion' => 'togglebox-wrapper',
        'togglebox' => 'togglebox',
        'togglebox-nav' => 'togglebox-nav',
        'togglebox-items' => 'togglebox-items',
        'togglebox-item' => 'togglebox-item',
        'accordion_button' => 'togglebox-button',

        'flip_box' => 'flipbox',
        'flipbox-front' => 'flipbox-front',
        'flipbox-back' => 'flipbox-back',

        'image' => 'figure',
        '__image' => 'image2',
        '__figcaption' => 'figcaption',
            
        'video' => 'video2',
        'map' => 'map2',
        'listing' => 'listing',
        'gallery' => 'gallery',
        'menu' => 'menu',
        'reusable_section' => 'reusable-section',
        'spacer' => 'spacer'
    );

    private $private_classes = array(
        'page_module' => array( array( 'name' => 'page-module', 'private' => 1 ) ),
        'row' => array( array( 'name' => 'row', 'private' => 1 ) )
    );

    private $breakpoints = array(
        'desktop' => null,
        'tablet' => '(max-width: 992px)',
        'mobileLandscape' => '(max-width: 768px)',
        'mobilePortrait' => '(max-width: 480px)'
    );

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $batch_size = 3;
        $do_the_update = true;
        $title = 'Migrate 2.10.X to 3.0.0 ( Gjs Builder Implementation )';
        $slug = 'migrate_2_10_x_to_3_0_0';
        $is_top_level = true;
        $meta_keys = array(
            'page_modules',
            // 'components',
            'offcanvas_element_content'
        );
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, $meta_keys );
    }

    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;
    
        // Obtener un lote de páginas a procesar
        $meta_keys_placeholders = implode(',', array_fill(0, count($this->meta_keys), '%s'));
        $query = "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key IN ($meta_keys_placeholders)
            -- AND p.ID = 312  /* SideNav1  */
            -- AND p.ID = 826  /* Page with content layout data  */
            -- AND p.ID = 734  /* _refactorizing-custom-fields */
            -- AND p.ID = 1997  /* Test Dark Theme Implementation */
            -- AND p.ID = 333  /* Test ScrollSpy */
            -- AND p.ID = 1227  /* Test Scroll Animations */
            -- AND p.ID = 1634  /* Web Demo */
            -- AND p.ID = 56 /* test */
            -- AND p.ID = 156  /* Test Gallery */
            -- AND p.ID = 345  /* Test Listing */
            -- AND p.ID = 1043 /* Test Icon and Text */
            -- AND p.ID = 1175  /* test maps */
            -- AND p.ID = 1971  /* test headings */
            -- AND p.ID = 279  /* test video */
            -- AND p.ID = 2034 /* Test Accordion */
            -- AND p.ID = 1145 /* Test Carousel */
            -- AND p.ID = 126 /* Flip Box */
            AND p.post_type != 'revision'
            LIMIT %d OFFSET %d";
        
        $prepare_values = array_merge($this->meta_keys, array($batch_size, $offset));
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

            error_log( 'Migrating page: ' . get_the_title( $page->post_id ) . ' (ID: ' . $page->post_id . ') - Meta Key: ' . $page->meta_key );

            // page_modules
            if( $page->meta_key == 'page_modules' ){
                $new_data = $this->migrate_page_modules_data($old_data);
                if($do_the_update) $this->save_in_page_content($page->post_id, $new_data);
                $page_control['new_data'] = $new_data;
            }
            // reusable_section
            if( $page->meta_key == 'components' ){
                $new_reusable_section_data = $this->migrate_seccion_reusable_components_data($old_data);
                if($do_the_update) $this->save_in_page_content($page->post_id, $new_reusable_section_data);
                $page_control['new_data'] = $new_reusable_section_data;
            }
            // OCE
            if( $page->meta_key == 'offcanvas_element_content' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data, $page->meta_key, $page->post_id);
                if($do_the_update) $this->save_in_page_content($page->post_id, $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }

            // PAGES WITH BLOCKS LAYOUT (HAVE columns COMP)
            // if( $page->meta_key == 'blocks_layout' ){
            //     $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);

            //     $page_content = get_post_meta( $page->post_id, 'page_content', true );
            //     if( empty($page_content) ){
            //         if($do_the_update){
            //             $this->save_in_page_content($page->post_id, $new_blocks_layout_data);
            //         } 
            //     } else{
            //         if($do_the_update){
            //             $this->append_to_page_content($page->post_id, $new_blocks_layout_data);
            //         }    
            //     }

            //     $page_control['new_data'] = $new_blocks_layout_data;
            // }

            // if( $page->meta_key == 'page_header_settings' ){
            //     $new_page_header_settings_data = $this->custom_migrate_settings_data($old_data);
            //     if($do_the_update) update_post_meta($page->post_id, 'page_header_settings', $new_page_header_settings_data);
            //     $page_control['new_data'] = $new_page_header_settings_data;
            // }

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }

    public function save_in_page_content( $post_id, $new_data ){
        $container_id = $this->generate_id();

        $page_content = array(
            'gjs_data' => array(
                'dataSources' => array(),
                'assets' => array(),
                'styles' => $new_data['gjs_styles'],
                'pages' => array(
                    array(
                        'frames' => array(
                            array(
                                'id' => substr( md5( uniqid() ), 0, 16 ),
                                'component' => array(
                                    'type' => 'wrapper',
                                    'stylable' => [
                                        "background",
                                        "background-color",
                                        "background-image",
                                        "background-repeat",
                                        "background-attachment",
                                        "background-position",
                                        "background-size"
                                    ],
                                    'attributes' => array(
                                        'id' => 'iwuh'
                                    ),
                                    'components' => array(
                                        array(
                                            'type' => 'container',
                                            'classes' => array('container'),
                                            'attributes' => array(),
                                            'components' => $new_data['gjs_components'],
                                            '__id' => $container_id
                                        )
                                    ),
                                    'head' => array( 'type' => 'head' ),
                                    'docEl' => array( 'tagName' => 'html' ),
                                    '__id' => 'iwuh'
                                )        
                            )
                        ),
                        'type' => 'main',
                        // random id like this: 4s0oTzohtBBDCTnt
                        'id' => substr( md5( uniqid() ), 0, 16 )
                    )
                ),
                'symbols' => array()
            ),
            'components' => array(
                array(
                    '__type' => 'wrapper',
                    '__id' => 'iwuh',
                    'components' => array(
                        array(
                            '__type' => 'container',
                            '__id' => $container_id,
                            'components' => $new_data['uf_components']
                        )
                    )
                )
            ),
            'styles' => $new_data['styles']
        );

        update_post_meta( $post_id, 'page_content', $page_content['gjs_data'] );
        update_post_meta( $post_id, 'page_content_components', $page_content['components'] );
        update_post_meta( $post_id, 'page_content_styles', $page_content['styles'] );
    }

    public function append_to_page_content( $post_id, $new_data ){
        $existing_gjs_data = get_post_meta( $post_id, 'page_content', true );
        $existing_components = get_post_meta( $post_id, 'page_content_components', true );
        $existing_styles = get_post_meta( $post_id, 'page_content_styles', true );

        // Append new data
        $existing_gjs_data['pages'][0]['frames'][0]['component']['components'] = array_merge(
            $existing_gjs_data['pages'][0]['frames'][0]['component']['components'],
            $new_data['gjs_components']
        );

        $existing_components[0]['components'] = array_merge(
            $existing_components[0]['components'],
            $new_data['uf_components']
        );

        $existing_styles .= $new_data['styles'];

        // Update post meta
        update_post_meta( $post_id, 'page_content', $existing_gjs_data );
        update_post_meta( $post_id, 'page_content_components', $existing_components );
        update_post_meta( $post_id, 'page_content_styles', $existing_styles );
    }

    public function migrate_page_modules_data($page_modules_data) {
        // create the arrays to store the final data: builder data, uf datastores and css styles
        $gjs_components = array();
        $gjs_styles = array();

        $uf_components = array();

        $css_styles = '';

        // private classes for gjs components (is it necessary?)
        $private_classes = array(
            'page_module' => array( array( 'name' => 'page-module', 'private' => 1 ) ),
            'row' => array( array( 'name' => 'row', 'private' => 1 ) )
        ); 

        // process each page module
        foreach ($page_modules_data as $module) {
            if( $module['__type'] == 'page_module' ){

                $processed_section = $this->process_component($module, $css_styles, $gjs_styles);

                $uf_section = $processed_section['uf_component'];
                $gjs_section = $processed_section['gjs_component'];
                
                // Migrate the components of the page module
                if( isset($module['components']) && is_array($module['components']) && !empty($module['components']) ){

                    $uf_components_array = array();
                    $gjs_components_array = array();
                
                    foreach ($module['components'] as $c) {
                        $processed_component = $this->process_component($c, $css_styles, $gjs_styles);

                        $uf_components_array[] = $processed_component['uf_component'];
                        $gjs_components_array[] = $processed_component['gjs_component'];
                    }
                    
                    $uf_section['components'] = $uf_components_array;
                    $gjs_section['components'] = $gjs_components_array;
                }

                $uf_section['__type'] = 'section';
                    
                array_push( $uf_components, $uf_section );
                array_push( $gjs_components, $gjs_section );
            }
        }

        return array(
            'gjs_components' => $gjs_components,
            'uf_components' => $uf_components,
            'gjs_styles' => $gjs_styles,
            'styles' => $css_styles
        );
    }

    public function migrate_content_layout_data($content_layout_data, $meta_key = '', $post_id = 0) {
        // create the arrays to store the final data: builder data, uf datastores and css styles
        $gjs_components = array();
        $gjs_styles = array();
        $uf_components = array();
        $css_styles = '';

        // create a fake uf_component to hold the content layout data
        $fake_component['__type'] = 'components_wrapper';
        $fake_component['blocks_layout'] = $content_layout_data;
        if ( $meta_key == 'offcanvas_element_content' ) {
            $fake_component['settings'] = get_post_meta( $post_id, 'offcanvas_element_settings', true );
        }

        // process the fake component
        $processed_component = $this->process_component($fake_component, $css_styles, $gjs_styles);

        // set the correct type for the processed component
        if ( $meta_key == 'offcanvas_element_content' ) {
            $processed_component['uf_component']['__type'] = 'offcanvas_element';
            $processed_component['gjs_component']['type'] = 'oce-element';

            $this->migrate_oce_post_meta_to_component( 
                $processed_component['uf_component'],
                $processed_component['gjs_component'],
                $post_id 
            );

            // move the components inside a modal content
            // modal content must be inside the oce-element gjs component
            $processed_component['gjs_component']['components'] = array(
                array(
                    'type' => 'oce-modal-content',
                    'attributes' => array(),
                    'components' => $processed_component['gjs_component']['components']
                )
            );
            
            // add an overlay as sibling of the oce element
            $gjs_components[] = array(
                'type' => 'oce-overlay',
                'attributes' => array(),
                'components' => array()
            );
        } else {
            $processed_component['uf_component']['__type'] = 'section';
            $processed_component['gjs_component']['type'] = 'comp_section';
        }

        $uf_components[] = $processed_component['uf_component'];
        $gjs_components[] = $processed_component['gjs_component'];

        return array(
            'gjs_components' => $gjs_components,
            'uf_components' => $uf_components,
            'gjs_styles' => $gjs_styles,
            'styles' => $css_styles
        );
    }

    public function migrate_seccion_reusable_components_data($components_data) {
        // create the arrays to store the final data: builder data, uf datastores and css styles
        $gjs_components = array();
        $gjs_styles = array();
        $uf_components = array();
        $css_styles = '';

        // process each component
        foreach ($components_data as $component) {
            $processed_component = $this->process_component($component, $css_styles, $gjs_styles);

            $uf_components[] = $processed_component['uf_component'];
            $gjs_components[] = $processed_component['gjs_component'];
        }

        return array(
            'gjs_components' => $gjs_components,
            'uf_components' => $uf_components,
            'gjs_styles' => $gjs_styles,
            'styles' => $css_styles
        );
    }

    public function migrate_oce_post_meta_to_component( &$uf_component, &$gjs_component, $post_id = 0 ) {
        $slug = 'offcanvas_element';
        $type = get_post_meta( $post_id, $slug.'_type', true );
        $uf_component['oce_type'] = $type;

        // migrate oce settings
        $oce_settings_key = $slug.'_'.$type.'_settings';
        $old_oce_settings = get_post_meta( $post_id, $oce_settings_key, true );
        if( isset($old_oce_settings['position']) ) $uf_component['position'] = $old_oce_settings['position'];
        if( isset($old_oce_settings['dismissible']) ) $uf_component['dismissible'] = $old_oce_settings['dismissible'];
        if( isset($old_oce_settings['close_on_click']) ) $uf_component['close_on_click'] = $old_oce_settings['close_on_click'];
        if( isset($old_oce_settings['overlay_color']) ) $uf_component['overlay_color'] = $old_oce_settings['overlay_color'];
        if( isset($old_oce_settings['max_width']) ) $uf_component['max_width'] = $old_oce_settings['max_width'];
        if( isset($old_oce_settings['max_height']) ) $uf_component['max_height'] = $old_oce_settings['max_height'];

        // create dynamic content component if needed
        $content_type = get_post_meta( $post_id, $slug.'_content_type', true );
        if( $content_type == 'async' ){
            $__id = $this->generate_id();
            $gjs_component['components'] = array(
                array(
                    'type' => 'oce-dynamic-content',
                    'attributes' => array(),
                    'components' => array(),
                    '__id' => $__id
                )
            );
            $uf_component['components'] = array(
                array(
                    '__type' => 'oce_dynamic_content',
                    '__id' => $__id,
                    'async_settings' => get_post_meta( $post_id, $slug.'_async_settings', true )
                )
            );
        }
    }

    private function process_component($component, &$css_styles, &$gjs_styles) {
        $id = $this->generate_id($component); // for id attribute in html and gjs
        $__id = 'cmp_' . substr(md5(uniqid()), 0, 8); // to connect gjs with uf component
        $components_mapping = $this->components_mapping;
        
        // Create UF component structure
        $uf_component = $component;
        $uf_component['__id'] = $__id;
        
        // Create GJS component structure
        $gjs_component = array(
            'type' => $components_mapping[$component['__type']] ?? 'comp_' . $component['__type'],
            'attributes' => array(),
            'components' => array(),
            '__id' => $__id
        );


        // Handle spaces cases _____________________________________________________________________
        if( $component['__type'] == 'image' ){
            $this->process_image_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
            
            //stop here and dont process settings, it wil be done later:
            return array(
                'uf_component' => $uf_component,
                'gjs_component' => $gjs_component
            );
        }
        if( $component['__type'] == 'html' ){
            // change type to code
            $uf_component['__type'] = 'code';
        }
        

        // Migrate settings
        $__settings = $component['settings'] ?? array();
        $migrated_settings = $this->custom_migrate_settings_data($component, $__settings, $id);
        $uf_component['settings'] = $migrated_settings['uf_settings'];

        // Add additional classes if provided
        $private_classes = $this->private_classes;
        if ( isset( $private_classes[ $component['__type'] ] ) ) {
            $gjs_component['classes'] = $private_classes[ $component['__type'] ];
        }

        // Process styles
        if ($migrated_settings['styles']) {
            $css_styles .= $migrated_settings['styles'];
        }
        if (count($migrated_settings['gjs_styles']) > 0) {
            foreach ($migrated_settings['gjs_styles'] as $gs) {
                $gjs_styles[] = $gs;
            }
        }

        // add ID attribute if needed
        if ( $migrated_settings['styles'] || count($migrated_settings['gjs_styles']) > 0) {
            $gjs_component['attributes']['id'] = $id;
            $uf_component['__gjsAttributes'] = array( 'id' => $id );
        }

        // Special handling for custom components ///////////////////////////////////////////////////////////////////////

        if( $component['__type'] == 'text_editor' || $component['__type'] == 'button' ){
            $this->process_text_editor_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'video' ){
            $this->process_video_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'heading' ){
            $this->process_heading_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'map' ){
            $this->process_map_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'icon_and_text' ){
            $this->process_icon_and_text_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'listing' ){
            $this->process_listing_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'gallery' ){
            $this->process_gallery_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'testimonials' ){
            $this->process_testimonials_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'spacer' ){
            $this->process_spacer_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        if( $component['__type'] == 'shortcode' ){
            $this->process_shortcode_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id);
        }

        // loop inner components ///////////////////////////////////////////////////////////////////////////////////////////
        $has_inner_components = $this->has_inner_components( $component['__type'] );
        if( $has_inner_components['where'] == 'in-row-content' ){
            // migrate inner components inside columns of row
            $this->process_columns_inner_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles );
        }
        if( 
            $has_inner_components['where'] == 'in-components-wrapper-content' 
            || $component['__type'] == 'carousel-item'
            || $component['__type'] == 'togglebox-item'
            || $component['__type'] == 'flipbox-front'
            || $component['__type'] == 'flipbox-back'
            // || ( isset($component['__flag']) && $component['__flag'] == 'front-content' )
            // || ( isset($component['__flag']) && $component['__flag'] == 'back-content' )
        ){
            // migrate inner components inside components_wrapper
            $this->process_layout_inner_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles );
        }
        if( $has_inner_components['where'] == 'in-carousel-content' ){
            // migrate inner components inside carousel
            $this->process_carousel_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles );
        }
        if( $has_inner_components['where'] == 'in-accordion-content' ){
            // migrate inner components inside accordion
            $this->process_accordion_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles );
        }
        if( $has_inner_components['where'] == 'in-flip-box-content' ){
            // migrate inner components inside flipbox
            $this->process_flipbox_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles );
        }

        // Handle component['settings'] 
        $this->handle_settings( $uf_component, $gjs_component, $css_styles, $gjs_styles, $id );

        return array(
            'uf_component' => $uf_component,
            'gjs_component' => $gjs_component
        );
    }

    public function custom_migrate_settings_data( $comp, $settings, $id ){
        $_return = array(
            'uf_settings' => array(),
            'gjs_styles' => array(),
            'styles' => ''
        );

        if( $comp['__type'] == 'page_module' ){ 
            $_return['gjs_styles'][] = array(
                'selectors' => array(
                    array( 'name' => 'page-module', 'private' => 1 )
                ),
                'style' => array( 
                    'padding-top' => '40px',
                    'padding-right' => '0px',
                    'padding-bottom' => '40px',
                    'padding-left' => '0px'
                ),
                'group' => 'cmp:section'
            );
        }

        // process the old settings and migrate them to the new format using $id
        $styles_to_apply = array();

        // Process style settings using helper method
        $style_mappings = [
            'background_color' => Background::class,
            'background_image' => Background::class,
            'padding' => Padding::class,
            'box_shadow' => Box_Shadow::class,
            'border' => Borders::class,
            'border_radius' => Borders::class,
            'margin' => Margin::class,
            'width' => Width::class,
            'font_color' => Color::class
        ];

        foreach ($style_mappings as $setting_key => $style_class) {
            if (isset($settings[$setting_key]) && $settings[$setting_key]['use']) {
                $this->process_style_setting($comp, $setting_key, $style_class, $id, $styles_to_apply, $_return, $settings);
            }
        }

        if( count($styles_to_apply) > 0 ) {
            $_return['gjs_styles'][] = array(
                'selectors' => array( '#' . $id ),
                'style' => $styles_to_apply
            );
        }

        $_return['uf_settings'] = $settings;

        return $_return;
    }

    private function process_style_setting($comp, $setting_key, $style_class, $id, &$styles_to_apply, &$new_settings, &$settings) {

        if($comp['__type'] == 'column'){
            // special case for column component
            $comp['settings'] = $settings;
        }
        $styles = $style_class::get_styles($comp);

        $style_string = '';
        foreach ($styles as $key => $value) {
            $styles_to_apply[$key] = $value;
            $style_string .= $key . ': ' . $value . '; ';
        }

        $new_settings['styles'] .= '#' . $id . ' { ' . $style_string . '}';
        unset($settings[$setting_key]);
    }

    private function process_text_editor_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $responsive_settings = $component['add_responsive'] ?? 0;
        if( $responsive_settings ){
            // migrate 'tablet_text_align' and 'mobile_text_align' settings
            $breakpoints = $this->breakpoints;
            
            foreach( ['tablet','mobile'] as $device ){
                $text_align_key = $device.'_text_align';
                if( isset($component[$text_align_key]) && in_array($component[$text_align_key], ['left','center','right']) ){
                    $current_device_id = ($device == 'mobile') ? 'mobilePortrait' : $device;
                    $breakpoint = $breakpoints[$current_device_id];
                    if( $breakpoint ){
                        $text_align = $component[$text_align_key];
                        // add the css style
                        $css_styles .= "@media {$breakpoint} { #{$id} { text-align: {$text_align}; } }";
                        $gjs_styles[] = array(
                            'selectors' => array( '#' . $id ),
                            'style' => array( 'text-align' => $text_align ),
                            'mediaText' => $breakpoint,
                            'atRuleType' =>  "media"
                        );
                    }
                }
            }

            $gjs_component['attributes']['id'] = $id;
            $uf_component['__gjsAttributes'] = array( 'id' => $id );
        }

        $alignment = $component['alignment'] ?? 'left'; // for button cmp
        if( $alignment != 'left' ){
            // add the css style
            $css_styles .= "#{$id} { text-align: {$alignment}; }";
            $gjs_styles[] = array(
                'selectors' => array( '#' . $id ),
                'style' => array( 'text-align' => $alignment )
            );

            $gjs_component['attributes']['id'] = $id;
            $uf_component['__gjsAttributes'] = array( 'id' => $id );
        }

        unset( $uf_component['alignment'] );
        unset( $uf_component['add_responsive'] );
        unset( $uf_component['mobile_text_align'] );
        unset( $uf_component['tablet_text_align'] );
    }

    private function process_image_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['__type'] = 'figure';
        
        // migrate alignment
        if( $component['alignment'] != 'left' ){
            $fig_id = $this->generate_id();
            $gjs_component['attributes']['id'] = $fig_id;
            $uf_component['__gjsAttributes'] = array( 'id' => $fig_id );

            $text_align = $component['alignment'];
            // add the css style
            $css_styles .= "#{$fig_id} { text-align: {$text_align}; }";
            $gjs_styles[] = array(
                'selectors' => array( '#' . $fig_id ),
                'style' => array( 'text-align' => $text_align )
            );
        }

        // Unset figure settings
        $__unwanted_fig_atts = ['actions_settings','alignment','aspect_ratio','custom_aspect_ratio','expand_on_click','external_image','external_image_credits','full_width','image','image_source','object_fit','settings','scroll_animations_settings'];
        foreach ($__unwanted_fig_atts as $att) {
            unset( $uf_component[$att] );
        }

        // create image, editing __type to avoid infinite looping
        $component['__type'] = '__image';
        $this->quick_component( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles );
        
        // edit some attributes on uf cmp
        $uf_component['components'][0]['__type'] = 'image'; // set this manually
        $img_id = $this->generate_id($component);
        $uf_component['components'][0]['__gjsAttributes'] = array( 'id' => $img_id );
        $uf_component['components'][0]['credits'] = ($component['image_source'] == 'external') ? $component['external_image_credits'] : '';

        // edit some attributes on gj cmp
        $gjs_component['components'][0]['attributes']['src'] = '__src';
        $gjs_component['components'][0]['attributes']['id'] = $img_id;
        $gjs_component['components'][0]['resizable'] = array( 'ratioDefault'=>1 );

        // migrate object fit, aspect ratio and full width
        $img_styles = array();

        // if( $component['object_fit'] != 'cover' ){
            $object_fit = $component['object_fit'];
            $css_styles .= "#{$img_id} { object-fit: {$object_fit}; }";
            $img_styles['object-fit'] = $object_fit;
        // }

        $aspect_ratio = $component['aspect_ratio'];
        $css_styles .= "#{$img_id} { aspect-ratio: {$aspect_ratio}; }";
        $img_styles['aspect-ratio'] = $aspect_ratio;

        if( $component['full_width'] ){
            $css_styles .= "#{$img_id} { width: 100%; }";
            $img_styles['width'] = '100%';
        }

        if( count($img_styles) ){
            $gjs_styles[] = array(
                'selectors' => array( '#' . $img_id ),
                'style' => $img_styles
            );
        }

        // unset image props:
        $__unwanted_img_atts = ['alignment','external_image_credits','object_fit','full_width'];
        foreach ($__unwanted_img_atts as $att) {
            unset( $uf_component['components'][0][$att] );
        }

        // create a caption 
        $figcaption = array(
            '__type' => '__figcaption'
        );
        $this->quick_component( $figcaption, $uf_component, $gjs_component, $css_styles, $gjs_styles );
    }

    private function process_video_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){

        $aspect_ratio = ( isset($component['aspect_ratio']) && $component['aspect_ratio'] != 'default' ) ? $component['aspect_ratio'] : false;
        if( $aspect_ratio ){
            $aspect_ratio_value = ( $component['aspect_ratio'] != 'custom' ) ? $component['aspect_ratio'] : $component['custom_aspect_ratio'];
            
            $css_styles .= "#{$id} { --aspect-ratio: {$aspect_ratio_value}; }";
            $gjs_styles[] = array(
                'selectors' => array( '#' . $id ),
                'style' => array( '--aspect-ratio' => $aspect_ratio_value )
            );

            $gjs_component['attributes']['id'] = $id;
            $uf_component['__gjsAttributes'] = array( 'id' => $id );
        }

        $gjs_component['classes'] = ['video2'];

        // unset object_fit
        unset( $uf_component['object_fit'] );
    }

    private function process_map_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $height = ( isset($component['height']) ) ? $component['height'] : false;
        if( $height ){
            $height_value = $height['height'] . $height['unit'];

            $css_styles .= "#{$id} { height: {$height_value}; }";
            $gjs_styles[] = array(
                'selectors' => array( '#' . $id ),
                'style' => array( 'height' => $height_value )
            );

            $gjs_component['attributes']['id'] = $id;
            $uf_component['__gjsAttributes'] = array( 'id' => $id );
        }

        // migrate "icono" to icon_data
        $uf_component['icon_data'] = array(
            'icon' => $component['icono'] ?? '',
            'width' => 38,
            'height' => 38
        );

        // migrate "info" to info_window_content
        $uf_component['info_window_content'] = $component['info'] ?? '';
        
        $gjs_component['classes'] = ['map2'];
        
        unset( $uf_component['icono'] );
        unset( $uf_component['height'] );   
    }

    private function process_heading_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $elements = [
            [ 'name' => 'heading', 'class' => '.heading__text' ],
            [ 'name' => 'tagline', 'class' => '.heading__tagline' ]
        ];

        foreach( $elements as $element ){
            if( isset($component[$element['name']]['settings']) && is_array($component[$element['name']]['settings']) ){
                $style = Color::get_styles($component[$element['name']]);
                
                if( isset($style['color']) ){
                    $style_string = 'color: ' . $style['color'] . '; ';
    
                    $selector = $id . ' ' . $element['class'];
                    $css_styles .= '#' . $selector . ' { ' . $style_string . '}';
    
                    $gjs_styles[] = array(
                        'selectors' => [],
                        'selectorsAdd' => '#' . $selector,
                        'style' => array( 'color' => $style['color'] )
                    );
    
                    unset( $uf_component[$element['name']]['settings'] );
                }
            }
        }
    }

    private function process_columns_inner_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles ){
        $column_count = 0;
        foreach ($component['row']['content'] as $column) {
                
            $uf_components_array = array();
            $gjs_components_array = array();

            foreach ($column as $inner_component ) {
                $migrated = $this->process_component( $inner_component, $css_styles, $gjs_styles );
                $uf_components_array[] = $migrated['uf_component'];
                $gjs_components_array[] = $migrated['gjs_component'];
            }

            // create the column wrapper for inner components
            $col_id = $this->generate_id();
            $col__id = 'cmp_' . substr(md5(uniqid()), 0, 8);
            // uf column
            $uf_component['components'][$column_count] = array(
                '__type' => 'column',
                '__id' => $col__id,
                'components' => $uf_components_array,
                '__gjsAttributes' => array( 'id' => $col_id )
            );
            $migrated_settings = $this->custom_migrate_settings_data(
                $uf_component['components'][$column_count], 
                $component['row']['columns_settings'][$column_count] ?? array(), 
                $col_id
            );
            $uf_component['components'][$column_count]['settings'] = $migrated_settings['uf_settings'];
            if ($migrated_settings['styles']) {
                $css_styles .= $migrated_settings['styles'];
            }
            if (count($migrated_settings['gjs_styles']) > 0) {
                foreach ($migrated_settings['gjs_styles'] as $gs) {
                    $gjs_styles[] = $gs;
                }
            }

            // gjs column
            $gjs_component['components'][$column_count] = array(
                'type' => 'column',
                'classes' => array(array( 'name' => 'column', 'private' => 1 )),
                'attributes' => array( 'id' => $col_id ),
                'components' => $gjs_components_array,
                '__id' => $col__id
            );

            // Handle column['settings'] 
            $this->handle_settings(
                $uf_component['components'][$column_count], 
                $gjs_component['components'][$column_count], 
                $css_styles, $gjs_styles, $col_id 
            );

            $column_count++;
        }

        // ////////////////////////////////////////////////////////////////////////////////////////77
        // gjs row need a 'control' property...
        $gjs_component['control'] = array(
            'desktop' => array( 'locked' => 1, 'gap' => 1, 'orders' => [], 'widths' => [] ),
            'tablet' => array( 'locked' => 1, 'widths' => [] ),
            'mobileLandscape' => array( 'locked' => 1, 'widths' => [] ),
            'mobilePortrait' => array( 'locked' => 1, 'widths' => [] )
        );

        // create the column cids
        $columns_cids = array();
        foreach ($component['row']['content'] as $index => $column) {
            $cid = 'c' . substr(md5(uniqid()), 0, 3);
            $columns_cids[$index] = $cid;
        }

        // set column widths for each device
        $devices_keys = array( 'l', 't', 'm' );
        $devices_ids = array( 'desktop', 'tablet', 'mobilePortrait' );
        $columns_quantity = count( $component['row']['content'] );
        $available_space = 100 - ( $columns_quantity - 1 );

        // special cases for fr to %
        $frames_dictionary = array(
            'repeat(2, 1fr)' => '1fr 1fr',
            'repeat(3, 1fr)' => '1fr 1fr 1fr',
            'repeat(4, 1fr)' => '1fr 1fr 1fr 1fr',
            'repeat(5, 1fr)' => '1fr 1fr 1fr 1fr 1fr',
            'repeat(6, 1fr)' => '1fr 1fr 1fr 1fr 1fr 1fr',
            'auto 1fr' => '1fr 1fr', // :/
            '1fr auto' => '1fr 1fr' // :/
        );
        $special_case_1_column = "1fr";
        $special_case_3_mapping = array(
            'tablet-1-1de2-1de2' => array(100,49.5,49.5),
            'tablet-1de2-1de2-1' => array(49.5,49.5,100),
            'tablet-1de2-1-1de2' => array(49.5,100,49.5),
            'mobile-1-1de2-1de2' => array(100,49.5,49.5),
            'mobile-1de2-1de2-1' => array(49.5,49.5,100)
        );

        $column_count = 0;
        foreach ($component['row']['content'] as $column) {
            $_count = 0;
            foreach ($devices_keys as $dk){
                $current_device_id = $devices_ids[$_count];
                if( $dk == 'l' ){
                    $gjs_component['control'][$current_device_id]['orders'][] = $columns_cids[$column_count];
                }

                // Handle case where l_grid_*, t_grid_*, m_grid_* settings are missing
                if (!isset($component['row']['row_settings'][$dk.'_grid_'.$columns_quantity])) {
                    if( $dk == 'l' ) {
                        // in laptop assume equal distribution
                        $device_fr_width = "repeat({$columns_quantity}, 1fr)";
                    } else {
                        // in tablet and mobile assume single column
                        $device_fr_width = "1fr";
                    }
                } else {
                    $device_fr_width = $component['row']['row_settings'][$dk.'_grid_'.$columns_quantity];
                }

                // Handle case where device_fr_width is an array (e.g., layout-row field)
                $device_fr_width = is_array($device_fr_width) ? implode(' ', $device_fr_width) : $device_fr_width;

                if( !$device_fr_width || $device_fr_width == '' || $device_fr_width == 'repeat(1, 1fr)' ){
                    // default value for row with 1 column without width setting
                    $device_fr_width = "1fr";
                }

                // $device_fr_width could be "1fr 1fr", "2fr 1fr", "3fr 1fr"...
                // and must be converted to percentage: "50% 50%", "66.66% 33.33%", "75% 25%"...
                $device_fr_width_array = explode(' ', $device_fr_width);
                $total_fr = 0;
                $locked = 1;
                
                // Initialize special cases for this device
                $special_case_2 = false;
                $special_case_3 = false;

                // Handle special case for "repeat" syntax
                if (array_key_exists($device_fr_width, $frames_dictionary)) {
                    $device_fr_width_array = explode(' ', $frames_dictionary[$device_fr_width]);
                }

                // Handle special case for single column "1fr" or typo "1f"
                // e.g. 2 columns: "1fr 1fr", 3 columns: "1fr 1fr 1fr" ...
                if (
                    $device_fr_width === $special_case_1_column
                    || $device_fr_width === '1f'
                    ) {
                    $device_fr_width_array = array_fill(0, $columns_quantity, '1fr');
                    $locked = 0; // unlock if all columns are equal
                }

                // Handle case where number of fr units doesn't match number of columns
                // eg '1fr 1fr' for 4 columns meaning: '1/2 + 1/2 + 1/2 + 1/2'
                if (
                    count($device_fr_width_array) !== $columns_quantity 
                    && !array_key_exists($device_fr_width, $special_case_3_mapping)
                    && $device_fr_width != "1f"
                    ) {
                    $special_case_2 = true;
                    $locked = 0;
                    $device_fr_width_array = array('1fr','1fr','1fr','1fr');
                }

                // Handle special cases like 'tablet-1-1de2-1de2'
                if (array_key_exists($device_fr_width, $special_case_3_mapping)) {
                    $special_case_3 = true;
                    $locked = 0;
                    $device_fr_width_array = array('1fr','1fr','1fr');
                }

                // Calculate total fr units
                foreach ($device_fr_width_array as $fr) {
                    $total_fr += (float) rtrim($fr, 'fr');
                }

                // Convert each fr value to percentage and assign to corresponding column
                foreach ($device_fr_width_array as $column_index => $fr) {
                    if ($device_fr_width === $special_case_1_column) {
                        $percentage = 100;
                    } elseif ($special_case_2) {
                        $percentage = $available_space / ($columns_quantity / 2);
                    } elseif ($special_case_3) {
                        $percentage = $special_case_3_mapping[$device_fr_width][$column_index];
                    } else {
                        $percentage = ($total_fr > 0) ? ( (float) rtrim($fr, 'fr') / $total_fr * $available_space ) : 0;
                    }
                    $cid = $columns_cids[$column_index];
                    $gjs_component['control'][$current_device_id]['widths'][$cid] = $percentage;
                    $gjs_component['control'][$current_device_id]['locked'] = $locked;
                }
                $_count++;
            }
            $column_count++;
        }

        // check the old orders settings and apply if exist
        $totest = array();
        $column_count = 0;
        for( $i = 0; $i < $columns_quantity; $i++ ){
            $column_setting = $component['row']['columns_settings'][$i] ?? array();
            $_count = 0;

            $column_cid = $columns_cids[$column_count];
            foreach ($devices_keys as $dk){
                $current_device_id = $devices_ids[$_count];
                $device_order = $column_setting[$dk.'_order'] ?? 0;
                $device_order = is_numeric($device_order) ? (int)$device_order : 0;
                $totest[$current_device_id][$column_cid] = $device_order;
                // sort by value in ascending order
                asort( $totest[$current_device_id] );
                // get ordered cids
                $ordered_cids = array_keys( $totest[$current_device_id] );
                // check the control if adding the order is necessary
                if( $gjs_component['control']['desktop']['orders'] !== $ordered_cids ){
                    $gjs_component['control'][$current_device_id]['orders'] = $ordered_cids;
                }
                $_count++;
            }
            
            $column_count++;
        }

        // add the css for row and columns
        $breakpoints = $this->breakpoints;

        foreach( $gjs_component['control'] as $device => $control ){
            if( isset( $gjs_component['control'][$device] ) ){
                $column_count = 0;
                foreach( $gjs_component['control'][$device]['widths'] as $cid => $width ){
                    // Add the styles for the column layout
                    $id = $uf_component['components'][$column_count]['__gjsAttributes']['id'];
                    if( $breakpoints[$device] ){
                        $css_styles .= "@media {$breakpoints[$device]} { #{$id} { width: {$width}%; } }";
                    } else {
                        $css_styles .= "#{$id} { width: {$width}%; }";
                    }
                    // add order if exist
                    if ( $device != 'desktop' ){
                        if( isset( $gjs_component['control'][$device]['orders'] ) ){
                            $order_index = array_search( $cid, $gjs_component['control'][$device]['orders'] );
                            if( $order_index !== false ){
                                $order = $order_index;
                                if( $breakpoints[$device] ){
                                    $css_styles .= "@media {$breakpoints[$device]} { #{$id} { order: {$order}; } }";
                                } else {
                                    $css_styles .= "#{$id} { order: {$order}; }";
                                }
                            }
                        }
                    }
                    $column_count++;
                }
            }
        }

        // check the content alignment for each column
        $column_count = 0;
        for( $i = 0; $i < $columns_quantity; $i++ ){
            $column_setting = $component['row']['columns_settings'][$i] ?? array();
            $_count = 0;
            $id = $uf_component['components'][$column_count]['__gjsAttributes']['id'];
            foreach ($devices_keys as $dk){
                $content_alignment = $column_setting[$dk.'_content_alignment'] ?? '';
                if( $content_alignment != 'flex-start' && $content_alignment != '' ){
                    $current_device_id = $devices_ids[$_count];
                    $breakpoint = $breakpoints[$current_device_id];
                    if( $breakpoint ){
                        $css_styles .= "@media {$breakpoint} { #{$id} { justify-content: {$content_alignment}; } }";
                        $gjs_styles[] = array(
                            'selectors' => array( '#' . $id ),
                            'style' => array( 'justify-content' => $content_alignment ),
                            'mediaText' => $breakpoint,
                            'atRuleType' =>  "media"
                        );
                    } else {
                        $css_styles .= "#{$id} { justify-content: {$content_alignment}; }";
                        $gjs_styles[] = array(
                            'selectors' => array( '#' . $id ),
                            'style' => array( 'justify-content' => $content_alignment )
                        );
                    }
                }
                $_count++;
            }
            $column_count++;
        }

        // migrate l_content_alignment=>pinned to components_wrapper.sticky
        $column_count = 0;
        for( $i = 0; $i < $columns_quantity; $i++ ){
            $column_setting = $component['row']['columns_settings'][$i] ?? array();
            $l_content_alignment = $column_setting['l_content_alignment'] ?? '';
            if( $l_content_alignment == 'pinned' ){
                $__id = 'cmp_' . substr(md5(uniqid()), 0, 8); // to connect gjs with uf component

                $gjs_comp_wrapper = array(
                    'type' => 'comp-wrapper',
                    'components' => $gjs_component['components'][$column_count]['components'],
                    '__id' => $__id
                );
                $gjs_component['components'][$column_count]['components'] = [$gjs_comp_wrapper];

                $uf_comp_wrapper = array(
                    '__type' => 'components_wrapper',
                    'components' => $uf_component['components'][$column_count]['components'],
                    '__id' => $__id
                );
                $uf_comp_wrapper['settings']['classes'] = 'sticky';
                $uf_component['components'][$column_count]['components'] = [$uf_comp_wrapper];
            }
        }

        unset( $uf_component['row'] );
    }

    private function process_layout_inner_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles ){
        $uf_components_array = array();
        $gjs_components_array = array();

        if( !isset($component['blocks_layout']) ){
            error_log(print_r( $component, true ));
        }

        foreach ($component['blocks_layout'] as $row) {
            if( 
                isset($component['blocks_layout_settings']) 
                && isset($component['blocks_layout_settings']['layout']) 
                && $component['blocks_layout_settings']['layout'] == 'flex'
                ){

                // this was a layout field in flex-mode
                foreach ($row as $_row_comp) {
                    $processed_component = $this->process_component($_row_comp, $css_styles, $gjs_styles);
    
                    $uf_components_array[] = $processed_component['uf_component'];
                    $gjs_components_array[] = $processed_component['gjs_component'];
                }

                if( isset($uf_component['__gjsAttributes']) && isset($uf_component['__gjsAttributes']['id']) ){
                    $id = $uf_component['__gjsAttributes']['id'];
                } else {
                    $id = $this->generate_id($component);
                    $gjs_component['attributes']['id'] = $id;
                    $uf_component['__gjsAttributes'] = array( 'id' => $id );
                }
                $justify_content = $component['blocks_layout_settings']['justify_content'];
                $align_items = $component['blocks_layout_settings']['align_items'];
                $css_styles .= "#{$id} { display:flex; gap:20px; justify-content: {$justify_content}; align-items: {$align_items} }";
                $gjs_styles[] = array(
                    'selectors' => array( '#' . $id ),
                    'style' => array( 
                        'display' => 'flex',
                        'gap' => '20px',
                        'justify-content' => $justify_content,
                        'align-items' => $align_items
                    )
                );

            } else {
                // layout field had 1 single component in a row
                if( count($row) === 1 ){
                    $processed_component = $this->process_component($row[0], $css_styles, $gjs_styles);
    
                    $uf_components_array[] = $processed_component['uf_component'];
                    $gjs_components_array[] = $processed_component['gjs_component'];
                } else {
                    // layout field had many components in a row
                    // create fake uf row to process him later
                    $cols_count = count($row);
                    $fake_uf_row = array(
                        '__type' => 'row',
                        'settings' => [],
                        'scroll_animations_settings' => [],
                        'actions_settings' => [],
                        'row' => [
                            'content' => [],
                            'row_settings' => array(
                                'l_grid_'.$cols_count => [],
                                't_grid_'.$cols_count => [],
                                'm_grid_'.$cols_count => '1fr',
                                'l_gap' => 20
                            ),
                            'columns_settings' => [],
                        ]
                    );
                    foreach ($row as $_row_comp) {
                        $fake_uf_row['row']['content'][] = array( $_row_comp );
                        $fake_uf_row['row']['row_settings']['l_grid_'.$cols_count][] = $_row_comp['__width'] . 'fr';
                        $fake_uf_row['row']['row_settings']['t_grid_'.$cols_count][] = $_row_comp['__width'] . 'fr';
                        $fake_uf_row['row']['columns_settings'][] = array();
                    }
    
                    // create uf/gjs row
                    $processed_component = $this->process_component($fake_uf_row, $css_styles, $gjs_styles);
    
                    $uf_components_array[] = $processed_component['uf_component'];
                    $gjs_components_array[] = $processed_component['gjs_component'];
                }
            }
        }

        $uf_component['components'] = $uf_components_array;
        $gjs_component['components'] = $gjs_components_array;

        unset( $uf_component['blocks_layout'] );
    }

    private function process_carousel_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles ){
        $uf_component['items'] = array(
            'desktop' => $component['items_in_desktop'],
            'laptop' => $component['items_in_laptop'],
            'tablet' => $component['items_in_tablet'],
            'mobile' => $component['items_in_mobile']
        );
        $uf_component['gutter'] = array(
            'desktop' => $component['gutter_in_desktop'] ? $component['gutter_in_desktop'] : 15,
            'laptop' => $component['gutter_in_laptop'] ? $component['gutter_in_laptop'] : 15,
            'tablet' => $component['gutter_in_tablet'] ? $component['gutter_in_tablet'] : 15,
            'mobile' => $component['gutter_in_mobile'] ? $component['gutter_in_mobile'] : 15
        );

        // create inner components
        $fake_carousel = array('__type'=>'fake-carousel');
        $processed_carousel = $this->process_component($fake_carousel, $css_styles, $gjs_styles);
        $gjs_carousel = $processed_carousel['gjs_component'];
        $uf_carousel = $processed_carousel['uf_component'];

        foreach ($component['items'] as $item) {
            $item['__type'] = ($item['__type']==='content') ? 'carousel-item' : $item['__type']; 
            // $item['__type'] = 'image' ??????????????????????????????????????????????????????????????????????

            $processed_item = $this->process_component($item, $css_styles, $gjs_styles);

            $gjs_carousel['components'][] = $processed_item['gjs_component'];
            $uf_carousel['components'][] = $processed_item['uf_component'];
        }

        $gjs_component['components'] = array( $gjs_carousel );
        $uf_component['components'] = array( $uf_carousel );

        unset( $uf_component['items_in_desktop'] );
        unset( $uf_component['items_in_laptop'] );
        unset( $uf_component['items_in_tablet'] );
        unset( $uf_component['items_in_mobile'] );
        unset( $uf_component['gutter_in_desktop'] );
        unset( $uf_component['gutter_in_laptop'] );
        unset( $uf_component['gutter_in_tablet'] );
        unset( $uf_component['gutter_in_mobile'] );
    }

    private function process_accordion_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles ){
        // set devices control
        $gjs_component['devicesControl'] = array(
            'desktop' => array( 
                'template' => $component['desktop_template'],
                'style' => $component['desktop_'.$component['desktop_template'].'_style']
            ),
            'mobilePortrait' => array( 
                'template' => $component['mobile_template'],
                'style' => $component['mobile_'.$component['mobile_template'].'_style']
            )
        );
        $gjs_component['tgbtemplate'] = '';
        $gjs_component['tgbstyle'] = '';

        // set uf component template and style
        $uf_component['template'] = $component['desktop_template'];
        $uf_component[$component['desktop_template'].'_style'] = $component['desktop_'.$component['desktop_template'].'_style'];

        // create data breakpoints string for ufcomponent, e. g. "desktop|tab|tab-style1,mobileLandscape|tab|tab-style1"
        $uf_component['__gjs_data_breakpoints'] = "desktop|".$component['desktop_template']."|".$component['desktop_'.$component['desktop_template'].'_style'].",mobileLandscape|".$component['mobile_template']."|".$component['mobile_'.$component['mobile_template'].'_style'];

        // create inner components
        $fake_accordion = array('__type'=>'togglebox');
        $processed_accordion = $this->process_component($fake_accordion, $css_styles, $gjs_styles);
        $gjs_accordion = $processed_accordion['gjs_component'];
        $uf_accordion = $processed_accordion['uf_component'];

        $nav = array('__type'=>'togglebox-nav');
        $this->quick_component( $nav, $uf_accordion, $gjs_accordion, $css_styles, $gjs_styles );

        $items = array('__type'=>'togglebox-items');
        $this->quick_component( $items, $uf_accordion, $gjs_accordion, $css_styles, $gjs_styles );

        foreach ($component['accordion'] as $i => $acc_item) {
            // button-------------------------------
            $button = array(
                '__type' => 'accordion_button',
                'title' => $acc_item['title'],
                'subtitle' => $acc_item['subtitle'] ?? '',
                'icon_settings' => array(
                    'type' => $acc_item['identifier'],
                    'icon' => $acc_item['icon'],
                    'image' => $acc_item['image'],
                    'image_size' => $acc_item['image_size']
                ),
                'itemid' => $acc_item['itemid'] ?? ''
            );
            $this->quick_component( $button, $uf_accordion['components'][0], $gjs_accordion['components'][0], $css_styles, $gjs_styles );

            // item----------------------------------
            $item = array('__type'=>'togglebox-item', 'blocks_layout' => [] );
            $this->quick_component( $item, $uf_accordion['components'][1], $gjs_accordion['components'][1], $css_styles, $gjs_styles );

            if($acc_item['content_element'] === 'layout'){
                $item_content = $acc_item;
                $item_content['__type'] = 'components_wrapper';
            } elseif($acc_item['content_element'] === 'text'){
                $item_content = array( '__type' => 'text_editor', 'content' => $acc_item['content'] );
            } elseif($acc_item['content_element'] === 'reusable_section'){
                $item_content = array( '__type' => 'reusable_section', 'reusable_section' => $acc_item['reusable_section'] );
            } else {
                $item_content = array( '__type' => 'text_editor', 'content' => 'how to handle a page????');
            }
            $this->quick_component( $item_content, $uf_accordion['components'][1]['components'][$i], $gjs_accordion['components'][1]['components'][$i], $css_styles, $gjs_styles );
        }

        // add attributes for funcionality
        foreach( $gjs_accordion['components'][1]['components'] as $index => $item){
            $id = $this->generate_id($item);

            // nav ----------------------|
            $gjs_accordion['components'][0]['components'][$index]['box'] = '#'.$id;

            // items --------------------|
            $gjs_accordion['components'][1]['components'][$index]['attributes']['id'] = $id;
            $gjs_accordion['components'][1]['components'][$index]['classes'] = array(
                [ 'name' => 'v23-togglebox__item', 'private' => 1 ]
            );
            if($index === 0) {
                $gjs_accordion['components'][1]['components'][$index]['classes'][] = [ 'name' => 'active', 'private' => 1 ];
            }
        }

        $gjs_component['components'] = [ $gjs_accordion ];
        $uf_component['components'] = [ $uf_accordion ];

        unset( $uf_component['accordion'] );
        unset( $uf_component['desktop_template'] );
        unset( $uf_component['desktop_accordion_template'] );
        unset( $uf_component['desktop_accordion_style'] );
        unset( $uf_component['desktop_tab_style'] );
        unset( $uf_component['mobile_template'] );
        unset( $uf_component['mobile_accordion_template'] );
        unset( $uf_component['mobile_accordion_style'] );
        unset( $uf_component['mobile_tab_style'] );
        unset( $uf_component['tab_settings'] );
    }

    private function process_flipbox_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles ){
        $front = array(
            '__type' => 'flipbox-front', 
            'settings' => $component['front_settings'],
            'blocks_layout' => $component['front_content']['blocks_layout']
        );
        $this->quick_component( $front, $uf_component, $gjs_component, $css_styles, $gjs_styles );

        $back = array(
            '__type' => 'flipbox-back', 
            'settings' => $component['back_settings'],
            'blocks_layout' => $component['back_content']['blocks_layout']
        );
        $this->quick_component( $back, $uf_component, $gjs_component, $css_styles, $gjs_styles );

        // add some classes
        $gjs_component['components'][0]['classes'][] = array( 'name' => 'flipbox-front', 'private' => 1 );
        $gjs_component['components'][1]['classes'][] = array( 'name' => 'flipbox-back', 'private' => 1 );

        // check the content alignment for box
        foreach ( ['front','back'] as $index => $key) {
            $id = $uf_component['components'][$index]['__gjsAttributes']['id'];

            $justify_content = $component[$key.'_justify_content'];
            $align_items = $component[$key.'_align_items'];

            $css_styles .= "#{$id} { justify-content: {$justify_content}; align-items: {$align_items}; }";
            $gjs_styles[] = array(
                'selectors' => array( '#' . $id ),
                'style' => array( 
                    'justify-content' => $justify_content,
                    'align-items' => $align_items
                )
            );

            unset( $uf_component[$key.'_justify_content'] );
            unset( $uf_component[$key.'_align_items'] );
        }

        unset( $uf_component['front_settings'] );
        unset( $uf_component['back_settings'] );
        unset( $uf_component['front_content'] );
        unset( $uf_component['back_content'] );
        
    }

    private function process_listing_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['source'] = $component['show'];
        $uf_component['posts_per_page'] = $component['qty'] ?? -1;
        $uf_component['listing_template'] = $component['list_template'];

        // migrate taxonomies to a better format
        $uf_component['tax_params'] = array();
        if( isset($component['taxonomies_field']) && is_array($component['taxonomies_field']) ){
            $listing_taxonomies = Listing::get_listing_taxonomies();
            foreach( $listing_taxonomies as $tax_data ){
                $tax_name = $tax_data['slug'];
                if( isset($component['taxonomies_field'][$tax_name]) ){
                    $posttype = $tax_data['cpt_slug'];
                    $uf_component['tax_params'][$posttype . '--' . $tax_name] = $component['taxonomies_field'][$tax_name];
                }
            }
        }

        if( $component['list_template'] == 'carrusel' ){
            $uf_component['listing_template'] = 'carousel';
        }

        unset( $uf_component['show'] );
        unset( $uf_component['qty'] );
        unset( $uf_component['list_template'] );
        unset( $uf_component['taxonomies_field'] );
    }

    private function process_gallery_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['marquee_settings'] = array(
            'speed' => $component['marquee_speed'],
            'fade_color' => $component['fade_color'],
        );
        $uf_component['items'] = array(
            'desktop' => $component['items_in_desktop'],
            'laptop' => $component['items_in_laptop'],
            'tablet' => $component['items_in_tablet'],
            'mobile' => $component['items_in_mobile']
        );
        $uf_component['gutter'] = array(
            'desktop' => $component['gutter_in_desktop'],
            'laptop' => $component['gutter_in_laptop'],
            'tablet' => $component['gutter_in_tablet'],
            'mobile' => $component['gutter_in_mobile']
        );
        $uf_component['action'] = array(
            'link' => $component['link'],
            'targetsize' => $component['targetsize']
        );
        $uf_component['use_id'] = array(
            'id' => $component['gallery_id'],
            'hide_gallery' => $component['hide_gallery']
        );
        $uf_component['image_quality'] = $component['size'];

        unset( $uf_component['marquee_speed'] );
        unset( $uf_component['fade_color'] );
        unset( $uf_component['items_in_desktop'] );
        unset( $uf_component['items_in_laptop'] );
        unset( $uf_component['items_in_tablet'] );
        unset( $uf_component['items_in_mobile'] );
        unset( $uf_component['gutter_in_desktop'] );
        unset( $uf_component['gutter_in_laptop'] );
        unset( $uf_component['gutter_in_tablet'] );
        unset( $uf_component['gutter_in_mobile'] );
        unset( $uf_component['link'] );
        unset( $uf_component['targetsize'] );
        unset( $uf_component['gallery_id'] );
        unset( $uf_component['hide_gallery'] );
    }

    private function process_testimonials_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['items'] = array(
            'desktop' => $component['cols_in_desktop'],
            'laptop' => $component['cols_in_desktop'],
            'tablet' => $component['cols_in_tablet'],
            'mobile' => $component['cols_in_mobile']
        );

        unset( $uf_component['cols_in_desktop'] );
        unset( $uf_component['cols_in_tablet'] );         
        unset( $uf_component['cols_in_mobile'] );
    }

    private function process_spacer_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $height_value = $component['height'] . $component['unit'];

        $css_styles .= "#{$id} { height: {$height_value}; }";

        // check if already exist a style for this id
        $last_selector_added = end( $gjs_styles );
        $last_selector_index = key( $gjs_styles );
        if( $last_selector_added && in_array( '#' . $id, $last_selector_added['selectors'] ) ){
            // update existing style
            $gjs_styles[$last_selector_index]['style']['height'] = $height_value;
        } else {
            // add new style
            $gjs_styles[] = array(
                'selectors' => array( '#' . $id ),
                'style' => array( 'height' => $height_value )
            );

            $gjs_component['attributes']['id'] = $id;
            $uf_component['__gjsAttributes'] = array( 'id' => $id );
        }

        // add spacer class
        $gjs_component['classes'][] = 'spacer';

        unset( $uf_component['spacer_options_wrapper'] );
    }

    private function process_shortcode_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['options'] = array(
            'desktop' => $component['desktop'],
            'mobile' => $component['mobile']
        );

        unset( $uf_component['_shortcodes_wrapper'] );
    }

    private function process_icon_and_text_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['icon_style'] = array(
            'fontsize' => $component['ifontsize'],
            'color' => $component['icolor'],
            'has_bgc' => $component['ihas_bgc'],
            'bgcolor' => $component['ibgc']
        );

        $uf_component['hide_icon_on_mobile'] = $component['hide-icon-on-mobile'] ?? 0;
        
        unset( $uf_component['_icon_styles_wrapper'] );
        unset( $uf_component['hide-icon-on-mobile'] );
    }

    private function handle_settings( &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        if( !isset( $uf_component['settings'] ) || !is_array( $uf_component['settings'] ) ){
            return;
        }

        // Migrate layout2 or layout3 to a wrapper ///////////////////////////////////////////////////////////////
        // check if the component have settings.layout
        if( 
            isset( $uf_component['settings']['layout'] ) 
            && $uf_component['settings']['layout']['use']
            ){
            $layout = $uf_component['settings']['layout']['key'];

            $special_layouts = array( 'layout2', 'layout3' );
            $dont_doit_for = array( 'page_module', 'components_wrapper', 'column' );
            $component_type = $uf_component['__type'];
            if( in_array( $layout, $special_layouts )  ){
                if( !in_array( $component_type, $dont_doit_for ) ){
                    $__id = 'cmp_' . substr(md5(uniqid()), 0, 8); // to connect gjs with uf component

                    unset( $gjs_component['attributes'] );
                    $gj_comp_wrapper = array(
                        'type' => 'comp-wrapper',
                        'components' => array( $gjs_component ),
                        '__id' => $__id,
                        'attributes' => array(  'id' => $id )
                    );
                    $gjs_component = $gj_comp_wrapper;
                    
                    unset( $uf_component['__gjsAttributes'] );
                    unset( $uf_component['settings']['layout'] );
                    $uf_comp_wrapper = array(
                        '__type' => 'components_wrapper',
                        'components' => array( $uf_component ),
                        '__id' => $__id,
                        '__gjsAttributes' => array( 'id' => $id )
                    );
                    $uf_comp_wrapper['settings']['layout'] = array( 'use' => 1, 'key' => $layout );
                    $uf_component = $uf_comp_wrapper;
                }
            }
        }

        // Extract id and class from main_attributes wrapper ///////////////////////////////////////////////
        if( 
            isset( $uf_component['settings']['main_attributes'] ) 
            && is_array( $uf_component['settings']['main_attributes'] )
            ){
            $main_attr = $uf_component['settings']['main_attributes'];

            // id
            if( isset( $main_attr['id'] ) && $main_attr['id'] != '' ){
                $uf_component['settings']['id'] = $main_attr['id'];
            }

            // class
            if( isset( $main_attr['class'] ) && $main_attr['class'] != '' ){
                $uf_component['settings']['classes'] = $main_attr['class'];
            }

            unset( $uf_component['settings']['main_attributes'] );
        }

        // rename reponsive to hide_on
        if( 
            isset( $uf_component['settings']['responsive'] ) 
            && is_array( $uf_component['settings']['responsive'] )
            ){
            $responsive = $uf_component['settings']['responsive'];
            $uf_component['settings']['hide_on'] = array(
                'desktop' => isset( $responsive['hide_on_desktop'] ) ? (bool)$responsive['hide_on_desktop'] : false,
                'tablet' => isset( $responsive['hide_on_tablet'] ) ? (bool)$responsive['hide_on_tablet'] : false,
                'mobile' => isset( $responsive['hide_on_mobile'] ) ? (bool)$responsive['hide_on_mobile'] : false,
            );

            unset( $uf_component['settings']['responsive'] );
        }

        // rename settings.video_background.video_settings.background_color to settings.video_background.video_settings.bgc
        if( 
            isset( $uf_component['settings']['video_background'] ) 
            && is_array( $uf_component['settings']['video_background'] )
            && isset( $uf_component['settings']['video_background']['video_settings'] )
            && is_array( $uf_component['settings']['video_background']['video_settings'] )
            && isset( $uf_component['settings']['video_background']['video_settings']['background_color'] )
            ){
            $bgc = $uf_component['settings']['video_background']['video_settings']['background_color'];
            $uf_component['settings']['video_background']['video_settings']['bgc'] = $bgc;

            unset( $uf_component['settings']['video_background']['video_settings']['background_color'] );
        }
    }

    private function quick_component($data, &$uf_parent, &$gjs_parent, &$css_styles, &$gjs_styles ){
        $created_comp = $this->process_component($data, $css_styles, $gjs_styles);
        $gjs_parent['components'][] = $created_comp['gjs_component'];
        $uf_parent['components'][] = $created_comp['uf_component'];
    }
}