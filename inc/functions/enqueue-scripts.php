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
        // wp_register_style('jquery-ui-datepicker','http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css');
        // wp_enqueue_style( 'jquery-ui-datepicker' );

        $assets_url = (PARENT_THEME_TEST_MODE) ? get_template_directory_uri() :  get_stylesheet_directory_uri();
        wp_register_style( 'mv23-styles', $assets_url . '/style.css', array(), THEME_VERSION, 'all' );
        wp_enqueue_style( 'mv23-styles' );
        wp_enqueue_style( 'theme-font-awesome', FONT_AWESOME, array(), THEME_VERSION, 'all' );

        // adding scripts files in the footer
        if (GM_IS_ACTIVE) wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WgG02INNI9A_3V1pxMVsstKGDX4blvc', array(), '1.0', true);

        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '', true);
        wp_enqueue_script('jquery');

        wp_enqueue_script( 'fitty-lib', get_template_directory_uri() . '/src/js/libs/fitty.min.js', array(), THEME_VERSION, true );
        wp_register_script( 'mv23-scripts', $assets_url . '/assets/js/scripts.js', array(), THEME_VERSION, true );

        wp_localize_script( 'mv23-scripts', 'MV23_GLOBALS', array( 
            'isMobile' => wp_is_mobile(), 
            'ajaxUrl' => admin_url( 'admin-ajax.php' ), 
            'homeUrl' => home_url(), 
            'nonce' =>  wp_create_nonce( 'global-nonce' ),
            'userIsLoggedIn' => is_user_logged_in(),
            'lang' => 'es_ES'
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
    wp_register_script( 'mv23-admin-scripts', $assets_url . '/assets/js/admin-scripts.js',array('jquery'),THEME_VERSION, true );
    wp_enqueue_script( 'mv23-admin-scripts' );
}

add_action('admin_head', 'mv23_admin_stuff');