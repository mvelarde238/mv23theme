<?php
/*
Author: Miguel Velarde
URL: http://velarde23.com 
*/
// if( !defined('WP_POST_REVISIONS') ) define ('WP_POST_REVISIONS', false);
if( !defined('THEME_VERSION') ) define ('THEME_VERSION', '8.23');
if( !defined('PARENT_THEME_TEST_MODE') ) define ('PARENT_THEME_TEST_MODE', false);
define ('IS_MOBILE', wp_is_mobile());
define ('GM_IS_ACTIVE', get_option('activate_gm'));
define ('ULTIMATE_FIELDS_DISABLE_UI', true);
define ('IS_MULTILANGUAGE', class_exists('Polylang') );
define ('FONT_AWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');

if( !defined('MAIN_COLOR') ) define ('MAIN_COLOR', '#F8522C');
if( !defined('SECONDARY_COLOR') ) define ('SECONDARY_COLOR', '#2A5354');
if( !defined('TERTIARY_COLOR') ) define ('TERTIARY_COLOR', '#CDC6BE');
if( !defined('PAGE_HEADER_BGC') ) define ('PAGE_HEADER_BGC', '#2A5354');
if( !defined('PAGE_HEADER_TEXT_COLOR') ) define ('PAGE_HEADER_TEXT_COLOR', 'text-color-2');
if( !defined('HEADER_THEME') ) define ('HEADER_THEME', 'Transparente con letras negras');
if( !defined('PAGE_HEADER_BGI') ) define ('PAGE_HEADER_BGI', 0);
if( !defined('BORDER_RADIUS') ) define ('BORDER_RADIUS', 15);

if( !defined('UF_POSTTYPES') ) define ('UF_POSTTYPES', array('page','post','megamenu','archive_page'));
if( !defined('DISABLE_PAGE_HEADER_IN') ) define ('DISABLE_PAGE_HEADER_IN', array('megamenu'));

if( !defined('MENU_ITEM_DATA_LOCATIONS') ) define ('MENU_ITEM_DATA_LOCATIONS', array('main-nav'));
if( !defined('ARCHIVE_OPTIONS_POSTTYPES') ) define ('ARCHIVE_OPTIONS_POSTTYPES', array('post' => 'Entradas'));
if( !defined('ARCHIVE_OPTIONS_TAXONOMIES') ) define ('ARCHIVE_OPTIONS_TAXONOMIES', array('category' => 'Category','post_tag' => 'Tag'));

if( !defined('CONTENT_SLIDER') ) define ('CONTENT_SLIDER', false);
if( !defined('ROW') ) define ('ROW', false);
if( !defined('COLUMNAS_SIMPLES') ) define ('COLUMNAS_SIMPLES', false);
if( !defined('ITEMS_GRID') ) define ('ITEMS_GRID', false);
if( !defined('TEMPLATE_PART') ) define ('TEMPLATE_PART', false);

require_once( 'libs/ultimate-fields/ultimate-fields.php' );

require_once( 'inc/functions/theme.php' );
require_once( 'inc/functions/enqueue-scripts.php' );
require_once( 'inc/functions/register-sidebars.php' );
require_once( 'inc/functions/get-tinymce-custom-colors.php' );
require_once( 'inc/functions/get-style-formats.php' );
require_once( 'inc/functions/hex-to-rgba.php' );
require_once( 'inc/functions/personalizar-tinymce.php' );
require_once( 'inc/functions/filter-archive-title.php' );
require_once( 'inc/functions/security-functions.php' );
require_once( 'inc/functions/video-responsive.php' );
require_once( 'inc/functions/hide-editor.php' );
require_once( 'inc/functions/admin.php' );
get_template_part( 'inc/functions/theme-nav-walker' );
require_once( 'inc/ultimate-fields/index.php' );
require_once( 'inc/functions/include-posttypes.php' );
require_once( 'inc/functions/include-shortcodes.php' );
require_once( 'inc/functions/include-ajax-functions.php' );
require_once( 'inc/functions/fix-fatal-error-allowed-memory-size-error.php' );
// require_once( 'inc/functions/maybe-redirect-archive-page.php' );
require_once( 'inc/functions/archive-page.php' );
require_once( 'inc/functions/remove-custom-fields-metabox.php' );
require_once( 'inc/functions/ajax/mv23-library-actions.php' );

function mv23_launch_theme() {
    // launching operation cleanup
    add_action( 'init', 'mv23_head_cleanup' );
    // A better title
    add_filter( 'wp_title', 'mv23_title_meta_tag', 10, 3 );
    // remove WP version from RSS
    add_filter( 'the_generator', 'mv23_rss_version' );
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', 'mv23_remove_wp_widget_recent_comments_style', 1 );
    // clean up comment styles in the head
    add_action( 'wp_head', 'mv23_remove_recent_comments_style', 1 );
    // clean up gallery output in wp
    add_filter( 'gallery_style', 'mv23_gallery_style' );
    // launching this stuff after theme setup
    mv23_theme_support();
    add_nav_support();
    // cleaning up random code around images
    // add_filter( 'the_content', 'mv23_filter_ptags_on_images' );
    // cleaning up excerpt
    add_filter( 'excerpt_more', 'mv23_excerpt_more' );
    // adding sidebars to Wordpress (these are created in functions.php)
    add_action( 'widgets_init', 'mv23_register_sidebars' );
    // remove emoji detection script and styles
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );    
    // Allow editor style.
    $assets_url = (PARENT_THEME_TEST_MODE) ? get_template_directory_uri() :  get_stylesheet_directory_uri();
    add_editor_style( $assets_url . '/assets/css/editor-style.css' );  
    // language support 
    // load_theme_textdomain( 'mv23', get_template_directory() . '/translation' );
    mv23_include_posttypes();
    mv23_include_shortcodes();
    mv23_include_ajax_functions();
}

add_action( 'after_setup_theme', 'mv23_launch_theme' );


/* DON'T DELETE THIS CLOSING TAG */ ?>