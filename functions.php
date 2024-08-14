<?php
/*
Author: Miguel Velarde
URL: http://velarde23.com 
*/
require_once( 'inc/functions/utils.php' );
// if( !defined('WP_POST_REVISIONS') ) define ('WP_POST_REVISIONS', false);
if( !defined('THEME_DIR') ) define ('THEME_DIR', __DIR__);
if( !defined('THEME_VERSION') ) define ('THEME_VERSION', '0.4.1');
if( !defined('PARENT_THEME_TEST_MODE') ) define ('PARENT_THEME_TEST_MODE', true);
define ('IS_MOBILE', wp_is_mobile());
define ('GM_IS_ACTIVE', get_option('activate_gm'));
define ('SCROLL_ANIMATIONS', scroll_animation_is_active());
define ('SCROLL_INDICATORS', scroll_indicators_is_active());
define ('FONT_AWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');

// define ('IS_MULTILANGUAGE', class_exists('Polylang') );
$polylang_path = trailingslashit( WP_PLUGIN_DIR ) . 'polylang/polylang.php';
define ('IS_MULTILANGUAGE', in_array( $polylang_path, wp_get_active_and_valid_plugins() ));

$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'polylang/polylang.php';
define ('WOOCOMMERCE_IS_ACTIVE', in_array( $plugin_path, wp_get_active_and_valid_plugins() ));

$wp_media_folder_path = trailingslashit( WP_PLUGIN_DIR ) . 'wp-media-folder/wp-media-folder.php';
define ('WPMEDIAFOLDER_IS_ACTIVE', in_array( $wp_media_folder_path, wp_get_active_and_valid_plugins() ));

if( !defined('MAIN_COLOR') ) define ('MAIN_COLOR', '#ff7a00');
if( !defined('SECONDARY_COLOR') ) define ('SECONDARY_COLOR', '#071a36');
if( !defined('TERTIARY_COLOR') ) define ('TERTIARY_COLOR', '#CDC6BE');
if( !defined('BORDER_RADIUS') ) define ('BORDER_RADIUS', 15);
if( !defined('STICKY_HEADER_BREAKPOINT') ) define ('STICKY_HEADER_BREAKPOINT', 20);
if( !defined('HEADER_HEIGHT') ) define ('HEADER_HEIGHT', 64); // for anchors if header is fixed
if( !defined('IMAGE_THUMB_SIZE') ) define ('IMAGE_THUMB_SIZE', 'full');
if( !defined('COLOR_PICKER_PALETTES') ) define ('COLOR_PICKER_PALETTES', array('#000000','#ffffff',get_main_color(),get_secondary_color(),get_tertiary_color(),'#0065bd','#5f27cd','#bcd81c'));
if( !defined('CUSTOM_TINYMCE_FONTS') ) define('CUSTOM_TINYMCE_FONTS', array());

if( !defined('CF7_USE_EMAIL_TEMPLATE') ) define ('CF7_USE_EMAIL_TEMPLATE', true);
if( !defined('CF7_EMAIL_MAIN_COLOR') ) define ('CF7_EMAIL_MAIN_COLOR', get_main_color());
if( !defined('CF7_EMAIL_BUTTON_COLOR') ) define ('CF7_EMAIL_BUTTON_COLOR', get_secondary_color());
// logo url: useful if logo is svg or another not supported format in emails
if( !defined('CF7_EMAIL_LOGO') ) define ('CF7_EMAIL_LOGO', false);

if( !defined('MAIN_NAV_STYLE') ) define ('MAIN_NAV_STYLE', array('horizontal-nav','horizontal-nav-1'));
// if( !defined('MENU_ITEM_DATA_LOCATIONS') ) define ('MENU_ITEM_DATA_LOCATIONS', array('main-nav'));
if( !defined('ARCHIVE_OPTIONS_POSTTYPES') ) define ('ARCHIVE_OPTIONS_POSTTYPES', array('post' => 'Entradas'));
if( !defined('ARCHIVE_OPTIONS_TAXONOMIES') ) define ('ARCHIVE_OPTIONS_TAXONOMIES', array('category' => 'Category','post_tag' => 'Tag'));

