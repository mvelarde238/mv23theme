<?php
namespace Core\Theme_Options;

use Core\Includes\Theme_Header_Data;
use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Field\Font;
use Core\Utils\Helpers;
use Core\Theme_Options\UF_Container\Main;
use Core\Theme_Options\UF_Container\Global_Styles;
use Core\Theme_Options\UF_Container\Custom_Scripts;
use Core\Theme_Options\UF_Container\Posts_Subscription;
use Core\Theme_Options\UF_Container\Track_Posts_Data;
use Core\Theme_Options\UF_Container\Builder_Options;

class Theme_Options extends Theme_Header_Data{
	private static $instance = null;

    private $slug = 'theme-options-menu';

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Theme_Options();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){
        parent::__construct();
        $this->hide_repeater_groups();
    }

    public function init_options_page(){
        if( current_user_can('administrator') ){
            Options_Page::create( $this->slug, __( 'Theme Options', 'mv23theme' ) )->set_position( 2 )->set_capability( 'manage_options' );
            Options_Page::create( 'theme-options', __( 'Theme Options', 'mv23theme' ) )->set_parent( $this->slug );
            Options_Page::create( 'custom-scripts-options', 'Custom Scripts' )->set_parent( $this->slug );

            // load uf-containers
            Main::init();
            Global_Styles::init();
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
            'edit.php?post_type=postcard', 
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
                    // font urls
                    $custom_font_urls = array();
                    $type = $item['type'] ?? 'file';
                    $name = $item['name'] ?? '';
                    $names[] = $name;
                    $variant = $item['variant'] ?? 'normal';

                    if($type == 'file'){
                        $files = $item['files'] ?? array();
                        if( is_array($files) && !empty($files) ){
                            foreach ($files as $file) {
                                $custom_font_urls[] = 'url('.wp_get_attachment_url($file).')';
                            }
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

        // containers
        $containers_settings = get_option( 'containers_settings' );
        if( !empty($containers_settings) ){
            foreach ($containers_settings as $item) {
                $width = $item['width'];
                $max_width = $item['max_width'];
                if( $item['scope'] === 'global' ){
                    if( $width ) $properties[] = '--container-width:'.$width.'%';
                    if( $max_width ) $properties[] = '--container-max-width:'.$max_width.'px';
                } elseif ( $item['scope'] === 'custom' && !empty($item['selector']) ){
                    if( $width ) $properties[] = $item['selector'].'{--container-width:'.$width.'%}';
                    if( $max_width ) $properties[] = $item['selector'].'{--container-max-width:'.$max_width.'px}';
                } else {
                    if( $width ) $properties[] = '.'.$item['scope'].'{--container-width:'.$width.'%}';
                    if( $max_width ) $properties[] = '.'.$item['scope'].'{--container-max-width:'.$max_width.'px}';
                }
            }
        }

        // typography css vars
        $types = ['typography','headings','links', 'custom_css'];
        foreach ($types as $type) {
            $type_settings = get_option($type.'_settings');
            if( is_array($type_settings) ){
                foreach ($type_settings as $prop => $value) {
                    if($value){
                        if( str_starts_with($prop,'--') ){
                            $properties[] = $prop.':'.$value;
                        
                        } elseif( $prop === 'base_font_size' ){
                            $properties[] = 'html{font-size:'.$value.'}';
                        
                        } elseif( $prop === 'custom_global_css' ){
                            $properties[] = preg_replace('/\s*([{};:,])\s*/', '$1', preg_replace('/\s+/', ' ', trim($value)));
                        }
                    } 
                }
            }
        }

        // breakpoint typography css vars
        $breakpoints_ids = ['tablet', 'mobileLandscape', 'mobilePortrait'];
        $breakpoints = BREAKPOINTS;
        foreach ($breakpoints_ids as $bp_id) {
            $bp_root_lines = array();
            $bp_css = '';
            foreach ($types as $type) {
                $type_settings = get_option( '_breakpoint_' . $bp_id . '_' . $type . '_settings' );
                if( is_array($type_settings) ){
                    foreach ($type_settings as $prop => $value) {
                        if( $value ){
                            if( str_starts_with($prop, '--') ){
                                $bp_root_lines[] = $prop . ':' . $value;

                            } elseif( $prop === 'base_font_size' ){
                                $bp_css .= 'html{font-size:' . $value . '}';
                            
                            } elseif( $prop === 'custom_global_css' ){
                                $bp_css .= preg_replace('/\s*([{};:,])\s*/', '$1', preg_replace('/\s+/', ' ', trim($value)));
                            }
                        }
                    }
                }
            }
            if( !empty($bp_root_lines) ){
                $bp_css .= ':root, .text-color-1{' . implode(';', $bp_root_lines) . '}';
            }
            if( !empty($bp_css) ){
                $properties[] = '@media (max-width:' . $breakpoints[$bp_id] . 'px){' . $bp_css . '}';
            }
        }

        return $properties;
    }

    public function get_html_properties(){
        $properties = [];

        $types = ['typography','headings','links'];
        foreach ($types as $type) {
            $type_settings = get_option($type.'_settings');
            if( is_array($type_settings) ){
                foreach ($type_settings as $prop => $value) {
                    if($value && $prop === 'base_font_size' ){
                        if($value != '16px') $properties[] = 'font-size:'.$value;
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
        $media_css = '';

        if( !empty($properties) ){
            foreach ($properties as $prop) {
                if( str_starts_with($prop,'--') ){ 
                    // is a css property
                    $root_lines[] = $prop;
                } elseif( str_starts_with($prop, '@media') ){
                    // defer media queries to the end
                    $media_css .= $prop;
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
        $css .= $media_css;
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

    public function print_head_scripts(){
        $head_scripts = get_option( 'head_scripts' );
        if ($head_scripts) echo $head_scripts;
    }

    public function print_body_scripts(){
        $body_scripts = get_option( 'body_scripts' );
        if ($body_scripts) echo $body_scripts;
    }

    public function print_footer_scripts(){
        $footer_scripts = get_option( 'footer_scripts' );
        if ($footer_scripts) echo $footer_scripts;
    }
}