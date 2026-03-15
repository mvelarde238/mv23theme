<?php
namespace Core\Theme_Options;

use Core\Includes\Theme_Header_Data;
use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Field\Font;
use Core\Utils\Helpers;
use Core\Theme_Options\UF_Container\Main;
use Core\Theme_Options\UF_Container\Custom_Scripts;
use Core\Theme_Options\UF_Container\Posts_Subscription;
use Core\Theme_Options\UF_Container\Track_Posts_Data;
use Core\Theme_Options\UF_Container\Builder_Options;

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
        $this->hide_repeater_groups();
    }

    public function init_options_page(){
        if( current_user_can('administrator') ){
            Options_Page::create( $this->slug, __( 'Theme Options', 'mv23theme' ) )->set_position( 2 )->set_capability( 'manage_options' );
            Options_Page::create( 'theme-options', __( 'Theme Options', 'mv23theme' ) )->set_parent( $this->slug );
            Options_Page::create( 'custom-scripts-options', 'Custom Scripts' )->set_parent( $this->slug );

            // load uf-containers
            Main::init();
            Custom_Scripts::init();
            Builder_Options::init();
            Posts_Subscription::init();
            Track_Posts_Data::getInstance()->init();
        }
    }

    public function rearrange_submenu_order( $menu_ord ){
        if( !current_user_can('administrator') ) return $menu_ord;

        global $submenu;
        // Enable the next line to see the menu order
        // echo '<pre>'.print_r($submenu[$this->slug],true).'</pre>';
    
        $order_list = array( 
            'theme-options',
            'edit.php?post_type=header', 
            'edit.php?post_type=footer', 
            'edit.php?post_type=archive_template',
            'edit.php?post_type=single_template',
            'edit.php?post_type=offcanvas_element',
            'edit.php?post_type=megamenu', 
            'edit.php?post_type=reusable_section',
            'custom-scripts-options', 
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
            array( 'slug'=>'reusable_section', 'name'=>__('Reusable Sections','mv23theme') )
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
            /* translators: %s: number */
            self::$logos_field_names[$field_name] = sprintf('Version %s', $i);
        }
        self::$logos_field_names['custom'] = 'Custom';
    }

    public static function get_logos_field_names(){
        return self::$logos_field_names;
    }

    public static function hide_repeater_groups(){
        add_filter( 'uf.repeater.group_hidden', function($hidden, $group, $this_obj){
            $groups = ['color','social-network'];

            if( in_array($group->get_id(), $groups) ){
                return true;
            }
            return $hidden;
        }, 10, 3 );
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
                    if( !isset($item['files']) || !is_array($item['files']) || empty($item['files']) ) continue;

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
            
        wp_add_inline_style( 'mv23theme-styles', $fonts['css'] );
    }

    public function get_property($name){
        return get_option($name);
    }

    public function get_css_properties(){
        $properties = array();

        // theme colors
        $theme_colors = get_option('theme_colors', array());
        
        if (is_array($theme_colors) && !empty($theme_colors)) {
            foreach ($theme_colors as $color_item) {
                // Process color type items
                if (isset($color_item['__type']) && $color_item['__type'] === 'color') {
                    if (!empty($color_item['color']) && !empty($color_item['css_property'])) {
                        $properties[] = $color_item['css_property'] . ':' . $color_item['color'];

                        // Generate variations if enabled
                        if (!empty($color_item['customize_variations'])) {
                            $base_var = $color_item['css_property'];
                            
                            // Generate light variation
                            $light_value = !empty($color_item['light']) ? $color_item['light'] : 70;
                            $properties[] = $base_var . '-light:color-mix( in srgb, var(' . $base_var . '), white ' . $light_value . '% )';
                            
                            // Generate lighter variation
                            $lighter_value = !empty($color_item['lighter']) ? $color_item['lighter'] : 94;
                            $properties[] = $base_var . '-lighter:color-mix( in srgb, var(' . $base_var . '), white ' . $lighter_value . '% )';
                            
                            // Generate dark variation
                            $dark_value = !empty($color_item['dark']) ? $color_item['dark'] : 15;
                            $properties[] = $base_var . '-dark:color-mix( in srgb, var(' . $base_var . '), black ' . $dark_value . '% )';
                        }
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

        // typography css vars
        $typography_css_vars = get_option('typography_css_vars');
        if( is_array($typography_css_vars) ){
            foreach ($typography_css_vars as $prop => $value) {
                if($value){
                    if( str_starts_with($prop,'--') ){
                        $properties[] = $prop.':'.$value;
                    }
                } 
            }
        }

        return $properties;
    }

    public function get_html_properties(){
        $properties = [];

        $typography_css_vars = get_option('typography_css_vars');
        if( is_array($typography_css_vars) ){
            foreach ($typography_css_vars as $prop => $value) {
                if($value && $prop === 'base_font_size' ){
                    if($value != '16px') $properties[] = 'font-size:'.$value;
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

        $html_properties = self::$instance->get_html_properties();
        if( !empty($html_properties) ){
            $html_properties = implode(';', $html_properties);
            if( !empty($html_properties) ) $css .= 'html{'.$html_properties.'}';
        }
        
        if( !empty($root_lines) ) $css .= ':root, .text-color-1 {'.implode(';', $root_lines ).'}';
        if( !empty($css) ) wp_add_inline_style( 'mv23theme-styles', $css );
    }

    public function enqueue_admin_scripts(){
        $theme_options = self::$instance;
		$theme_colors = array('#000000','#ffffff');
        $added_colors = array();
		
        // Get colors from theme_colors
        $colors_data = get_option('theme_colors', array());
        
        if (is_array($colors_data) && !empty($colors_data)) {
            foreach ($colors_data as $color_item) {
                // Only process color type items
                if (isset($color_item['__type']) && $color_item['__type'] === 'color') {
                    if (!empty($color_item['color']) && !in_array($color_item['color'], $added_colors)) {
                        $theme_colors[] = $color_item['color'];
                        $added_colors[] = $color_item['color'];
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
        $uri = $this->theme_uri . '/assets/js/customizer.js';
	    wp_enqueue_script( 'theme-custom-fields', $uri, array( 'jquery', 'uf-customize-preview' ), $this->version, true );
    }
}