if( !defined('LISTING_DESKTOP_COLUMNS') ) define ('LISTING_DESKTOP_COLUMNS', 2);
if( !defined('LISTING_LAPTOP_COLUMNS') ) define ('LISTING_LAPTOP_COLUMNS', 2);
if( !defined('LISTING_TABLET_COLUMNS') ) define ('LISTING_TABLET_COLUMNS', 2);
if( !defined('LISTING_MOBILE_COLUMNS') ) define ('LISTING_MOBILE_COLUMNS', 1);
if( !defined('LISTING_DESKTOP_GAP') ) define ('LISTING_DESKTOP_GAP', '50px');
if( !defined('LISTING_LAPTOP_GAP') ) define ('LISTING_LAPTOP_GAP', '40px');
if( !defined('LISTING_TABLET_GAP') ) define ('LISTING_TABLET_GAP', '30px');
if( !defined('LISTING_MOBILE_GAP') ) define ('LISTING_MOBILE_GAP', '20px');
if( !defined('ARCHIVE_SIDEBAR') ) define ('ARCHIVE_SIDEBAR', true);
if( !defined('ARCHIVE_MAIN_CONTENT_TEMPLATE') ) define ('ARCHIVE_MAIN_CONTENT_TEMPLATE', 'main-content--sidebar-left');
if( !defined('SEARCH_PLACEHOLDER') ) define ('SEARCH_PLACEHOLDER', array('es' => 'Buscar...', 'en' => 'Search...' ));

if( !defined('SINGLE_SIDEBAR') ) define ('SINGLE_SIDEBAR', true);
if( !defined('SINGLE_MAIN_CONTENT_TEMPLATE') ) define ('SINGLE_MAIN_CONTENT_TEMPLATE', 'main-content--sidebar-right');

if( !defined('USE_PORTFOLIO_CPT') ) define( 'USE_PORTFOLIO_CPT', false);
if( !defined('LISTING_LOADING_TEXT') ) define( 'LISTING_LOADING_TEXT', array('es' => 'Cargando...', 'en' => 'Loading...' ));
if( !defined('LISTING_LOAD_MORE_TEXT') ) define( 'LISTING_LOAD_MORE_TEXT', array('es' => 'Cargar más...', 'en' => 'Load more...' ));
if( !defined('LISTING_PORTFOLIO_EXPANDER_HEIGHT') ) define( 'LISTING_PORTFOLIO_EXPANDER_HEIGHT', '500px');
if( !defined('LISTING_PORTFOLIO_SCROLL_DURATION') ) define( 'LISTING_PORTFOLIO_SCROLL_DURATION', '500');

if( !defined('MODAL_OUT_DURATION') ) define( 'MODAL_OUT_DURATION', 1);

if( !defined('OPEN_MINICART_ON_ADD_TO_CART') ) define ('OPEN_MINICART_ON_ADD_TO_CART', true);
if( !defined('MINICART_SIDENAV_POSITION') ) define ('MINICART_SIDENAV_POSITION', 'right');

require_once( 'inc/classes/CPT.php' );
require_once( 'inc/classes/page.php' );
require_once( 'inc/custom-fields/index.php' );
require_once( 'inc/offcanvas-elements/offcanvas-elements.php' );
require_once( 'inc/migrator/index.php' );

require_once( 'inc/classes/header.php' );
require_once( 'inc/classes/page-header.php' );
require_once( 'inc/classes/Theme_Nav_Walker.php' );
require_once( 'inc/classes/Archive_Page.php' );

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
require_once( 'inc/functions/admin.php' );
require_once( 'inc/functions/include-posttypes.php' );
require_once( 'inc/functions/include-shortcodes.php' );
require_once( 'inc/functions/fix-fatal-error-allowed-memory-size-error.php' );
// require_once( 'inc/functions/maybe-redirect-archive-page.php' );
require_once( 'inc/functions/remove-custom-fields-metabox.php' );
require_once( 'inc/functions/ajax/mv23-library-actions.php' );
require_once( 'inc/functions/body-style-tag.php' );
require_once( 'inc/functions/body-class.php' );
require_once( 'inc/functions/show-cpt-count-in-admin.php' );
require_once( 'inc/functions/theme-my-login-multi-language-support.php' );
if( CF7_USE_EMAIL_TEMPLATE ) require_once( 'inc/functions/cf7-mail-template.php' );
require_once( 'inc/functions/ajax/load-posts.php' );
get_template_part( 'inc/functions/woocommerce-support' );
// require_once( 'inc/functions/tinymce-buttons/button-manager.php' );
require_once( 'inc/functions/dequeue-styles.php' );

// remove_all_actions( 'admin_notices' );

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
}

add_action( 'after_setup_theme', 'mv23_launch_theme', 10 );

/* DON'T DELETE THIS CLOSING TAG */ ?>