<?php
namespace Theme_Custom_Fields;

use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Field\Font;
use Core\Utils\Helpers;

class Theme_options{
	private static $instance = null;

    private $slug = 'theme-options-menu';

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Theme_options();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){

        if( current_user_can('administrator') ){
            Options_Page::create( $this->slug, 'Theme Options' )->set_position( 2 )->set_capability( 'manage_options' );
            Options_Page::create( 'theme-options', 'Theme Options' )->set_parent( $this->slug );
            Options_Page::create( 'custom-scripts-options', 'Custom Scripts' )->set_parent( $this->slug );
    
            $this->add_theme_options_meta_boxes();
            add_filter( 'custom_menu_order', array( $this, 'rearrange_submenu_order' ));
        }
        
        // load frontend stuff
        add_action( 'wp_enqueue_scripts', array( $this, 'add_theme_fonts' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'add_css_properties' ));
    }

    public function add_theme_options_meta_boxes(){
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/custom-scripts-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/theme-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/rrss-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/global-options.php' );

        /**
         * Let child theme register its own meta boxes
         */
	    do_action('add_theme_options_meta_boxes');
    }

    public function rearrange_submenu_order( $menu_ord ){
        global $submenu;
        // Enable the next line to see the menu order
        // echo '<pre>'.print_r($submenu[$this->slug],true).'</pre>';
    
        $order_list = array( 
            'theme-options',
            'edit.php?post_type=footer', 
            'edit.php?post_type=offcanvas_element',
            'custom-scripts-options', 
            'edit.php?post_type=megamenu', 
            'edit.php?post_type=v23accordion', 
            'edit.php?post_type=archive_page',
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

    public function add_theme_fonts(){
        $fonts = array(
            array( 'option' => 'general_font', 'apply_to' => 'body'),
            array( 'option' => 'headings_font', 'apply_to' => 'h1,h2,h3,h4,h5,h6')
        );

        foreach ($fonts as $font) {
            $the_font = get_option( $font['option'] );
    
            if( $the_font ) {
                $url = Font::get_font_url( $the_font );
                wp_enqueue_style( $the_font['family'], $url );
            
                $css = $font['apply_to'].' {font-family: "' . $the_font['family'] . '", Sans-Serif;}';
                wp_add_inline_style( 'mv23theme-styles', $css );
            }
        }
    }

    public function get_property($name){
        return get_option($name);
    }

    public function add_css_properties(){
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
            if( $count < 2 && $the_value['add_bgc'] ) {
                $color = Helpers::hexToRgb( $the_value['bgc'], $the_value['alpha'] );
                $properties[] = $header_properties[$count].': rgba('.$color.')';
            }
            if( $count > 1 ){
                $properties[] = $header_properties[$count].': '.$the_value.'px';
            }
            $count++;
        }

        // ADD THE CSS PROPERTIES
        if( !empty($properties) ){
            $css = ':root {'.implode(';', $properties ).'}';
            wp_add_inline_style( 'mv23theme-styles', $css );
        }
    }
}