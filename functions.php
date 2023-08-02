<?php
/*
Author: Miguel Velarde
URL: http://velarde23.com 
*/
require_once( 'inc/functions/utils.php' );
// if( !defined('WP_POST_REVISIONS') ) define ('WP_POST_REVISIONS', false);
if( !defined('THEME_DIR') ) define ('THEME_DIR', __DIR__);
if( !defined('THEME_VERSION') ) define ('THEME_VERSION', '8.23');
if( !defined('PARENT_THEME_TEST_MODE') ) define ('PARENT_THEME_TEST_MODE', false);
define ('IS_MOBILE', wp_is_mobile());
define ('GM_IS_ACTIVE', get_option('activate_gm'));
define ('SCROLL_ANIMATIONS', scroll_animation_is_active());
define ('SCROLL_INDICATORS', scroll_indicators_is_active());
define ('ULTIMATE_FIELDS_DISABLE_UI', true);
define ('IS_MULTILANGUAGE', class_exists('Polylang') );
define ('FONT_AWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');
$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';
define ('WOOCOMMERCE_IS_ACTIVE', in_array( $plugin_path, wp_get_active_and_valid_plugins() ));

$wp_media_folder_path = trailingslashit( WP_PLUGIN_DIR ) . 'wp-media-folder/wp-media-folder.php';
define ('WPMEDIAFOLDER_IS_ACTIVE', in_array( $wp_media_folder_path, wp_get_active_and_valid_plugins() ));

if( !defined('MAIN_COLOR') ) define ('MAIN_COLOR', '#F8522C');
if( !defined('SECONDARY_COLOR') ) define ('SECONDARY_COLOR', '#2A5354');
if( !defined('TERTIARY_COLOR') ) define ('TERTIARY_COLOR', '#CDC6BE');
if( !defined('PAGE_HEADER_BGC') ) define ('PAGE_HEADER_BGC', get_secondary_color());
if( !defined('PAGE_HEADER_LAYOUT') ) define ('PAGE_HEADER_LAYOUT', 'layout2');
if( !defined('PAGE_HEADER_TEXT_COLOR') ) define ('PAGE_HEADER_TEXT_COLOR', 'text-color-2');
if( !defined('PAGE_HEADER_BGI') ) define ('PAGE_HEADER_BGI', 0);
if( !defined('PAGE_HEADER_CONTENT_BUILDER') ) define ('PAGE_HEADER_CONTENT_BUILDER', false);
if( !defined('BORDER_RADIUS') ) define ('BORDER_RADIUS', 15);
if( !defined('LOGOS_QUANTITY') ) define ('LOGOS_QUANTITY', 2);
if( !defined('FLOATING_HEADER_BREAKPOINT') ) define ('FLOATING_HEADER_BREAKPOINT', 200);
if( !defined('HEADER_HEIGHT') ) define ('HEADER_HEIGHT', 0); // for anchors if header is fixed
if( !defined('CARD_CONTENT_TYPE') ) define ('CARD_CONTENT_TYPE', 'components');
if( !defined('IMAGE_THUMB_SIZE') ) define ('IMAGE_THUMB_SIZE', 'full');
if( !defined('COLOR_PICKER_PALETTES') ) define ('COLOR_PICKER_PALETTES', array('#000000','#ffffff',get_main_color(),get_secondary_color(),get_tertiary_color(),'#0065bd','#5f27cd','#bcd81c'));

if( !defined('CF7_USE_EMAIL_TEMPLATE') ) define ('CF7_USE_EMAIL_TEMPLATE', false);
if( !defined('CF7_EMAIL_MAIN_COLOR') ) define ('CF7_EMAIL_MAIN_COLOR', get_main_color());
// logo url: useful if logo is svg or another not supported format in emails
if( !defined('CF7_EMAIL_LOGO') ) define ('CF7_EMAIL_LOGO', false);

if( !defined('MOBILE_MENU_LOGO') ) define ('MOBILE_MENU_LOGO', 'secondary_logo');
if( !defined('MOBILE_MENU_WIDTH') ) define ('MOBILE_MENU_WIDTH', 300);
if( !defined('MOBILE_MENU_POSITION') ) define ('MOBILE_MENU_POSITION', 'left');

