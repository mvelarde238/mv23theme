<?php	
/**
 * enqueueing FRONTEND scripts & styles
 *
 */
function mv23_scripts_and_styles() {

    if (!is_admin()) {

        // comment reply script for threaded comments
        if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script( 'comment-reply' );
        }

        // register stylesheets
        $assets_url = (PARENT_THEME_TEST_MODE) ? get_template_directory_uri() :  get_stylesheet_directory_uri();
        wp_register_style( 'mv23-styles', $assets_url . '/assets/css/style.css', array(), THEME_VERSION, 'all' );
        wp_enqueue_style( 'mv23-styles' );
        wp_enqueue_style( 'theme-font-awesome', FONT_AWESOME, array(), THEME_VERSION, 'all' );

        // adding scripts files in the footer
        $gm_url = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WgG02INNI9A_3V1pxMVsstKGDX4blvc&callback=initMaps';
        $gm_services = get_option('gm_services') ? get_option('gm_services') : array();
        if( count($gm_services) ) $gm_url .= '&libraries='. implode(",", $gm_services);
        if (GM_IS_ACTIVE) wp_enqueue_script( 'googleapis', $gm_url, array(), '1.0', true);

        if( SCROLL_ANIMATIONS ){
            wp_enqueue_script( 'scroll-animations', get_template_directory_uri() . '/assets/js/scrollmagic.js', array(), '1.0', false);
            if( SCROLL_INDICATORS ){
                wp_enqueue_script( 'scroll-indicators', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js', array('scroll-animations'), '1.0', false);
            }
        }

        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '', true);
        wp_enqueue_script('jquery');

        wp_enqueue_script('jquery-masonry');

        wp_enqueue_script( 'fitty-lib', get_template_directory_uri() . '/src/js/libs/ignore/fitty.min.js', array(), THEME_VERSION, true );
        wp_register_script( 'mv23-scripts', $assets_url . '/assets/js/scripts.js', array(), THEME_VERSION, true );

        $static_header = new Header();
        $sticky_header = new Header('sticky');
        wp_localize_script( 'mv23-scripts', 'STATIC_HEADER', $static_header->get_options() ); 
        wp_localize_script( 'mv23-scripts', 'STICKY_HEADER', $sticky_header->get_options() ); 

        wp_localize_script( 'mv23-scripts', 'MV23_GLOBALS', array( 
            'isMobile' => wp_is_mobile(), 
            'ajaxUrl' => admin_url( 'admin-ajax.php' ), 
            'homeUrl' => home_url(), 
            'nonce' =>  wp_create_nonce( 'global-nonce' ),
            'userIsLoggedIn' => is_user_logged_in(),
            'lang' => (function_exists('pll_current_language')) ? pll_current_language() : 'es',
            'headerHeight' => HEADER_HEIGHT,
            'stickyHeaderBreakpoint' => STICKY_HEADER_BREAKPOINT,
            'mobile_menu_position' => MOBILE_MENU_POSITION,
            'listing_loading_text' => LISTING_LOADING_TEXT,
            'modal' => array(
                'outDuration' => MODAL_OUT_DURATION
            ),
            'expanderHeight' => LISTING_PORTFOLIO_EXPANDER_HEIGHT,
            'listingPortfolioScrollDuration' => LISTING_PORTFOLIO_SCROLL_DURATION,
            'carousels' => array(),
            'scrollAnimations' => SCROLL_ANIMATIONS,
            'scrollIndicators' => SCROLL_INDICATORS,
            'open_minicart_on_add_to_cart' => OPEN_MINICART_ON_ADD_TO_CART,
            'minicart_sidenav_position' => MINICART_SIDENAV_POSITION,
            'items_in_cart' => (WOOCOMMERCE_IS_ACTIVE) ? WC()->cart->get_cart_contents_count() : null,
        )); 

        wp_enqueue_script( 'mv23-scripts' );
    }
}

add_action( 'wp_enqueue_scripts', 'mv23_scripts_and_styles',999 );








/**
 * Enqueue ADMIN scripts and styles.
 */


function mv23_admin_stuff(){
    wp_enqueue_style( 'font-awesome', FONT_AWESOME, array(), THEME_VERSION );
    $assets_url = get_template_directory_uri();
    wp_enqueue_style( 'mv23-admin-styles', $assets_url . '/assets/css/admin-styles.css', array(), THEME_VERSION);
    wp_register_script( 'mv23-admin-scripts', $assets_url . '/assets/js/admin-scripts.js',array('jquery'),THEME_VERSION, false );
    wp_localize_script( 'mv23-admin-scripts', 'MV23_GLOBALS', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' )
    ));
    wp_enqueue_script( 'mv23-admin-scripts' );
}
add_action('admin_head', 'mv23_admin_stuff');

add_action( 'admin_enqueue_scripts', function(){
    wp_deregister_script('uf-field-color');
    $assets_url = get_template_directory_uri();
    wp_enqueue_script('colorpicker-enhancement', $assets_url . '/assets/js/color-picker-enhancement.js', array('uf-field', 'wp-color-picker'), THEME_VERSION, false );
    wp_localize_script( 'colorpicker-enhancement', 'COLOR_PICKER', array( 
        'palettes' => COLOR_PICKER_PALETTES
    ));
});