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
use Ultimate_Fields\Ultimate_Builder\Templates_Generator;

class Migrate_2_10_X_to_3_0_0 extends Migrate_Components_Settings {
    private static $instance = null;

    private $components_mapping = array(
        'page_module' => 'section',
        'row' => 'row-component',
        'components_wrapper' => 'components-wrapper',
        'inner_wrapper' => 'components-wrapper',
        'html' => 'code',

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
        '__image' => 'image-component',
        '__figcaption' => 'figcaption',
            
        'video' => 'video-component',
        'map' => 'map-component',
        'listing' => 'listing',
        'gallery' => 'gallery',
        'menu' => 'menu',
        'reusable_section' => 'reusable-section',
        'spacer' => 'spacer',
        'page' => 'wrapper',
        'icon_and_text' => 'icon-and-text',
        'text_editor' => 'text-editor'
    );

    private $private_classes = array(
        'page_module' => array( array( 'name' => 'page-module', 'private' => 1 ) ),
        // if needed in future, add and test .component class on migrated gjs component:
        // 'row' => array( array( 'name' => 'row-component', 'private' => 1 ) )
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
        $delete_old_data = false; // Cleanup is handled by Cleanup_2_10_X_to_3_0_0
        $title = 'Migrate 2.10.X to 3.0.0 ( Gjs Builder Implementation )';
        $slug = 'migrate_2_10_x_to_3_0_0';
        $is_top_level = true;
        $meta_keys = array(
            'page_modules',
            'components',
            'blocks_layout',
            'offcanvas_element_content'
        );
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, $meta_keys, $delete_old_data );
    }

    public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;
    
        // Obtener un lote de páginas a procesar
        $meta_keys_placeholders = implode(',', array_fill(0, count($this->meta_keys), '%s'));
        $pages = array(
            // '312',  /* SideNav1  */
            // '826',  /* Page with content layout data  */
            '734',  /* _refactorizing-custom-fields */
            // '1997',  /* Test Dark Theme Implementation */
            // '333',  /* Test ScrollSpy */
            // '1227',  /* Test Scroll Animations */
            // '1634',  /* Web Demo */
            // '56', /* test */
            // '156',  /* Test Gallery */
            // '345',  /* Test Listing */
            // '1043', /* Test Icon and Text */
            // '1175',  /* test maps */
            // '1971',  /* test headings */
            // '279',  /* test video */
            // '2034', /* Test Accordion */
            // '1145', /* Test Carousel */
            // '126' /* Flip Box */
        );
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

                $this->transform_page_header_to_page_module( $old_data, $page->post_id );

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

            // PAGES WITH BLOCKS LAYOUT (posttypes)
            if( $page->meta_key == 'blocks_layout' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                if($do_the_update) $this->save_in_page_content($page->post_id, $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }

            // Migrate Archive Pages Post Meta
            if( $page->post_type == 'archive_page' ){
                $this->migrate_archive_page_post_meta( $page->post_id );
            }

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }

    public function save_in_page_content( $post_id, $new_data ){
        $container_id = $this->generate_id();
        $wrapper_id = $this->generate_id();
        $gjs_styles = $new_data['gjs_styles'];
        $css_styles = $new_data['styles'];
        $datastore = $new_data['datastore'];

        $fake_page_component = array(
            '__type' => 'page',
            'stylable' => [
                "background",
                "background-color",
                "background-image",
                "background-repeat",
                "background-attachment",
                "background-position",
                "background-size"
            ],
            'head' => array( 'type' => 'head' ),
            'docEl' => array( 'tagName' => 'html' )
        );

        $this->migrate_page_settings_to_page_component( $post_id, $fake_page_component );

        $processed_page = $this->process_component($fake_page_component, $css_styles, $gjs_styles, $datastore);

        $uf_wrapper = $processed_page['uf_component'];
        $datastore[$wrapper_id] = $uf_wrapper;
        
        $gjs_wrapper = $processed_page['gjs_component'];
        $gjs_wrapper['components'] = array(
            array(
                'type' => 'container',
                'classes' => array('container'),
                'attributes' => array(),
                'components' => $new_data['gjs_components'],
                '__id' => $container_id
            )
        );        

        $page_content = array(
            'gjs_data' => array(
                'dataSources' => array(),
                'assets' => array(),
                'styles' => $gjs_styles,
                'pages' => array(
                    array(
                        'frames' => array(
                            array(
                                'id' => substr( md5( uniqid() ), 0, 16 ),
                                'component' => $gjs_wrapper
                            )
                        ),
                        'type' => 'main',
                        // random id like this: 4s0oTzohtBBDCTnt
                        'id' => substr( md5( uniqid() ), 0, 16 )
                    )
                ),
                'symbols' => array()
            ),
            'datastore' => $datastore,
            'styles' => $css_styles
        );

        update_post_meta( $post_id, 'page_content', $page_content['gjs_data'] );
        update_post_meta( $post_id, 'page_content_datastore', $page_content['datastore'] );
        update_post_meta( $post_id, 'page_content_styles', $page_content['styles'] );
    }

    public function migrate_page_modules_data($page_modules_data) {
        // create the arrays to store the final data: builder data, uf datastores and css styles
        $gjs_components = array();
        $gjs_styles = array();
        $datastore = array();
        $css_styles = '';

        // private classes for gjs components (is it necessary?)
        $private_classes = array(
            'page_module' => array( array( 'name' => 'page-module', 'private' => 1 ) ),
            'row' => array( array( 'name' => 'row', 'private' => 1 ) )
        ); 

        // process each page module
        foreach ($page_modules_data as $module) {
            if( $module['__type'] == 'page_module' ){

                $processed_section = $this->process_component($module, $css_styles, $gjs_styles, $datastore);
                $gjs_section = $processed_section['gjs_component'];
                
                // Migrate the components of the page module
                if( isset($module['components']) && is_array($module['components']) && !empty($module['components']) ){

                    $gjs_components_array = array();
                
                    foreach ($module['components'] as $c) {
                        $processed_component = $this->process_component($c, $css_styles, $gjs_styles, $datastore);
                        $gjs_components_array[] = $processed_component['gjs_component'];
                    }
                    
                    $gjs_section['components'] = $gjs_components_array;
                }
                    
                array_push( $gjs_components, $gjs_section );
            }
        }

        return array(
            'gjs_components' => $gjs_components,
            'datastore' => $datastore,
            'gjs_styles' => $gjs_styles,
            'styles' => $css_styles
        );
    }

    public function migrate_content_layout_data($content_layout_data, $meta_key = '', $post_id = 0) {
        // create the arrays to store the final data: builder data, uf datastores and css styles
        $gjs_components = array();
        $gjs_styles = array();
        $datastore = array();
        $css_styles = '';

        // create a fake uf_component to hold the content layout data
        $fake_component['__type'] = 'components_wrapper';
        $fake_component['blocks_layout'] = $content_layout_data;
        if ( $meta_key == 'offcanvas_element_content' ) {
            $fake_component['settings'] = get_post_meta( $post_id, 'offcanvas_element_settings', true );
        }

        // process the fake component
        $processed_component = $this->process_component($fake_component, $css_styles, $gjs_styles, $datastore);

        // set the correct type for the processed component
        if ( $meta_key == 'offcanvas_element_content' ) {
            $processed_component['gjs_component']['type'] = 'oce-element';

            $this->migrate_oce_post_meta_to_component( 
                $datastore,
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
            $processed_component['gjs_component']['type'] = 'section';
        }

        // Update the datastore entry with the corrected __type
        // (process_component already saved it, but with the original type)
        $cmp__id = $processed_component['gjs_component']['__id'];
        if( isset($datastore[$cmp__id]) ){
            $datastore[$cmp__id]['__type'] = $processed_component['gjs_component']['type'];
        }

        $gjs_components[] = $processed_component['gjs_component'];

        return array(
            'gjs_components' => $gjs_components,
            'datastore' => $datastore,
            'gjs_styles' => $gjs_styles,
            'styles' => $css_styles
        );
    }

    public function migrate_seccion_reusable_components_data($components_data) {
        // create the arrays to store the final data: builder data, uf datastores and css styles
        $gjs_components = array();
        $gjs_styles = array();
        $datastore = array();
        $css_styles = '';

        // process each component — UF data saved to $datastore by reference
        foreach ($components_data as $component) {
            $processed_component = $this->process_component($component, $css_styles, $gjs_styles, $datastore);
            $gjs_components[] = $processed_component['gjs_component'];
        }

        return array(
            'gjs_components' => $gjs_components,
            'datastore' => $datastore,
            'gjs_styles' => $gjs_styles,
            'styles' => $css_styles
        );
    }

    public function migrate_oce_post_meta_to_component( &$datastore, &$gjs_component, $post_id = 0 ) {
        $slug = 'offcanvas_element';
        $type = get_post_meta( $post_id, $slug.'_type', true );

        // Get the datastore key for this component
        $cmp__id = $gjs_component['__id'];

        // migrate oce settings directly into datastore entry
        $datastore[$cmp__id]['oce_type'] = $type;

        $oce_settings_key = $slug.'_'.$type.'_settings';
        $old_oce_settings = get_post_meta( $post_id, $oce_settings_key, true );
        if( isset($old_oce_settings['position']) ) $datastore[$cmp__id]['position'] = $old_oce_settings['position'];
        if( isset($old_oce_settings['dismissible']) ) $datastore[$cmp__id]['dismissible'] = $old_oce_settings['dismissible'];
        if( isset($old_oce_settings['close_on_click']) ) $datastore[$cmp__id]['close_on_click'] = $old_oce_settings['close_on_click'];
        if( isset($old_oce_settings['overlay_color']) ) $datastore[$cmp__id]['overlay_color'] = $old_oce_settings['overlay_color'];
        if( isset($old_oce_settings['max_width']) ) $datastore[$cmp__id]['max_width'] = $old_oce_settings['max_width'];
        if( isset($old_oce_settings['max_height']) ) $datastore[$cmp__id]['max_height'] = $old_oce_settings['max_height'];

        // create dynamic content component if needed
        $content_type = get_post_meta( $post_id, $slug.'_content_type', true );
        if( $content_type == 'async' ){
            $dc__id = 'cmp_' . substr(md5(uniqid()), 0, 8);

            // GJS: replace children with the dynamic content component
            $gjs_component['components'] = array(
                array(
                    'type' => 'oce-dynamic-content',
                    'attributes' => array(),
                    'components' => array(),
                    '__id' => $dc__id
                )
            );

            // Save dynamic content child to datastore (flat)
            $datastore[$dc__id] = array(
                '__type' => 'oce-dynamic-content',
                'async_settings' => get_post_meta( $post_id, $slug.'_async_settings', true )
            );
        }
    }

    public function transform_page_header_to_page_module( &$page_modules_data, $post_id ) {
        $page_header_content_type = get_post_meta( $post_id, 'page_header_content_type', true );
        if( empty($page_header_content_type) ) return;
        if( $page_header_content_type == 'none' ) return;

        $page_header_settings = get_post_meta( $post_id, 'page_header_settings', true );
        $page_header_settings = (is_array( $page_header_settings)) ? $page_header_settings : array();
        
        $header_module = array(
            '__type' => 'page_module',
            'settings' => $page_header_settings,
            'components' => array()
        );

        // create the content component according to the content type
        if( $page_header_content_type == 'default' ){
            $header_module['components'][] = array(
                '__type' => 'heading',
                'heading' => array(
                    'content' => '{{page_title}}',
                    'html_tag' => 'h1',
                    'settings' => array()
                ),
                'text_align' => 'center',
                'add_tagline' => false,
                'preset' => 'style1',
                'highlighted_element' => 'heading',
                'settings' => array()
            );
        } elseif( $page_header_content_type == 'slider' ){
            $page_header_slider = get_post_meta( $post_id, 'page_header_slider', true );

            $header_module['components'][] = array(
                '__type' => 'shortcode',
                'desktop' => $page_header_slider['desktop'],
                'set_mobile_shortcode' => (!empty($page_header_slider['mobile'])) ? true : false,
                'mobile' => $page_header_slider['mobile'],
                'settings' => array()
            );
        } elseif( $page_header_content_type == 'content' ){
            $header_module['components'][] = array(
                '__type' => 'components_wrapper',
                'blocks_layout' => get_post_meta( $post_id, 'page_header_content', true )
            );
        }

        array_unshift( $page_modules_data, $header_module );
    }

    public function migrate_page_settings_to_page_component( $post_id, &$page_component ) {
        $page_bgc = get_post_meta( $post_id, 'page_bgc', true );
        if( !is_array( $page_bgc ) ) return $page_component;
        
        $settings = array();
        if( $page_bgc['add_bgc'] ){
            $settings['background_color'] = array(
                'use' => $page_bgc['add_bgc'],
                'color' => $page_bgc['bgc'],
                'alpha' => 100
            );
        } 
        $page_color_scheme = get_post_meta( $post_id, 'page_color_scheme', true );
        if( !empty( $page_color_scheme ) && $page_color_scheme == 'dark-scheme' ){
            $settings['font_color'] = array(
                'use' => 1, 'color' => '',
                'color_scheme' => 'dark_scheme'
            );
        }
        if( !empty( $settings ) ){
            $page_component['settings'] = $settings;
        }

        $page_component['place_content_under_header'] = get_post_meta( $post_id, 'remove_body_padding_top', true );

        $other_meta = ['hide_static_header','hide_static_header_logo','hide_sticky_header', 'hide_sticky_header_logo'];
        foreach( $other_meta as $om ){
            $value = get_post_meta( $post_id, $om, true );
            $page_component[$om] = $value;
        }

        return $page_component;
    }

    private function process_component($component, &$css_styles, &$gjs_styles, &$datastore) {
        $id = $this->generate_id($component); // for id attribute in html and gjs
        $__id = 'cmp_' . substr(md5(uniqid()), 0, 8); // to connect gjs with uf component
        $components_mapping = $this->components_mapping;
        
        // Create UF component structure
        $uf_component = $component;
        $uf_component['__type'] = $components_mapping[$component['__type']] ?? $component['__type'];

        if( $component['__type'] == 'inner_wrapper' ){
            $uf_component['__type'] = 'components_wrapper';
        }
        
        // Create GJS component structure
        $gjs_component = array(
            'type' => $components_mapping[$component['__type']] ?? $component['__type'],
            'attributes' => array(),
            'components' => array(),
            '__id' => $__id
        );


        // Handle spaces cases _____________________________________________________________________
        if( $component['__type'] == 'image' ){
            $this->process_image_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id, $datastore);

            // uf component dont save structure
            unset($uf_component['components']);

            // save in datastore
            $datastore[$__id] = $uf_component; 
            
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
        if( $component['__type'] == 'menu' ){
            $menu_type = $component['type'] ?? 'menu';
            $uf_component['menu_type'] = $menu_type;
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
            $this->process_icon_and_text_component($component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $id, $datastore);
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
            $this->process_columns_inner_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $datastore );
        }
        if( 
            $has_inner_components['where'] == 'in-components-wrapper-content' 
            || $component['__type'] == 'carousel-item'
            || $component['__type'] == 'togglebox-item'
            || $component['__type'] == 'flipbox-front'
            || $component['__type'] == 'flipbox-back'
        ){
            $this->process_layout_inner_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $datastore );
        }
        if( $has_inner_components['where'] == 'in-carousel-content' ){
            $this->process_carousel_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $datastore );
        }
        if( $has_inner_components['where'] == 'in-accordion-content' ){
            $this->process_accordion_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $datastore );
        }
        if( $has_inner_components['where'] == 'in-flip-box-content' ){
            $this->process_flipbox_components( $component, $uf_component, $gjs_component, $css_styles, $gjs_styles, $datastore );
        }

        // Allow custom processing after main component processing
        $filtered = apply_filters( 'mv23_migrator_after_process_component', array(
            'uf_component' => $uf_component,
            'gjs_component' => $gjs_component,
            'css_styles' => $css_styles,
            'gjs_styles' => $gjs_styles,
            'datastore' => $datastore
        ), $component, $id );
        
        $uf_component = $filtered['uf_component'];
        $gjs_component = $filtered['gjs_component'];
        $css_styles = $filtered['css_styles'];
        $gjs_styles = $filtered['gjs_styles'];
        $datastore = $filtered['datastore'];

        // Handle component['settings'] 
        $this->handle_settings( $uf_component, $gjs_component, $css_styles, $gjs_styles, $id, $datastore );

        // Handle component['actions']
        $this->handle_actions( $uf_component, $gjs_component, $css_styles, $gjs_styles, $id );

        // uf component dont save structure
        unset($uf_component['components']);

        // save in datastore
        $datastore[$__id] = $uf_component; 

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

        // if font_color.use and font_color.color_scheme is dark_scheme, set helpers.use and add 'dark-mode' to helpers.list
        if (isset($settings['font_color']) && $settings['font_color']['use'] && isset($settings['font_color']['color_scheme']) && $settings['font_color']['color_scheme'] === 'dark_scheme') {
            if (!isset($settings['helpers'])) {
                $settings['helpers'] = array('use' => true, 'list' => array('dark-mode'));
            } else {
                $settings['helpers']['use'] = true;
                if (!in_array('dark-mode', $settings['helpers']['list'])) {
                    $settings['helpers']['list'][] = 'dark-mode';
                }
            }
        }

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
        }

        if( $component['__type'] == 'button' ){
            $alignment = $component['alignment'] ?? 'left';
            if( $alignment != 'left' ){
                // add the css style
                $css_styles .= "#{$id} { text-align: {$alignment}; }";
                $gjs_styles[] = array(
                    'selectors' => array( '#' . $id ),
                    'style' => array( 'text-align' => $alignment )
                );
    
                $gjs_component['attributes']['id'] = $id;
            }

            $button_type = $component['type'] ?? 'link';
            $uf_component['button_type'] = $button_type;

            $button_style = $component['style'] ?? 'btn btn--main-color';
            $uf_component['button_style'] = $button_style;

            $button_attributes = $component['attributes'] ?? array();
            $uf_component['button_attributes'] = $button_attributes;

            if( empty($uf_component['text']) ){
                $uf_component['text'] = 'Button';
            }
        }
            
        unset( $uf_component['style'] );
        unset( $uf_component['attributes'] );
        unset( $uf_component['type'] );
        unset( $uf_component['alignment'] );
        unset( $uf_component['add_responsive'] );
        unset( $uf_component['mobile_text_align'] );
        unset( $uf_component['tablet_text_align'] );
    }

    private function process_image_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id, &$datastore ){
        $uf_component['__type'] = 'figure';
        
        // migrate alignment
        $alignment = $component['alignment'] ?? 'left';
        if( $alignment != 'left' ){
            $fig_id = $this->generate_id();
            $gjs_component['attributes']['id'] = $fig_id;

            $css_styles .= "#{$fig_id} { text-align: {$alignment}; }";
            $gjs_styles[] = array(
                'selectors' => array( '#' . $fig_id ),
                'style' => array( 'text-align' => $alignment )
            );
        }

        // Save image-specific values needed for CSS before cleaning
        $object_fit = $component['object_fit'] ?? 'cover';
        $aspect_ratio = $component['aspect_ratio'] ?? 'auto';
        $custom_aspect_ratio = $component['custom_aspect_ratio'] ?? '';
        $full_width = isset($component['full_width']) && $component['full_width'];

        // Unset figure-level attributes from UF component (figure's datastore entry)
        $__unwanted_fig_atts = ['actions_settings','alignment','aspect_ratio','custom_aspect_ratio','expand_on_click','external_image','external_image_credits','full_width','image','image_source','object_fit','settings','scroll_animations_settings'];
        foreach ($__unwanted_fig_atts as $att) {
            unset( $uf_component[$att] );
        }

        // Clean consumed/figure-level attributes from $component before creating image child,
        // so the child's datastore entry won't have them (already saved to local vars above)
        $__consumed_from_child = ['alignment','aspect_ratio','custom_aspect_ratio','full_width','object_fit','scroll_animations_settings'];
        foreach ($__consumed_from_child as $att) {
            unset( $component[$att] );
        }

        // Pre-generate image id and inject into component so process_component uses it consistently
        // for both settings CSS and GJS attributes (avoids id mismatch)
        $img_id = $this->generate_id();
        if( !isset($component['settings']) || !is_array($component['settings']) ){
            $component['settings'] = array();
        }
        if( !isset($component['settings']['main_attributes']) || !is_array($component['settings']['main_attributes']) ){
            $component['settings']['main_attributes'] = array();
        }
        if( empty($component['settings']['main_attributes']['id']) ){
            $component['settings']['main_attributes']['id'] = $img_id;
        } else {
            $img_id = $component['settings']['main_attributes']['id'];
        }

        // create image child — UF data saved to $datastore inside process_component
        $component['__type'] = '__image';
        // inject image-specific values needed for CSS
        $component['aspect_ratio'] = $aspect_ratio;
        $component['custom_aspect_ratio'] = $custom_aspect_ratio;
        $processed_image = $this->process_component( $component, $css_styles, $gjs_styles, $datastore );
        $gjs_component['components'][] = $processed_image['gjs_component'];

        // Ensure GJS child has correct attributes (id may not be set if no settings generated styles)
        $child__id = $gjs_component['components'][0]['__id'];
        $gjs_component['components'][0]['attributes']['id'] = $img_id;
        $gjs_component['components'][0]['attributes']['src'] = '__src';
        $gjs_component['components'][0]['resizable'] = array( 'ratioDefault'=>1 );

        // Generate CSS for image-specific styles (using the same $img_id)
        $img_styles = array();

        $css_styles .= "#{$img_id} { object-fit: {$object_fit}; }";
        $img_styles['object-fit'] = $object_fit;

        $css_styles .= "#{$img_id} { aspect-ratio: {$aspect_ratio}; }";
        $img_styles['aspect-ratio'] = $aspect_ratio;

        if( $full_width ){
            $css_styles .= "#{$img_id} { width: 100%; }";
            $img_styles['width'] = '100%';
        }

        if( count($img_styles) ){
            $gjs_styles[] = array(
                'selectors' => array( '#' . $img_id ),
                'style' => $img_styles
            );
        }

        // create figcaption child
        $figcaption = array( '__type' => '__figcaption' );
        $processed_figcaption = $this->process_component( $figcaption, $css_styles, $gjs_styles, $datastore );
        $gjs_component['components'][] = $processed_figcaption['gjs_component'];
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
        }

        $gjs_component['classes'] = ['video-component'];

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
        }

        // migrate "icono" to icon_data
        $uf_component['icon_data'] = array(
            'icon' => $component['icono'] ?? '',
            'width' => 38,
            'height' => 38
        );

        // migrate "info" to info_window_content
        $uf_component['info_window_content'] = $component['info'] ?? '';
        
        $gjs_component['classes'] = ['map-component','component'];
        
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

        $accent_color_settings = $component['accent_color'] ?? array();
        if( isset($accent_color_settings['use_color']) && $accent_color_settings['use_color'] ){
            $accent_color_settings['color_variable'] = 'Use ColorPicker';
            unset( $accent_color_settings['use_color'] );
            $uf_component['accent_color'] = $accent_color_settings;
        }
    }

    private function process_columns_inner_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, &$datastore ){
        $uf_component['components'] = array(); // Reset — will hold column UF data temporarily
        $column_count = 0;
        foreach ($component['row']['content'] as $column) {
            $gjs_components_array = array();

            // Process inner components — each saves its UF data to $datastore via process_component
            foreach ($column as $inner_component ) {
                $migrated = $this->process_component( $inner_component, $css_styles, $gjs_styles, $datastore );
                $gjs_components_array[] = $migrated['gjs_component'];
            }

            // Create column wrapper IDs
            $col_id = $this->generate_id();
            $col__id = 'cmp_' . substr(md5(uniqid()), 0, 8);

            // Create UF column data (saved to $datastore at end of method)
            $uf_component['components'][$column_count] = array(
                '__type' => 'column',
                '__id' => $col__id
            );

            // Migrate column settings
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

            // GJS column — stores full tree with children
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
                $css_styles, $gjs_styles, $col_id, $datastore
            );

            $column_count++;
        }

        // ////////////////////////////////////////////////////////////////////////////////////////77
        // a 'control' to store widths and orders per device
        $__gjs_cmp['control'] = array(
            'desktop' => array( 'locked' => 1, 'gap' => 1, 'orders' => [], 'widths' => [] ),
            'tablet' => array( 'locked' => 1, 'widths' => [] ),
            'mobileLandscape' => array( 'locked' => 1, 'widths' => [] ),
            'mobilePortrait' => array( 'locked' => 1, 'widths' => [] )
        );

        // create the column cids mapping
        $columns_cids = array();
        foreach ($gjs_component['components'] as $index => $column) {
            $cid = $column['__id'];
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
                    $__gjs_cmp['control'][$current_device_id]['orders'][] = $columns_cids[$column_count];
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
                    $__gjs_cmp['control'][$current_device_id]['widths'][$cid] = $percentage;
                    $__gjs_cmp['control'][$current_device_id]['locked'] = $locked;
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
                if( $__gjs_cmp['control']['desktop']['orders'] !== $ordered_cids ){
                    $__gjs_cmp['control'][$current_device_id]['orders'] = $ordered_cids;
                }
                $_count++;
            }
            
            $column_count++;
        }

        // add the css for row and columns
        $breakpoints = $this->breakpoints;

        // get or set the row ID
        if( !empty($gjs_component['attributes']['id']) ){
            $row_id = $gjs_component['attributes']['id'];
        } else {
            $row_id = $this->generate_id($component);
            $gjs_component['attributes']['id'] = $row_id;
        }

        foreach( $__gjs_cmp['control'] as $device => $control ){
            if( isset( $__gjs_cmp['control'][$device] ) ){
                $column_count = 0;
                foreach( $__gjs_cmp['control'][$device]['widths'] as $cid => $width ){
                    // Add the styles for the column layout
                    $id = $gjs_component['components'][$column_count]['attributes']['id'];
                    if( $breakpoints[$device] ){
                        $css_styles .= "@media {$breakpoints[$device]} { #{$id} { width: {$width}%; } }";
                        $gjs_styles[] = array(
                            'selectors' => array( '#' . $id ),
                            'style' => array( 'width' => $width . '%' ),
                            'mediaText' => $breakpoints[$device],
                            'atRuleType' =>  "media"
                        );
                    } else {
                        $css_styles .= "#{$id} { width: {$width}%; }";
                        $gjs_styles[] = array(
                            'selectors' => array( '#' . $id ),
                            'style' => array( 'width' => $width . '%' )
                        );
                    }
                    // add order if exist
                    if ( $device != 'desktop' ){
                        if( isset( $__gjs_cmp['control'][$device]['orders'] ) ){
                            $order_index = array_search( $cid, $__gjs_cmp['control'][$device]['orders'] );
                            if( $order_index !== false ){
                                $order = $order_index;
                                if( $breakpoints[$device] ){
                                    $css_styles .= "@media {$breakpoints[$device]} { #{$id} { order: {$order}; } }";
                                    $gjs_styles[] = array(
                                        'selectors' => array( '#' . $id ),
                                        'style' => array( 'order' => $order ),
                                        'mediaText' => $breakpoints[$device],
                                        'atRuleType' =>  "media"
                                    );
                                } else {
                                    $css_styles .= "#{$id} { order: {$order}; }";
                                    $gjs_styles[] = array(
                                        'selectors' => array( '#' . $id ),
                                        'style' => array( 'order' => $order )
                                    );
                                }
                            }
                        }
                    }
                    $column_count++;
                }

                // migrate row gap and locked (flex-wrap)
                $flex_wrap = ($__gjs_cmp['control'][$device]['locked'] == 1) ? 'nowrap' : 'wrap';
                if( $breakpoints[$device] ){
                    $css_styles .= "@media {$breakpoints[$device]} { #{$row_id} { gap: 2%; flex-wrap: {$flex_wrap}; } }";
                    $gjs_styles[] = array(
                        'selectors' => array( '#' . $row_id ),
                        'style' => array( 
                            'gap' => '2%',
                            'flex-wrap' => $flex_wrap
                        ),
                        'mediaText' => $breakpoints[$device],
                        'atRuleType' =>  "media"
                    );
                } else {
                    $css_styles .= "#{$row_id} { gap: 2%; flex-wrap: {$flex_wrap}; }";
                    $gjs_styles[] = array(
                        'selectors' => array( '#' . $row_id ),
                        'style' => array( 
                            'gap' => '2%',
                            'flex-wrap' => $flex_wrap
                        )
                    );
                }
            }
        }

        // check the content alignment for each column
        $column_count = 0;
        for( $i = 0; $i < $columns_quantity; $i++ ){
            $column_setting = $component['row']['columns_settings'][$i] ?? array();
            $_count = 0;
            $id = $gjs_component['components'][$column_count]['attributes']['id'];
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
        for( $i = 0; $i < $columns_quantity; $i++ ){
            $column_setting = $component['row']['columns_settings'][$i] ?? array();
            $l_content_alignment = $column_setting['l_content_alignment'] ?? '';
            if( $l_content_alignment == 'pinned' ){
                $wrapper__id = 'cmp_' . substr(md5(uniqid()), 0, 8);

                // GJS: wrap column children in a components-wrapper
                $gjs_comp_wrapper = array(
                    'type' => 'components-wrapper',
                    'components' => $gjs_component['components'][$i]['components'],
                    '__id' => $wrapper__id
                );
                $gjs_component['components'][$i]['components'] = [$gjs_comp_wrapper];

                // UF: save sticky wrapper to datastore (flat, no structure)
                $datastore[$wrapper__id] = array(
                    '__type' => 'components-wrapper',
                    'settings' => array( 'classes' => 'sticky' )
                );
            }
        }

        // Save each column's UF data to $datastore (flat, without 'components' hierarchy)
        if( isset($uf_component['components']) && is_array($uf_component['components']) ){
            foreach ($uf_component['components'] as $col_uf) {
                if( isset($col_uf['__id']) ){
                    $col_datastore_entry = $col_uf;
                    unset($col_datastore_entry['components']); // datastore doesn't store structure
                    unset($col_datastore_entry['__id']); // __id is the key, not needed inside
                    $datastore[$col_uf['__id']] = $col_datastore_entry;
                }
            }
        }

        unset( $uf_component['row'] );
    }

    private function process_layout_inner_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, &$datastore ){
        $gjs_components_array = array();

        if( !isset($component['blocks_layout']) || !is_array($component['blocks_layout']) ){
            error_log('process_layout_inner_components: blocks_layout missing for component type: ' . ($component['__type'] ?? 'unknown'));
            return;
        }

        foreach ($component['blocks_layout'] as $row) {
            if( 
                isset($component['blocks_layout_settings']) 
                && isset($component['blocks_layout_settings']['layout']) 
                && $component['blocks_layout_settings']['layout'] == 'flex'
                ){

                // this was a layout field in flex-mode
                foreach ($row as $_row_comp) {
                    $processed_component = $this->process_component($_row_comp, $css_styles, $gjs_styles, $datastore);
                    $gjs_components_array[] = $processed_component['gjs_component'];
                }

                if( !empty($gjs_component['attributes']['id']) ){
                    $id = $gjs_component['attributes']['id'];
                } else {
                    $id = $this->generate_id($component);
                    $gjs_component['attributes']['id'] = $id;
                }
                $justify_content = $component['blocks_layout_settings']['justify_content'];
                $align_items = $component['blocks_layout_settings']['align_items'];
                $css_styles .= "#{$id} { display:flex; flex-direction: row; flex-wrap: wrap; gap:20px; justify-content: {$justify_content}; align-items: {$align_items} }";
                $gjs_styles[] = array(
                    'selectors' => array( '#' . $id ),
                    'style' => array( 
                        'display' => 'flex',
                        'flex-direction' => 'row',
                        'flex-wrap' => 'wrap',
                        'gap' => '20px',
                        'justify-content' => $justify_content,
                        'align-items' => $align_items
                    )
                );

            } else {
                // layout field had 1 single component in a row
                if( count($row) === 1 ){
                    $processed_component = $this->process_component($row[0], $css_styles, $gjs_styles, $datastore);
                    $gjs_components_array[] = $processed_component['gjs_component'];
                } else {
                    // layout field had many components in a row
                    // create fake uf row to process it (will recurse into process_columns_inner_components)
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
                        $fake_uf_row['row']['row_settings']['l_grid_'.$cols_count][] = ($_row_comp['__width'] ?? 1) . 'fr';
                        $fake_uf_row['row']['row_settings']['t_grid_'.$cols_count][] = ($_row_comp['__width'] ?? 1) . 'fr';
                        $fake_uf_row['row']['columns_settings'][] = array();
                    }
    
                    // process_component will save the row + columns + children in $datastore
                    $processed_component = $this->process_component($fake_uf_row, $css_styles, $gjs_styles, $datastore);
                    $gjs_components_array[] = $processed_component['gjs_component'];
                }
            }
        }

        // GJS stores the full tree structure
        $gjs_component['components'] = $gjs_components_array;

        // Clean up old data from UF component (datastore is flat, no structure needed)
        unset( $uf_component['blocks_layout'] );
        unset( $uf_component['blocks_layout_settings'] );
    }

    private function process_carousel_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, &$datastore ){
        // Migrate carousel settings to structured format
        $uf_component['controls_settings'] = array(
            'show' => $component['show_controls'] ?? true,
            'position' => $component['controls_position'] ?? 'center',
        );
        $uf_component['nav_settings'] = array(
            'show' => $component['show_nav'] ?? false,
            'position' => $component['nav_position'] ?? 'bottom',
        );
        $uf_component['autoplay_settings'] = array(
            'active' => $component['autoplay'] ?? false,
            'timeout' => $component['autoplay_timeout'] ?? 3000,
        );
        $uf_component['carousel_mode'] = array(
            'active' => false,
            'mode' => $component['mode'] ?? 'carousel',
            'axis' => $component['axis'] ?? 'horizontal',
            'speed' => $component['speed'] ?? 450
        );
        $uf_component['marquee_settings'] = array(
            'speed' => $component['marquee_speed'] ?? 18,
            'fade_width' => '100px'
        );
        $uf_component['customize_icons'] = array(
            'active' => false,
            'prev_icon' => $component['prev_icon'] ?? '',
            'next_icon' => $component['next_icon'] ?? ''
        );

        $uf_component['items'] = array(
            'desktop' => $component['items_in_desktop'],
            'laptop' => $component['items_in_laptop'],
            'tablet' => $component['items_in_tablet'],
            'mobile' => $component['items_in_mobile']
        );
        $uf_component['gutter'] = array(
            'desktop' => ($component['gutter_in_desktop'] ?? false) ? $component['gutter_in_desktop'] : 15,
            'laptop' => ($component['gutter_in_laptop'] ?? false) ? $component['gutter_in_laptop'] : 15,
            'tablet' => ($component['gutter_in_tablet'] ?? false) ? $component['gutter_in_tablet'] : 15,
            'mobile' => ($component['gutter_in_mobile'] ?? false) ? $component['gutter_in_mobile'] : 15
        );

        // Create fake-carousel wrapper — its UF data is saved to $datastore by process_component
        $fake_carousel = array('__type'=>'fake-carousel');
        $processed_carousel = $this->process_component($fake_carousel, $css_styles, $gjs_styles, $datastore);
        $gjs_carousel = $processed_carousel['gjs_component'];

        // Process each carousel item — each saves its own UF data to $datastore via process_component
        $carousel_items = $component['items'] ?? array();
        foreach ($carousel_items as $item) {
            $item['__type'] = ($item['__type'] === 'content') ? 'carousel-item' : $item['__type'];

            $processed_item = $this->process_component($item, $css_styles, $gjs_styles, $datastore);
            $gjs_carousel['components'][] = $processed_item['gjs_component'];
        }

        // GJS stores the full tree: carousel-wrapper > carousel > [carousel-items...]
        $gjs_component['components'] = array( $gjs_carousel );

        // Clean up old properties from UF component (carousel-wrapper's datastore entry)
        $to_unset = ['items_in_desktop','items_in_laptop','items_in_tablet','items_in_mobile','gutter_in_desktop','gutter_in_laptop','gutter_in_tablet','gutter_in_mobile','show_controls','controls_position','show_nav','nav_position','autoplay','autoplay_timeout','mode','axis','speed','marquee_speed','prev_icon','next_icon'];
        foreach ($to_unset as $key) {
            unset( $uf_component[$key] );
        }
    }

    private function process_accordion_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, &$datastore ){
        // set devices control (GJS-only attribute for responsive template switching)
        $desktop_template = $component['desktop_template'] ?? 'accordion';
        $mobile_template = $component['mobile_template'] ?? 'accordion';
        $desktop_style = $component['desktop_'.$desktop_template.'_style'] ?? 'accordion-style1';
        $mobile_style = $component['mobile_'.$mobile_template.'_style'] ?? 'accordion-style1';

        $gjs_component['devicesControl'] = array(
            'desktop' => array( 'template' => $desktop_template, 'style' => $desktop_style ),
            'mobilePortrait' => array( 'template' => $mobile_template, 'style' => $mobile_style )
        );
        $gjs_component['tgbtemplate'] = '';
        $gjs_component['tgbstyle'] = '';

        // set uf component template and style
        $uf_component['template'] = $desktop_template;
        $uf_component[$desktop_template.'_style'] = $desktop_style;

        // Create togglebox wrapper — UF saved to $datastore by process_component
        $fake_accordion = array('__type'=>'togglebox');
        $processed_accordion = $this->process_component($fake_accordion, $css_styles, $gjs_styles, $datastore);
        $gjs_accordion = $processed_accordion['gjs_component'];

        // Create togglebox-nav and togglebox-items containers
        $nav_data = array('__type'=>'togglebox-nav');
        $processed_nav = $this->process_component($nav_data, $css_styles, $gjs_styles, $datastore);
        $gjs_nav = $processed_nav['gjs_component'];

        $items_data = array('__type'=>'togglebox-items');
        $processed_items = $this->process_component($items_data, $css_styles, $gjs_styles, $datastore);
        $gjs_items = $processed_items['gjs_component'];

        // Process each accordion item
        $accordion_items = $component['accordion'] ?? array();
        foreach ($accordion_items as $i => $acc_item) {
            // button — saved to $datastore by process_component
            $button = array(
                '__type' => 'accordion_button',
                'title' => $acc_item['title'] ?? '',
                'subtitle' => $acc_item['subtitle'] ?? '',
                'icon_settings' => array(
                    'type' => $acc_item['identifier'] ?? '',
                    'icon' => $acc_item['icon'] ?? '',
                    'image' => $acc_item['image'] ?? '',
                    'image_size' => $acc_item['image_size'] ?? ''
                ),
                'itemid' => $acc_item['itemid'] ?? ''
            );
            $processed_button = $this->process_component($button, $css_styles, $gjs_styles, $datastore);
            $gjs_nav['components'][] = $processed_button['gjs_component'];

            // togglebox-item wrapper — saved to $datastore by process_component
            // (has blocks_layout so process_layout_inner_components won't trigger without content)
            $item = array('__type'=>'togglebox-item', 'blocks_layout' => array() );

            // Build item content based on content_element type
            $content_element = $acc_item['content_element'] ?? 'text';
            if( $content_element === 'layout' ){
                $item['blocks_layout'] = $acc_item['blocks_layout'] ?? array();
            }

            $processed_item = $this->process_component($item, $css_styles, $gjs_styles, $datastore);
            $gjs_item = $processed_item['gjs_component'];

            // For non-layout content, create a child component inside the togglebox-item
            if( $content_element === 'text' ){
                $item_content = array( '__type' => 'text_editor', 'content' => $acc_item['content'] ?? '' );
                $processed_content = $this->process_component($item_content, $css_styles, $gjs_styles, $datastore);
                $gjs_item['components'][] = $processed_content['gjs_component'];
            } elseif( $content_element === 'reusable_section' ){
                $item_content = array( '__type' => 'reusable_section', 'reusable_section' => $acc_item['reusable_section'] ?? '' );
                $processed_content = $this->process_component($item_content, $css_styles, $gjs_styles, $datastore);
                $gjs_item['components'][] = $processed_content['gjs_component'];
            }

            $gjs_items['components'][] = $gjs_item;
        }

        // Add id attributes for togglebox functionality (linking nav buttons to items)
        foreach( $gjs_items['components'] as $index => &$gjs_item_ref ){
            $item_id = $this->generate_id();

            // nav button: set 'box' to reference the item
            if( isset($gjs_nav['components'][$index]) ){
                $gjs_nav['components'][$index]['box'] = '#'.$item_id;
            }

            // item: set id and classes
            $gjs_item_ref['attributes']['id'] = $item_id;
            $gjs_item_ref['classes'] = array(
                array( 'name' => 'v23-togglebox__item', 'private' => 1 )
            );
            if( $index === 0 ){
                $gjs_item_ref['classes'][] = array( 'name' => 'active', 'private' => 1 );
            }
        }
        unset($gjs_item_ref); // break reference

        // Assemble GJS tree: togglebox-wrapper > togglebox > [nav, items]
        $gjs_accordion['components'] = array( $gjs_nav, $gjs_items );
        $gjs_component['components'] = array( $gjs_accordion );

        // Clean up old properties from UF component (togglebox-wrapper's datastore entry)
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

    private function process_flipbox_components( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, &$datastore ){
        // Create front face — UF data saved to $datastore by process_component
        $front = array(
            '__type' => 'flipbox-front', 
            'settings' => $component['front_settings'] ?? array(),
            'blocks_layout' => $component['front_content']['blocks_layout'] ?? array()
        );
        $processed_front = $this->process_component($front, $css_styles, $gjs_styles, $datastore);
        $gjs_front = $processed_front['gjs_component'];

        // Create back face — UF data saved to $datastore by process_component
        $back = array(
            '__type' => 'flipbox-back', 
            'settings' => $component['back_settings'] ?? array(),
            'blocks_layout' => $component['back_content']['blocks_layout'] ?? array()
        );
        $processed_back = $this->process_component($back, $css_styles, $gjs_styles, $datastore);
        $gjs_back = $processed_back['gjs_component'];

        // Add private classes
        $gjs_front['classes'][] = array( 'name' => 'flipbox-front', 'private' => 1 );
        $gjs_back['classes'][] = array( 'name' => 'flipbox-back', 'private' => 1 );

        // Migrate content alignment for each face
        $faces = array( 'front' => $gjs_front, 'back' => $gjs_back );
        foreach ( $faces as $key => &$gjs_face ) {
            $justify_content = $component[$key.'_justify_content'] ?? 'flex-start';
            $align_items = $component[$key.'_align_items'] ?? 'stretch';

            if( $justify_content !== 'flex-start' || $align_items !== 'stretch' ){
                // Ensure the face has an id for CSS targeting
                $face__id = $gjs_face['__id'];
                if( !isset($gjs_face['attributes']['id']) || empty($gjs_face['attributes']['id']) ){
                    $face_id = $this->generate_id();
                    $gjs_face['attributes']['id'] = $face_id;
                } else {
                    $face_id = $gjs_face['attributes']['id'];
                }

                $css_styles .= "#{$face_id} { justify-content: {$justify_content}; align-items: {$align_items}; }";
                $gjs_styles[] = array(
                    'selectors' => array( '#' . $face_id ),
                    'style' => array( 
                        'justify-content' => $justify_content,
                        'align-items' => $align_items
                    )
                );
            }

            unset( $uf_component[$key.'_justify_content'] );
            unset( $uf_component[$key.'_align_items'] );
        }
        unset($gjs_face); // break reference

        // GJS stores the full tree: flipbox > flipbox-inner > [front, back]
        $gjs_component['components'] = array( array(
            'type' => 'flipbox-inner',
            'components' => array( $faces['front'], $faces['back'] )
        ));

        // Clean up old properties
        unset( $uf_component['front_settings'] );
        unset( $uf_component['back_settings'] );
        unset( $uf_component['front_content'] );
        unset( $uf_component['back_content'] );
    }

    private function process_listing_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['source'] = $component['show'];
        $uf_component['listing_template'] = $component['list_template'];
        $uf_component['columns'] = array(
            'desktop' => $component['items_in_desktop'],
            'laptop' => $component['items_in_laptop'],
            'tablet' => $component['items_in_tablet'],
            'mobile' => $component['items_in_mobile']
        );
        $uf_component['columns_gap'] = array(
            'desktop' => $component['d_gap'] ?? 20,
            'laptop' => $component['l_gap'] ?? 20,
            'tablet' => $component['t_gap'] ?? 20,
            'mobile' => $component['m_gap'] ?? 20
        );

        // migrate carousel settings to a better format
        $uf_component['carousel_settings'] = array();
        if( isset($component['carousel_settings_wrapper']) && is_array($component['carousel_settings_wrapper']) ){
            $uf_component['carousel_settings'] = $component['carousel_settings_wrapper'];
        }

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

        // migrate query params
        $uf_component['query_params'] = array(
            'posts_per_page' => $component['qty'] ?? -1,
            'order' => $component['order'] ?? 'DESC',
            'orderby' => $component['orderby'] ?? 'date',
            'offset' => $component['offset'] ?? 0,
        );

        // migrate post status params
        $uf_component['status_params'] = array(
            'set_post_status' => isset($component['post_status']) ? true : false,
            'post_status' => $component['post_status'] ?? ['publish'],
        );

        // migrate postcard settings
        $uf_component['postcard_settings'] = array(
            'template' => $component['post_template'] ?? '_default',
            'on_click_post' => $component['on_click_post'] ?? 'redirect',
            'on_click_scroll_to' => $component['on_click_scroll_to'] ?? '',
        );

        // adjust listing template name
        if( $component['list_template'] == 'carrusel' ){
            $uf_component['listing_template'] = 'carousel';
        }

        // migrate pagination and scrolltop settings to a better format
        $pagination_type = $component['pagination_type'] ?? 'none';
        if($pagination_type == 'classic') $pagination_type = 'numeric';
        if($pagination_type == 'load_more') $pagination_type = 'load-more';
        $uf_component['pagination_type'] = $pagination_type;
        $uf_component['pagination_scrolltop'] = isset($component['scrolltop']) ? true : false;

        // migrate filter settings to a better format
        $uf_component['show_filter'] = isset($component['filter']) ? $component['filter'] : false;
        $uf_component['filters'] = array(
            'category' => array(
                'show' => isset($component['category-filter']['show']) ? $component['category-filter']['show'] : false,
                'initial_value' => $component['category-filter']['default_value'] ?? ''
            ),
            'portfolio-cat' => array(
                'show' => isset($component['portfolio-cat-filter']['show']) ? $component['portfolio-cat-filter']['show'] : false,
                'initial_value' => $component['portfolio-cat-filter']['default_value'] ?? ''
            ),
            'document-cat' => array(
                'show' => isset($component['document-cat-filter']['show']) ? $component['document-cat-filter']['show'] : false,
                'initial_value' => $component['document-cat-filter']['default_value'] ?? ''
            ),
            'month' => array(
                'show' => isset($component['month-filter']['show']) ? $component['month-filter']['show'] : false
            ),
            'year' => array(
                'show' => isset($component['year-filter']['show']) ? $component['year-filter']['show'] : false,
                'first_year' => $component['year-filter']['first_year'] ?? '',
                'initial_value' => $component['year-filter']['default'] ?? ''
            )
        );

        // unset old properties
        $to_unset = [ 'show','qty','items_in_desktop','items_in_laptop','items_in_tablet','items_in_mobile','list_template','taxonomies_field','d_gap', 'l_gap', 't_gap', 'm_gap', 'carousel_settings_wrapper', 'post_template', 'on_click_post', 'on_click_scroll_to', 'order', 'orderby', 'offset', 'post_status', 'posts_per_page', 'filter', 'category-filter', 'portfolio-cat-filter', 'document-cat-filter', 'month-filter', 'year-filter', 'scrolltop' ];
        foreach ( $to_unset as $key ) {
            unset( $uf_component[$key] );
        }
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
        }

        // add spacer class
        $gjs_component['classes'][] = 'spacer';

        unset( $uf_component['spacer_options_wrapper'] );
    }

    private function process_shortcode_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id ){
        $uf_component['desktop'] = $component['desktop'];
        $uf_component['set_mobile_shortcode'] = ( isset($component['mobile']) ) ? true : false;
        $uf_component['mobile'] = $component['mobile'];

        unset( $uf_component['_shortcodes_wrapper'] );
    }

    private function process_icon_and_text_component( $component, &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id, &$datastore ){
        // RENAME AND MAP OLD PROPERTIES
        $iposition = $component['iposition'] ?? 'left';
        $alignment = ( $iposition == 'top' ) ? ($component['itopalign'] ?? 'center') : ($component['ialign'] ?? 'left');
        $alignment_dictionary = array(
            'left' => 'flex-start',
            'center' => 'center',
            'right' => 'flex-end',
            'flex-start' => 'flex-start',
            'flex-end' => 'flex-end',
        );
        $uf_component['ialignment'] = $alignment_dictionary[$alignment] ?? 'flex-start';
        $uf_component['isource'] = ( ($component['ielement'] ?? 'icon') == 'imagen' ) ? 'image' : 'icon';
        if( isset($component['horizontal_alignment']) && $component['horizontal_alignment'] ){
            $uf_component['content_alignment'] = 'center';
        }

        // Save icon style values before cleanup
        $istyle = $component['istyle'] ?? 'default';

        // Generate icon HTML id (separate from parent's $id to avoid CSS collision)
        $icon_id = $this->generate_id();

        // Create icon child — saved to $datastore by process_component
        $icon_data = array( '__type' => 'icon' );
        $processed_icon = $this->process_component( $icon_data, $css_styles, $gjs_styles, $datastore );
        $gjs_icon = $processed_icon['gjs_component'];
        $gjs_icon['attributes']['id'] = $icon_id;

        // Create icon-wrapper — saved to $datastore by process_component
        $icon_wrapper_data = array( '__type' => 'icon-wrapper' );
        $processed_icon_wrapper = $this->process_component( $icon_wrapper_data, $css_styles, $gjs_styles, $datastore );
        $gjs_icon_wrapper = $processed_icon_wrapper['gjs_component'];
        $gjs_icon_wrapper['components'] = array( $gjs_icon );

        // Create text-editor child — saved to $datastore by process_component
        $text_data = array( '__type' => 'text_editor', 'content' => $component['content'] ?? '' );
        $processed_text = $this->process_component( $text_data, $css_styles, $gjs_styles, $datastore );
        $gjs_text = $processed_text['gjs_component'];

        // Create components-wrapper — saved to $datastore by process_component
        $wrapper_data = array( '__type' => 'components-wrapper' );
        $processed_wrapper = $this->process_component( $wrapper_data, $css_styles, $gjs_styles, $datastore );
        $gjs_wrapper = $processed_wrapper['gjs_component'];
        $gjs_wrapper['components'] = array( $gjs_text );

        // GJS stores the full tree: icon-and-text > [icon-wrapper > [icon], components-wrapper > [text-editor]]
        $gjs_component['components'] = array( $gjs_icon_wrapper, $gjs_wrapper );

        // ADD ICON STYLES
        $css_styles .= "#{$icon_id} { ";
        $gjs_style = array(
            'selectors' => array( '#' . $icon_id ),
            'style' => array()
        );

        $ifontsize = $component['ifontsize'] ?? '';
        if( $ifontsize != '' && $ifontsize != 40 ){
            $css_styles .= "--icon-size: {$ifontsize}px; ";
            $gjs_style['style']['--icon-size'] = $ifontsize . 'px';
        }
        $icolor = $component['icolor'] ?? '';
        if( $icolor != '' ){
            $css_styles .= "color: {$icolor}; ";
            $gjs_style['style']['color'] = $icolor;
        }

        if( $istyle != 'default' ){
            $bgc = ( isset( $component['ibgc'] ) && $component['ibgc'] != '' ) ? $component['ibgc'] : 'var(--primary-color)';
            $css_styles .= "background-color: {$bgc}; ";
            $gjs_style['style']['background-color'] = $bgc;

            $css_styles .= "padding: 20px; ";
            $gjs_style['style']['padding'] = '20px';

            $borderRadius = ( $istyle == 'square-outline' ) ? '8px' : '50%';
            $css_styles .= "border-radius: {$borderRadius}; ";
            $gjs_style['style']['border-radius'] = $borderRadius;
        }

        $use_border = ['circle-outline', 'square-outline'];
        if( in_array( $istyle, $use_border ) ){
            $css_styles .= "border-width: 2px; border-style: solid; ";
            $gjs_style['style']['border-width'] = '2px';
            $gjs_style['style']['border-style'] = 'solid';
        }

        $css_styles .= "}";
        $gjs_styles[] = $gjs_style;

        // UNSET OLD PROPERTIES
        unset( $uf_component['_icon_styles_wrapper'] );
        unset( $uf_component['hide-icon-on-mobile'] );
        unset( $uf_component['itopalign'] );
        unset( $uf_component['ialign'] );
        unset( $uf_component['ielement'] );
        unset( $uf_component['content'] );
        // unset( $uf_component['iposition'] );
        // unset( $uf_component['horizontal_alignment'] );
        
        unset( $uf_component['istyle'] );
        unset( $uf_component['ifontsize'] );
        unset( $uf_component['icolor'] );
        unset( $uf_component['ihas_bgc'] );
        unset( $uf_component['ibgc'] );
    }

    private function handle_settings( &$uf_component, &$gjs_component, &$css_styles, &$gjs_styles, $id, &$datastore ){
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
            if( in_array( $layout, $special_layouts )  ){
                $dont_doit_for = array( 'section', 'components-wrapper', 'column', 'inner_wrapper' ); // inner_wrapper?
                $component_type = $uf_component['__type'];
                if( !in_array( $component_type, $dont_doit_for ) ){
                    // just remove layout setting for these components, no need to wrap
                    unset( $uf_component['settings']['layout'] );

        //             // Get the child's __id before modifying
        //             $child__id = $gjs_component['__id'];
                    
        //             // Save the child component to datastore BEFORE wrapping
        //             $child_uf = $uf_component;
                    // unset( $child_uf['settings']['layout'] ); // remove layout from child
        //             unset( $child_uf['components'] ); // datastore doesn't store structure
        //             $datastore[$child__id] = $child_uf;

        //             // Generate new __id for the wrapper
        //             $wrapper__id = 'cmp_' . substr(md5(uniqid()), 0, 8);

        //             // Create GJS wrapper
        //             unset( $gjs_component['attributes'] );
        //             $gjs_comp_wrapper = array(
        //                 'type' => 'components-wrapper',
        //                 'components' => array( $gjs_component ),
        //                 '__id' => $wrapper__id,
        //                 'attributes' => array( 'id' => $id )
        //             );
        //             $gjs_component = $gjs_comp_wrapper;
                    
        //             // Create UF wrapper (will be saved to datastore by process_component)
        //             $uf_comp_wrapper = array(
        //                 '__type' => 'components-wrapper',
        //                 '__id' => $wrapper__id
        //             );
        //             $uf_comp_wrapper['settings']['layout'] = array( 'use' => 1, 'key' => $layout );
        //             $uf_component = $uf_comp_wrapper;
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
                $uf_component['settings']['video_background']['use'] = true; // ensure 'use' is true if video background settings exist
                unset( $uf_component['settings']['video_background']['video_settings']['background_color'] );
        }
    }

    private function handle_actions( &$uf_component ){
        // migrate old repeater actions to single action
        if( 
            isset( $uf_component['actions_settings'] ) 
            && is_array( $uf_component['actions_settings'] )
            && isset( $uf_component['actions_settings']['actions'] )
            && is_array( $uf_component['actions_settings']['actions'] )
            && count( $uf_component['actions_settings']['actions'] ) > 0
            ){
            $first_action = $uf_component['actions_settings']['actions'][0];

            if( isset( $first_action['enlace'] ) ){
                $first_action['link'] = $first_action['enlace'];
                $url_type_dictionary = array(
                    'interna' => 'internal',
                    'externa' => 'external',
                );
                $first_action['url_type'] = $url_type_dictionary[$first_action['enlace']['url_type']] ?? 'internal';
                unset( $first_action['enlace'] );
            }

            $uf_component['actions_settings'] = $first_action;

            unset( $uf_component['actions_settings']['actions'] );
        }
    }

    private function migrate_colors_settings() {
        $new_colors = array();

        // Mapping of old option names to CSS variables
        $color_mapping = array(
            'primary_color' => '--primary-color',
            'secondary_color' => '--secondary-color',
            'font_color' => '--font-color',
            'headings_color' => '--headings-color',
            'link_color' => '--link-color'
        );

        // Migrate main colors
        foreach ($color_mapping as $option_name => $css_var) {
            $color_value = get_option($option_name, '');
            
            // Only add if value exists and is not empty
            if (!empty($color_value)) {

                $customize_variations = false;
                $variations = array();
                $variations_keys = ['dark', 'light', 'lighter'];
                $variations_defaults = [15, 70, 94]; // default values for dark, light, lighter
                $add_variations_for = ['primary_color', 'secondary_color']; // only for primary and secondary colors
                if(  in_array($option_name, $add_variations_for) ){
                    $customize_variations = true;
                    $index = 0;
                    foreach ($variations_keys as $key) {
                        $variation = get_option($key.'_'.$option_name.'_percentage', $variations_defaults[$index]);
                        $variations[$key] = $variation;
                        $index++;
                    }
                }

                $new_colors[] = array(
                    '__type' => 'color',
                    'color' => $color_value,
                    'css_property' => $css_var,
                    'customize_variations' => $customize_variations,
                    'light' => $variations['light'] ?? null,
                    'lighter' => $variations['lighter'] ?? null,
                    'dark' => $variations['dark'] ?? null
                );
            }
        }

        // Migrate colorpicker palette
        $colorpicker_palette = get_option('colorpicker_palette', array());
        if (is_array($colorpicker_palette) && !empty($colorpicker_palette)) {
            foreach ($colorpicker_palette as $palette_item) {
                if (isset($palette_item['color']) && !empty($palette_item['color'])) {
                    $new_colors[] = array(
                        '__type' => 'color',
                        'color' => $palette_item['color'],
                        'css_property' => '', // No CSS variable assigned
                        'customize_variations' => false,
                        'light' => '',
                        'lighter' => '',
                        'dark' => ''
                    );
                }
            }
        }

        // Save new structure
        if ($this->do_the_update && !empty($new_colors)) {
            update_option('theme_colors', $new_colors);
        }

        // Delete old options
        if ($this->delete_old_data) {
            $old_options = array(
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
        }
    }

    private function migrate_header_settings() {
        $post_data = array(
            'post_title'    => 'Page Header',
            'post_status'   => 'publish',
            'post_type'     => 'header',
        );
        $post_id = wp_insert_post($post_data);
        
        if (!is_wp_error($post_id)) {

            $old_header_settings = array();
            $keys = array('static', 'sticky');
            foreach ($keys as $key) {
                // header logo
                $header_logo_id = null;
                $header_logo_key = get_option($key.'_header_logo');
                if( $header_logo_key == 'custom' ){
                    $header_logo_id = get_option('custom_'.$key.'_header_logo');
                }else{
                    $header_logo_id = get_option($header_logo_key);
                }
                $old_header_settings[$key.'_header_logo'] = $header_logo_id;

                // header max height
                $header_max_height = get_option($key.'_header_max_height', 60);
                $old_header_settings[$key.'_header_max_height'] = $header_max_height;

                // header background color
                $header_bgc_default = get_option( $key.'_header_bgc', array(
                    'add_bgc' => false,
                    'bgc' => '',
                    'alpha' => 100
                ));
                $header_bgc = get_option($key.'_header_bgc', $header_bgc_default);
                if( is_array($header_bgc) && $header_bgc['add_bgc'] ){
                    $color = $header_bgc['bgc'];
                    $alpha = $header_bgc['alpha'];
                    $old_header_settings[$key.'_header_bgc'] = 'rgba('.Helpers::hexToRgb( $color, $alpha ).')';
                } else {
                    $old_header_settings[$key.'_header_bgc'] = '';
                }

                // color scheme
                $header_color_scheme = get_option($key.'_header_color_scheme', '');
                $old_header_settings[$key.'_header_color_scheme'] = ( $header_color_scheme === 'text-color-2' ) ? '#ffffff' : 'inherit';
            }

            $adjust_scroll_position = get_option('adjust_scroll_position', false);

            // Generate content structure with migrated settings
            $content_structure = [
                "type" => "wrapper",
                "components" => [
                    [
                        "type" => "header",
                        "adjust_scroll_position" => $adjust_scroll_position,
                        "styles" => [
                            [
                                "style" => [
                                    "background-color" => $old_header_settings['static_header_bgc'],
                                    "color" => $old_header_settings['static_header_color_scheme'],
                                ],
                            ],
                            [
                                "style" => [
                                    "background-color" => $old_header_settings['sticky_header_bgc'],
                                    "color" => $old_header_settings['sticky_header_color_scheme'],
                                    "box-shadow" => "0px 0px 5px 0px #939191"
                                ],
                                "selectorsAdd" => "#%comp_id%.header--sticky"
                            ]
                        ],
                        "components" => [
                            [ 
                                "type" => "header-content",
                                "components" => [
                                    [ 
                                        "type" => "components-wrapper",
                                        "styles" => [
                                            [
                                                "style" => [
                                                    "display" => "flex",
                                                    "flex-direction" => "row",
                                                    "flex-wrap" => "wrap",
                                                    "justify-content" => "space-between",
                                                    "align-items" => "center",
                                                    "padding" => "15px 0 15px 0",
                                                    "gap" => "15px",
                                                ],
                                            ],
                                        ],
                                        "components" => [
                                            [ 
                                                "type" => "header-logo",
                                                "static_header_logo" => $old_header_settings['static_header_logo'],
                                                "sticky_header_logo" => $old_header_settings['sticky_header_logo'],
                                                "styles" => [
                                                    [
                                                        "style" => [
                                                            "height" => $old_header_settings['static_header_max_height'].'px',
                                                        ],
                                                        [
                                                            "style" => [
                                                                "height" => $old_header_settings['sticky_header_max_height'].'px',
                                                            ],
                                                            "selectorsAdd" => ".header-logo--sticky #%comp_id%"
                                                        ]
                                                    ],
                                                ]
                                            ],
                                            [
                                                "type" => "components-wrapper",
                                                "styles" => [
                                                    [
                                                        "style" => [
                                                            "display" => "flex",
                                                            "flex-direction" => "row",
                                                            "flex-wrap" => "wrap",
                                                            "justify-content" => "space-between",
                                                            "align-items" => "center",
                                                            "gap" => "15px",
                                                        ],
                                                    ],
                                                ],
                                                "components" => [
                                                    [ 
                                                        "type" => "menu",
                                                        "menu_type" => "location",
                                                        "menu" => null,
                                                        "location" => "main-nav",
                                                        "style" => "horizontal-nav-1",
                                                        "styles" => [
                                                            [
                                                                "style" => [
                                                                    "display" => "none",
                                                                ],
                                                                "mediaText" => "(max-width: 992px)",
                                                                "atRuleType" => "media",
                                                            ]
                                                        ]
                                                    ],
                                                    [ 
                                                        "type" => "menu",
                                                        "menu_type" => "location",
                                                        "menu" => null,
                                                        "location" => "mobile-header-buttons",
                                                        "style" => "horizontal-nav-1",
                                                        "styles" => [
                                                            [
                                                                "style" => [
                                                                    "display" => "none",
                                                                ],
                                                            ],
                                                            [
                                                                "style" => [
                                                                    "display" => "block",
                                                                ],
                                                                "mediaText" => "(max-width: 992px)",
                                                                "atRuleType" => "media",
                                                            ]
                                                        ]
                                                    ],
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ],
                    [ "type" => "container" ]
                ],
            ];

            $templates = Templates_Generator::generate_templates( $content_structure );

            // Prepare meta values
            $meta_values = [
                'page_content_styles' => $templates['styles'],
                'page_content' => $templates['gjs_template'],
                'page_content_datastore' => $templates['datastore'],
            ];

            // Update post meta with new values
            foreach ($meta_values as $meta_key => $meta_value) {
                if( $this->do_the_update ) update_post_meta($post_id, $meta_key, $meta_value);
            }
                
            if( $this->do_the_update ) update_option('theme_header_post', 'post_'.$post_id);

            // Delete old options
            if ($this->delete_old_data) {
                $old_options = array(
                    'static_header_logo',
                    'custom_static_header_logo',
                    'static_header_max_height',
                    'static_header_bgc',
                    'static_header_color_scheme',
                    'sticky_header_logo',
                    'custom_sticky_header_logo',
                    'sticky_header_max_height',
                    'sticky_header_bgc',
                    'sticky_header_color_scheme',
                    'adjust_scroll_position'
                );

                foreach ($old_options as $option) {
                    delete_option($option);
                }
            }
        }
    }

    public function migrate_archive_page_post_meta( $post_id ){
        // Migrate loop_columns to columns
        $loop_columns = get_post_meta( $post_id, 'loop_columns', true );
        if( $loop_columns && is_array( $loop_columns ) ){
            $new_columns = array(
                'desktop' => $loop_columns['desktop'] ?? 3,
                'laptop' => $loop_columns['laptop'] ?? 3,
                'tablet' => $loop_columns['tablet'] ?? 2,
                'mobile' => $loop_columns['mobile'] ?? 1
            );
            if( $this->do_the_update ) update_post_meta( $post_id, 'columns', $new_columns );
            if( $this->delete_old_data ) delete_post_meta( $post_id, 'loop_columns' );
        }

        // Migrate loop_columns_gap to columns_gap
        $loop_columns_gap = get_post_meta( $post_id, 'loop_columns_gap', true );
        if( $loop_columns_gap && is_array( $loop_columns_gap ) ){
            $new_columns_gap = array(
                'desktop' => $loop_columns_gap['desktop'] ?? 20,
                'laptop' => $loop_columns_gap['laptop'] ?? 20,
                'tablet' => $loop_columns_gap['tablet'] ?? 15,
                'mobile' => $loop_columns_gap['mobile'] ?? 10
            );
            if( $this->do_the_update ) update_post_meta( $post_id, 'columns_gap', $new_columns_gap );
            if( $this->delete_old_data ) delete_post_meta( $post_id, 'loop_columns_gap' );
        }

        // if page_template is "hide-sidebar", change it to "main-content--sidebarless"
        $page_template = get_post_meta( $post_id, 'page_template', true );
        if( $page_template && $page_template === 'hide-sidebar' ){
            if( $this->do_the_update ) update_post_meta( $post_id, 'page_template', 'main-content--sidebarless' );
        }
    }

    public function ajax_after_data_migration() {
        
        // Migrate single_pages_settings
        $single_pages_settings = get_option( 'single_pages_settings', array() );
        if( is_array( $single_pages_settings ) && count( $single_pages_settings ) > 0 ){
            foreach ( $single_pages_settings as $old_settings ) {
                $post_types = $old_settings['post_types'] ?? array();
                foreach ( $post_types as $post_type ) {
                    $new_settings = array(
                        'page_template' => $old_settings['hide_sidebar'] ? 'main-content--sidebarless' : $old_settings['page_template'],
                        'hide_post_title' => $old_settings['hide_post_title'] ?? 0,
                        'hide_social_share' => $old_settings['hide_social_share'] ?? 0,
                        'hide_related_posts' => $old_settings['hide_related_posts'] ?? 0,
                        'hide_comments_area' => 0,
                    );

                    if( $this->do_the_update ) update_option( 'single_' . $post_type . '_settings', $new_settings );
                }
            }
        }
        if( $this->delete_old_data ){
            delete_option( 'single_pages_settings' );
        }

        // Migrate colors settings
        $this->migrate_colors_settings();

        // Migrate Header settings
        $this->migrate_header_settings();

        wp_send_json_success(array(
            'complete' => true
        ));
    }
}