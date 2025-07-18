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

class Frontend extends Theme_Header_Data {

    public function __construct() { 
        parent::__construct();
    }

    public function enqueue_styles() {
        if (!is_admin()) {
            wp_enqueue_style( $this->text_domain.'-styles', $this->theme_uri . '/assets/css/style.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->text_domain.'-font-awesome', FONT_AWESOME, array(), $this->version, 'all' );
            wp_enqueue_style( $this->text_domain.'-bootstrap-icons', BOOTSTRAP_ICONS, array(), $this->version, 'all' );
        }
    }

    public function enqueue_scripts() {
        if (!is_admin()) {

            // comment reply script for threaded comments
            if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
                wp_enqueue_script( 'comment-reply' );
            }
            
            // adding scripts files in the footer
            
            if ( GM_IS_ACTIVE) {
                $google_api_key = get_option( 'uf_google_maps_api_key' );
                $gm_url = 'https://maps.googleapis.com/maps/api/js?key='.$google_api_key;
                $gm_services = get_option('gm_services') ? get_option('gm_services') : array();
                if( count($gm_services) ) $gm_url .= '&libraries='. implode(",", $gm_services);
                wp_enqueue_script( 'googleapis', $gm_url, array(), '1.0', true);
            }

            if( LEAFLET_IS_ACTIVE ){
                wp_enqueue_script( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js', array(), '1.0', true);
                wp_enqueue_style( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css', array(), '1.0', 'all' );
            }
    
            if( SCROLL_ANIMATIONS ){
                wp_enqueue_script( 'scroll-animations', $this->theme_uri . '/assets/js/gsap.js', array(), '1.0', true);
            }
    
            wp_deregister_script( 'jquery' );
            wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '', true );
            wp_enqueue_script( 'jquery' );
    
            if (MASONRY_IS_ACTIVE) wp_enqueue_script( 'jquery-masonry' );

            // Ensure GSAP is loaded before main scripts
            $dependencies = array();
            if( SCROLL_ANIMATIONS ) $dependencies[] = 'scroll-animations';
    
            wp_register_script( $this->text_domain . '-scripts', $this->theme_uri . '/assets/js/scripts.js', $dependencies, $this->version, true );
    
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
    
            wp_enqueue_script( $this->text_domain . '-scripts' );
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
    
        $page_color_scheme = get_metadata($page->get_type(), $page->get_id(),'page_color_scheme', true);
        if ( $page_color_scheme && $page_color_scheme == 'dark-scheme' ) $classes[] = 'text-color-2';
    
        $hide_static_header = get_metadata($page->get_type(), $page->get_id(),'hide_static_header', true);
        if ( $hide_static_header ) $classes[] = 'hide-static-header';
    
        $hide_sticky_header = get_metadata($page->get_type(), $page->get_id(),'hide_sticky_header', true);
        if ( $hide_sticky_header ) $classes[] = 'hide-sticky-header';
    
        $disable_comments_styles = get_option( 'disable_comments_styles' );
        if ( $disable_comments_styles ) $classes[] = 'disable-comments-styles';
            
        return $classes;
    }

    public function body_style(){
        $page = new Page();
        $style = '';
        
        $page_bgc = get_metadata($page->get_type(), $page->get_id(),'page_bgc', true);
        
        if ( is_array($page_bgc) && array_key_exists('add_bgc', $page_bgc) ) {
            $style .= ($page_bgc['add_bgc']) ? 'background-color: '.$page_bgc['bgc'].';' : '';
        }

        $remove_body_padding_top = get_metadata($page->get_type(), $page->get_id(), 'remove_body_padding_top', true);
        if( $remove_body_padding_top ){
            $style .= 'padding-top:0px;';
        }
    
        if (!empty($style)) {
            $style = 'style="'.$style.'"';		
        }

        if( SCROLL_ANIMATIONS ){
            $global_animations = get_option('global_animations');
            if( !empty($global_animations) ){
                $style .= ' '.Scroll_Animations::get_attribute( $global_animations );
            } 
        }

        echo $style;
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