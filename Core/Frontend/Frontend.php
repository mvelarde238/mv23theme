<?php
/**
 * Fontend specific functionality of this theme.
 */

namespace Core\Frontend;

use Core\Includes\Theme_Header_Data;
use Core\Frontend\Shortcodes as Shortcodes;
use Core\Frontend\Page;
use Core\Frontend\Header;
use Core\Theme_Options\Theme_Options;
use Core\Builder\Template_Engine\Scroll_Animations;
use Core\Builder\Template_Engine\Id;;

class Frontend extends Theme_Header_Data {

    public static $styles_control = array();

    public static $scripts_control = array();

    public function __construct() { 
        parent::__construct();
    }

    /*
    * Attach a style/script to this class to be registered later
    * Child themes can use this method to add their own styles/scripts
    * Attached styles/scripts will be registered for frontend and builder use
    */
    public static function add_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {
        self::$styles_control[] = array(
            'handle' => $handle,
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'media' => $media
        );
    }

    public static function add_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = true ) {
        self::$scripts_control[] = array(
            'handle' => $handle,
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'in_footer' => $in_footer
        );
    }

    /*
    * Get attached style/scripts handles
    */
    public static function get_styles_control_handles() {
        return array_column( self::$styles_control, 'handle' );
    }

    public static function get_scripts_control_handles() {
        return array_column( self::$scripts_control, 'handle' );
    }

    /*
    * Register frontend styles 
    */
    public function register_styles(){
        self::add_style( $this->text_domain.'-styles', $this->theme_uri . '/assets/css/style.css', array(), $this->version, 'all' );
        self::add_style( $this->text_domain.'-font-awesome', FONT_AWESOME, array(), $this->version, 'all' );
        self::add_style( $this->text_domain.'-bootstrap-icons', BOOTSTRAP_ICONS, array(), $this->version, 'all' );
        if( LEAFLET_IS_ACTIVE ) {
            self::add_style( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css', array(), '1.0', 'all' );
        }

        $theme_options = Theme_Options::getInstance();

        // add theme fonts styles
        $fonts = $theme_options->get_theme_fonts();
        $count = 0;
        foreach ($fonts['urls'] as $url) {
            self::add_style( $fonts['names'][$count], $url );
            $count++;
        }

        do_action( $this->text_domain.'_add_additional_styles' );

        // register attached styles
        foreach( self::$styles_control as $style ){
            wp_register_style( $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
        }

        // register inline styles from theme options
        $theme_options->add_theme_fonts();
        $theme_options->add_css_properties();
    }

    /*
    * Register frontend scripts
    */
    public function register_scripts() {
        if ( GM_IS_ACTIVE) {
            $google_api_key = get_option( 'uf_google_maps_api_key' );
            $gm_url = 'https://maps.googleapis.com/maps/api/js?key='.$google_api_key;
            $gm_services = get_option('gm_services') ? get_option('gm_services') : array();
            if( count($gm_services) ) $gm_url .= '&libraries='. implode(",", $gm_services);
            self::add_script( 'googleapis', $gm_url, array(), '1.0', true);
        }

        if( LEAFLET_IS_ACTIVE ){
            self::add_script( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js', array(), '1.0', true);
        }

        // Ensure GSAP is loaded before main scripts if scroll animations are enabled
        $dependencies = array();
        if( SCROLL_ANIMATIONS ) $dependencies[] = 'gsap';
        self::add_script( $this->text_domain . '-scripts', $this->theme_uri . '/assets/js/scripts.js', $dependencies, $this->version, true );

        // register attached scripts
        foreach( self::$scripts_control as $script ){
            wp_register_script( $script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer'] );
        }
    }

    /**
    * Enqueue frontend styles/scripts
    */
    public function enqueue_styles() {
        $this->register_styles();

        foreach( self::$styles_control as $style ){
            wp_enqueue_style( $style['handle'] );
        }
    }

    public function enqueue_scripts(){
        $this->register_scripts();

        // comment reply script for threaded comments
        if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script( 'comment-reply' );
        }

        // use Google hosted jQuery
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '', true );
        wp_enqueue_script( 'jquery' );
    
        // masonry script
        if (MASONRY_IS_ACTIVE) wp_enqueue_script( 'jquery-masonry' );

        // gsap for scroll animations
        if( SCROLL_ANIMATIONS ) wp_enqueue_script( 'gsap', $this->theme_uri . '/assets/js/gsap.js', array(), '1.0', true);

        // localize global variables
        $static_header = new Header();
        $sticky_header = new Header('sticky');
        wp_localize_script( $this->text_domain . '-scripts', 'STATIC_HEADER', $static_header->get_options() ); 
        wp_localize_script( $this->text_domain . '-scripts', 'STICKY_HEADER', $sticky_header->get_options() ); 
        wp_localize_script( $this->text_domain . '-scripts', 'MV23_GLOBALS', array(
            'pageID' => get_the_ID(),
            'isSingle' => is_single(),
            'isMobile' => wp_is_mobile(), 
            'ajaxUrl' => admin_url( 'admin-ajax.php' ), 
            'homeUrl' => home_url(), 
            'nonce' =>  wp_create_nonce( 'global-nonce' ),
            'userIsLoggedIn' => is_user_logged_in(),
            'lang' => (function_exists('pll_current_language')) ? pll_current_language() : 'es',
            'headerHeight' => HEADER_HEIGHT,
            'stickyHeaderBreakpoint' => STICKY_HEADER_BREAKPOINT,
            'listing_loading_text' => LISTING_LOADING_TEXT,
            'modal' => array(
                'outDuration' => MODAL_OUT_DURATION
            ),
            'expanderHeight' => LISTING_EXPANDER_HEIGHT,
            'expanderResponseHeight' => LISTING_EXPANDER_RESPONSE_HEIGHT,
            'expanderScrollDuration' => LISTING_EXPANDER_SCROLL_DURATION,
            'carousels' => array(),
            'scrollAnimations' => SCROLL_ANIMATIONS,
            'adjustScrollPosition' => ADJUST_SCROLL_POSITION,
            'open_minicart_on_add_to_cart' => OPEN_MINICART_ON_ADD_TO_CART,
            'minicart_sidenav_position' => MINICART_SIDENAV_POSITION,
            'woocommerce_is_active' => WOOCOMMERCE_IS_ACTIVE,
            'items_in_cart' => (WOOCOMMERCE_IS_ACTIVE) ? WC()->cart->get_cart_contents_count() : null,
            'menu_breakpoint' => 896,
            'masonry_is_active' => MASONRY_IS_ACTIVE,
            'debug' => DEBUG_SCRIPTS,
            'maybeFixScrollPositionStyles' => MAYBE_FIX_SCROLL_POSITION_STYLES
        )); 

        foreach( self::$scripts_control as $script ){
            wp_enqueue_script( $script['handle'] );
        }
    }

    public function register_thumbnail_sizes() {
        add_theme_support( 'post-thumbnails' );
        // add_image_size( 'custom-thumbnail' , 900 , 600 , true );
    }

    public function add_theme_support() {
        add_theme_support( 'html5' , array( 'comment-list' , 'comment-form' , 'search-form' , 'gallery' , 'caption' ) );
        add_theme_support( 'menus' );
        // 'aside' , 'gallery' , 'link' , 'image' , 'quote' , 'status' , 'video' , 'audio' , 'chat'
        // add_theme_support( 'post-formats' , array( 'link' ) );
    }

    public function load_theme_textdomain() {
        load_theme_textdomain( $this->text_domain, $this->theme_path . '/languages' );
    }

    public function title_meta_tag( $title, $sep, $seplocation ) {
        global $page, $paged;
  
        // Don't affect in feeds.
        if ( is_feed() ) return $title;
  
        // Add the web's name
        if ( 'right' == $seplocation ) {
            $title .= get_bloginfo( 'name' );
        } else {
            $title = get_bloginfo( 'name' ) .' |'. $title;
        }
  
        // Add the web description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
  
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title .= " {$sep} {$site_description}";
        }
  
        // Add a page number if necessary:
        if ( $paged >= 2 || $page >= 2 ) {
            /* translators: %s: page number */
            $title .= " {$sep} " . sprintf( __( 'Page %s', 'mv23theme' ), max( $paged, $page ) );
        }
  
        return $title;
    }

    public function body_class( $classes ) {
        $page = new Page();
        $page_content_components = ($page->get_id() != null) ? get_post_meta($page->get_id(), 'page_content_components', true) : null;
        if( is_array($page_content_components) && !empty($page_content_components) && isset($page_content_components[0]) ) {
            $page_component = $page_content_components[0];

            $hide_static_header = $page_component['hide_static_header'] ?? false;
            if ( $hide_static_header ) $classes[] = 'hide-static-header';

            $hide_sticky_header = $page_component['hide_sticky_header'] ?? false;
            if ( $hide_sticky_header ) $classes[] = 'hide-sticky-header';
        }
    
        $disable_comments_styles = get_option( 'disable_comments_styles' );
        if ( $disable_comments_styles ) $classes[] = 'disable-comments-styles';
            
        return $classes;
    }

    public function body_id(){
        $page = new Page();
        $id = null;

        $page_content_components = ($page->get_id() != null) ? get_post_meta($page->get_id(), 'page_content_components', true) : null;
        if( is_array($page_content_components) && !empty($page_content_components) && isset($page_content_components[0]) ) {
            $page_component = $page_content_components[0];
            $id = Id::get_id( $page_component );
        }

        echo ($id) ? 'id="'.$id.'"' : '';
    }

    public function body_style(){
        $page = new Page();
        $styles = [];

        $page_content_components = ($page->get_id() != null) ? get_post_meta($page->get_id(), 'page_content_components', true) : null;
        if( is_array($page_content_components) && !empty($page_content_components) && isset($page_content_components[0]) ) {
            $page_component = $page_content_components[0];
            $remove_padding_top = $page_component['remove_padding_top'] ?? false;
            if( $remove_padding_top ){
                $styles[] = 'padding-top:0px;';
            }
        }

        if( SCROLL_ANIMATIONS ){
            $global_animations = get_option('global_animations');
            if( !empty($global_animations) ){
                $styles[] = Scroll_Animations::get_attribute( $global_animations );
            } 
        }

        echo (!empty($styles)) ? 'style="'.implode(' ', $styles).'"' : '';
    }

    public function archive_title_meta_tag($title){
        if ( is_category() ) {
            $title = single_cat_title( '', false );
    
        } elseif ( is_tag() ) {
            // $title = single_tag_title( '', false );
            return $title;
    
        } elseif ( is_author() ) {
            // $title = '<span class="vcard">' . get_the_author() . '</span>' ;
            return $title;
        }
    
        return $title;
    }

    public function add_custom_meta_tags() {
        $primary_color = Theme_Options::getInstance()->get_property('primary_color');
        if( $primary_color ){
            echo '<meta name="theme-color" content="'.$primary_color.'">' . "\n";
            echo '<meta name="msapplication-TileColor" content="'.$primary_color.'">' . "\n";
        }
    }

    public function responsive_video($html, $url, $attr, $post_ID) {
        $return = '<div class="responsive-video">'.$html.'</div>';
        return $return;
    }

    public function include_shortcodes(){
        $shortcodes = new Shortcodes();
        $shortcodes->init();
    }
}