if( !defined('UF_POSTTYPES') ) define ('UF_POSTTYPES', array('page','post','megamenu','archive_page'));
if( !defined('UF_TAXONOMIES') ) define ('UF_TAXONOMIES', array('category'));
if( !defined('CONTENT_BUILDER_POSTTYPES') ) define ('CONTENT_BUILDER_POSTTYPES', array());
if( !defined('DISABLE_PAGE_HEADER_IN') ) define ('DISABLE_PAGE_HEADER_IN', array('megamenu'));
if( !defined('PAGE_SETTINGS_POSTTYPES') ) define ('PAGE_SETTINGS_POSTTYPES', array('page','post','archive_page'));

if( !defined('MENU_ITEM_DATA_LOCATIONS') ) define ('MENU_ITEM_DATA_LOCATIONS', array('main-nav'));
if( !defined('ARCHIVE_OPTIONS_POSTTYPES') ) define ('ARCHIVE_OPTIONS_POSTTYPES', array('post' => 'Entradas'));
if( !defined('ARCHIVE_OPTIONS_TAXONOMIES') ) define ('ARCHIVE_OPTIONS_TAXONOMIES', array('category' => 'Category','post_tag' => 'Tag'));

if( !defined('CONTENT_SLIDER') ) define ('CONTENT_SLIDER', false);
if( !defined('ROW') ) define ('ROW', false);
if( !defined('COLUMNAS_SIMPLES') ) define ('COLUMNAS_SIMPLES', false);
if( !defined('ITEMS_GRID') ) define ('ITEMS_GRID', false);
if( !defined('CARD') ) define ('CARD', false);
if( !defined('PROGRESS_CIRCLE') ) define ('PROGRESS_CIRCLE', false);
if( !defined('PROGRESS_BAR') ) define ('PROGRESS_BAR', false);

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
if( !defined('LISTING_CPTS') ) define( 'LISTING_CPTS', array('post' => 'Entradas'));
if( !defined('LISTING_TAXONOMIES') ) define( 'LISTING_TAXONOMIES', array());
if( !defined('LISTING_TEMPLATES') ) define( 'LISTING_TEMPLATES', array('' => 'Estilo por defecto', 'carrusel' => 'Carrusel'));
if( !defined('LISTING_PAGINATION_TYPES') ) define( 'LISTING_PAGINATION_TYPES', array('none' => 'Ninguno', 'classic' => 'Numérico', 'load_more' => 'Cargar más'));
if( !defined('LISTING_POST_TEMPLATE') ) define( 'LISTING_POST_TEMPLATE', array('' => 'Estilo por defecto'));
if( !defined('LISTING_LOADING_TEXT') ) define( 'LISTING_LOADING_TEXT', array('es' => 'Cargando...', 'en' => 'Loading...' ));
if( !defined('LISTING_LOAD_MORE_TEXT') ) define( 'LISTING_LOAD_MORE_TEXT', array('es' => 'Cargar más...', 'en' => 'Load more...' ));

if( !defined('MODAL_OUT_DURATION') ) define( 'MODAL_OUT_DURATION', 200);
if( !defined('DEFAULT_COLOR_SCHEME') ) define( 'DEFAULT_COLOR_SCHEME', '');
if( !defined('DEFAULT_TEXT_COLOR') ) define( 'DEFAULT_TEXT_COLOR', 'text-color-default');

require_once( 'libs/ultimate-fields/ultimate-fields.php' );

require_once( 'inc/classes/page.php' );
require_once( 'inc/classes/floating-header.php' );
require_once( 'inc/classes/header.php' );
require_once( 'inc/classes/page-header.php' );

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
require_once( 'inc/functions/fix-fatal-error-allowed-memory-size-error.php' );
// require_once( 'inc/functions/maybe-redirect-archive-page.php' );
require_once( 'inc/functions/archive-page.php' );
require_once( 'inc/functions/remove-custom-fields-metabox.php' );
require_once( 'inc/functions/ajax/mv23-library-actions.php' );
require_once( 'inc/functions/body-style-tag.php' );
require_once( 'inc/functions/show-cpt-count-in-admin.php' );
require_once( 'inc/functions/theme-my-login-multi-language-support.php' );
if( CF7_USE_EMAIL_TEMPLATE ) require_once( 'inc/functions/cf7-mail-template.php' );
require_once( 'inc/functions/ajax/load-posts.php' );
get_template_part( 'inc/functions/woocommerce-support' );
// require_once( 'inc/functions/tinymce-buttons/button-manager.php' );

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
}

add_action( 'after_setup_theme', 'mv23_launch_theme' );

/* DON'T DELETE THIS CLOSING TAG */ ?>