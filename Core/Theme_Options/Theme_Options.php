<?php
namespace Core\Theme_Options;

use Core\Includes\Theme_Header_Data;
use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Field\Font;
use Core\Utils\Helpers;
use Core\Theme_Options\UF_Container\Main;
use Core\Theme_Options\UF_Container\Social_Media;
use Core\Theme_Options\UF_Container\Global_Options;
use Core\Theme_Options\UF_Container\Custom_Scripts;

class Theme_Options extends Theme_Header_Data{
	private static $instance = null;

    private $slug = 'theme-options-menu';

    /**
     * Hold the list of available logo versions to be used as select options
     */
    private static $logos_field_names = array();

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Theme_Options();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){
        parent::__construct();
        $this->set_logos_field_names();
    }

    public function init_options_page(){
        if( current_user_can('administrator') ){
            Options_Page::create( $this->slug, 'Theme Options' )->set_position( 2 )->set_capability( 'manage_options' );
            Options_Page::create( 'theme-options', 'Theme Options' )->set_parent( $this->slug );
            Options_Page::create( 'custom-scripts-options', 'Custom Scripts' )->set_parent( $this->slug );

            // load uf-containers
            Main::init();
            Custom_Scripts::init();
            Social_Media::init();
            Global_Options::init();
        }
    }

    public function rearrange_submenu_order( $menu_ord ){
        if( !current_user_can('administrator') ) return $menu_ord;

        global $submenu;
        // Enable the next line to see the menu order
        // echo '<pre>'.print_r($submenu[$this->slug],true).'</pre>';
    
        $order_list = array( 
            'theme-options',
            'edit.php?post_type=footer', 
            'edit.php?post_type=offcanvas_element',
            'custom-scripts-options', 
            'edit.php?post_type=megamenu', 
            'edit.php?post_type=reusable_section' 
        );
    
        $new_order = array();
        $not_in_list = array();
        for ($i=0; $i < count($order_list); $i++) { 
            $new_order[ $i ] = null;
        }
        for ($i=0; $i < count( $submenu[$this->slug] ); $i++) { 
            $key = array_search( $submenu[$this->slug][$i][2], $order_list );
            if( $key > -1 ){
                $new_order[ $key ] = $submenu[$this->slug][$i]; 
            } else {
                $not_in_list[] = $submenu[$this->slug][$i];
            }
        }
        $new_order = array_merge( $new_order, $not_in_list );
        $submenu[$this->slug] = $new_order;

        return $menu_ord;
    }

    public function show_cpt_count() {
        global $wp_post_types;
    
        $custom_posts = array(
            array( 'slug'=>'megamenu', 'name'=>'Megamenú' ),
            array( 'slug'=>'reusable_section', 'name'=>__('Reusable Sections') )
        );
    
        foreach($custom_posts as $cpt){
            $slug = $cpt['slug'];
            $name = $cpt['name'];
    
            $notification_count = wp_count_posts( $slug )->publish;
            $labels = $wp_post_types[$slug]->labels;
            $labels->all_items = $notification_count ? sprintf('%s <span class="awaiting-mod">%d</span>', $name, $notification_count) : $name;   
        }
    }

    private function set_logos_field_names(){
        for ($i=1; $i <= LOGOS_QUANTITY; $i++) { 
            switch ($i) {
                case 1:
                    $field_name = 'main_logo';
                    break;
        
                case 2:
                    $field_name = 'secondary_logo';
                    break;
                
                default:
                    $field_name = 'logo_v'.$i;
                    break;
            }
            $field_title = 'Logo Versión '.$i;
            self::$logos_field_names[$field_name] = $field_title;
        }
        self::$logos_field_names['custom'] = 'Custom';
    }

    public static function get_logos_field_names(){
        return self::$logos_field_names;
    }

    public function get_theme_fonts(){
        $urls = array();
        $names = array();
        $css = '';

        $fonts = get_option('fonts');
        $apply_to = array(
            'global' => 'body',
            'headings' => 'h1,h2,h3,h4,h5,h6'
        );

        if( is_array($fonts) && !empty($fonts) ){
            foreach ($fonts as $item) {
                if( $item['scope'] != 'any' ) $selector = ( $item['scope'] == 'custom' ) ? $item['selector'] : $apply_to[ $item['scope'] ];

                if( $item['__type'] == 'google_font' ){
                    $font_data = $item['google_font'];
                    if( $font_data ) {
                        $url = Font::get_font_url( $font_data );
                        $urls[] = $url;
                        $names[] = $font_data['family'];
                    
                        // font rule
                        if( $item['scope'] != 'any' ) $css .= $selector.' {font-family: ' . $font_data['family'] . ', Sans-Serif;}';
                    }
                }   
                if( $item['__type'] == 'custom_font' ){
                    $files = $item['files'];
                    $name = $item['name'];
                    $variant = $item['variant'];
                    $type = (isset($item['type'])) ? $item['type'] : 'file';
                    $names[] = $name;
                    // font urls
                    $custom_font_urls = array();
                    if($type == 'file' && is_array($files) && !empty($files)){
                        foreach ($files as $file) {
                            $custom_font_urls[] = 'url('.wp_get_attachment_url($file).')';
                        }
                    }
                    if($type == 'url' && isset($item['urls']) && is_array($item['urls']) && !empty($item['urls'])){
                        foreach ($item['urls'] as $group_item) {
                            if($group_item['url']) $custom_font_urls[] = 'url('.$group_item['url'].')';
                        }
                    }
                    if( !empty($custom_font_urls) ){
                        // font face
                        $css .= '@font-face {';
                        $css .= 'font-family: '.$name.';';
                        $css .= 'font-weight: '.$variant.';';
                        $css .= 'src:'.implode(', ',$custom_font_urls).';';
                        $css .= '} ';
                        // $css .= '}\n '; // didnt worked in marine farm project

                        // font rule
                        if( $item['scope'] != 'any' ) $css .= $selector.' {font-family: ' . $name . ', Sans-Serif;}';
                    }
                    
                }
            }
        }

        return array(
            'names' => $names,
            'urls' => $urls,
            'css' => $css
        );
    }

    public function add_theme_fonts(){
        $fonts = self::$instance->get_theme_fonts();
        $count = 0;
        
        foreach ($fonts['urls'] as $url) {
            wp_enqueue_style( $fonts['names'][$count], $url );
            $count++;
        }
            
        wp_add_inline_style( 'mv23theme-styles', $fonts['css'] );
    }

    public function get_property($name){
        return get_option($name);
    }

    public function get_css_properties(){
        $properties = array();

        // main colors
        $colors = array('primary_color','secondary_color','font_color','headings_color');
        foreach ($colors as $color) {
            $the_color = get_option( $color );
            if( $the_color ) {
                $properties[] = '--'.str_replace('_','-',$color).':'.$the_color;
            }
        }

        // variations
        $variations = array('light','lighter','dark');
        foreach ($variations as $variation) {
            $percentage = get_option( $variation.'_primary_color_percentage' );
            if( $percentage ) $properties[] = '--primary-color-'.$variation.':color-mix( in srgb, var(--primary-color), white '.$percentage.'% )';

            $percentage = get_option( $variation.'_secondary_color_percentage' );
            if( $percentage ) $properties[] = '--secondary-color-'.$variation.':color-mix( in srgb, var(--secondary-color), white '.$percentage.'% )';
        }

        // typography
        $typography_options = array('paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        $props = array('font_size','line_height','font_weight');
        
        foreach ($typography_options as $option) {

            $is_heading = (strlen($option) == 2 );
            $option_name = ( $is_heading ) ? $option.'_heading' : $option;
            $the_option = get_option( $option_name );

            if( $the_option ) {
                foreach ($props as $prop) {
                    if ( $the_option[$prop] ){
                        $css_property_name = ( !$is_heading ) ? '--'.$prop : '--'.$option.'-'.$prop;
                        $properties[] = str_replace('_','-',$css_property_name).':'.$the_option[$prop];
                    } 
                }
            }
        }

        // header
        $header_options = array('static_header_bgc','sticky_header_bgc','static_header_logo_height','sticky_header_logo_height');
        $header_properties = array('--static-header-color','--sticky-header-color','--static-header-logo-height','--sticky-header-logo-height');
        $count = 0;
        foreach ($header_options as $option) {
            $the_value = get_option( $option );
            if( $count < 2 && is_array($the_value) && $the_value['add_bgc'] ) {
                $color = Helpers::hexToRgb( $the_value['bgc'], $the_value['alpha'] );
                $properties[] = $header_properties[$count].': rgba('.$color.')';
            }
            if( $count > 1 ){
                if($the_value) $properties[] = $header_properties[$count].': '.$the_value.'px';
            }
            $count++;
        }

        // containers
        $containers_width = get_option( 'containers_width' );
        if( !empty($containers_width) ){
            foreach ($containers_width as $item) {
                $width = $item['width'];
                if( $width ){
                    if( $item['scope'] === 'global' ){
                        $properties[] = '--container-width:'.$width.'px';
                    } elseif ( $item['scope'] === 'custom' && !empty($item['selector']) ){
                        $properties[] = $item['selector'].'{--container-width:'.$width.'px}';
                    } else {
                        $properties[] = '.'.$item['scope'].'{--container-width:'.$width.'px}';
                    }
                }
            }
        }

        return $properties;
    }
    
    public function add_css_properties(){
        $properties = self::$instance->get_css_properties();
        $root_lines = array();
        $css = '';

        if( !empty($properties) ){
            foreach ($properties as $prop) {
                if( str_starts_with($prop,'--') ){ 
                    // is a css property
                    $root_lines[] = $prop;
                } else { 
                    // is a css rule
                    $css .= $prop;
                }
            }
        }
        
        if( !empty($root_lines) ) $css .= ':root {'.implode(';', $root_lines ).'}';
        if( !empty($css) ) wp_add_inline_style( 'mv23theme-styles', $css );
    }

    public function get_page_template_settings( $type = '' ){
        $page_settings = array(
            'single' => array( 'page_template' => 'main-content--sidebar-right', 'hide_sidebar'=>0 )
        );

        $page_template_settings = get_option('single_pages_settings');
        foreach ($page_template_settings as $setting) {
            if($setting['__type'] == $type){
                if( $type == 'single' ){
                    $queried_object = get_queried_object();
                    $posttype = $queried_object->post_type;
                    if( in_array($posttype, $setting['post_types']) ){
                        $page_settings['single']['hide_sidebar'] = $setting['hide_sidebar'];
                        $page_settings['single']['page_template'] = $setting['page_template'];
                    }
                }
            }
        }

        return (isset($page_settings[$type])) ? $page_settings[$type] : array();
    }

    public function enqueue_admin_scripts(){
        $theme_options = self::$instance;
		$theme_colors = array('#000000','#ffffff');
		
        $options = array('primary_color','secondary_color','font_color','headings_color','colorpicker_palette');
        foreach ($options as $option_name) {
            if( $option_name != 'colorpicker_palette' ){
                $the_color = $theme_options->get_property($option_name);
                if( $the_color ) $theme_colors[] = $the_color;
            } else {
                $colorpicker_palette = $theme_options->get_property('colorpicker_palette');
                if( is_array($colorpicker_palette) && !empty($colorpicker_palette) ){
                    foreach ($colorpicker_palette as $item) {
                        if($item['color']) $theme_colors[] = $item['color'];
                    }
                }
            }
        }

        wp_add_inline_script( 
            'uf-field-color', 
            'const COLOR_PICKER = ' . json_encode(array(
                'palettes' => $theme_colors
            )),
            'before'
        );
    }

    public function enqueue_uf_customize_preview_script(){
        $uri = $this->theme_path . '/assets/js/customizer.js';
	    wp_enqueue_script( 'theme-custom-fields', $uri, array( 'jquery', 'uf-customize-preview' ), '1.0', true );
    }
}