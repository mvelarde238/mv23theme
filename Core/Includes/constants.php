<?php
define ('IS_MOBILE', wp_is_mobile());
define ('GM_IS_ACTIVE', get_option('activate_gm'));
define ('SCROLL_ANIMATIONS', scroll_animation_is_active());
define ('SCROLL_INDICATORS', scroll_indicators_is_active());
define ('FONT_AWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');

// define ('IS_MULTILANGUAGE', class_exists('Polylang') );
$polylang_path = trailingslashit( WP_PLUGIN_DIR ) . 'polylang/polylang.php';
define ('IS_MULTILANGUAGE', in_array( $polylang_path, wp_get_active_and_valid_plugins() ));

$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';
define ('WOOCOMMERCE_IS_ACTIVE', in_array( $plugin_path, wp_get_active_and_valid_plugins() ));

$wp_media_folder_path = trailingslashit( WP_PLUGIN_DIR ) . 'wp-media-folder/wp-media-folder.php';
define ('WPMEDIAFOLDER_IS_ACTIVE', in_array( $wp_media_folder_path, wp_get_active_and_valid_plugins() ));

if( !defined('STICKY_HEADER_BREAKPOINT') ) define ('STICKY_HEADER_BREAKPOINT', 20);
if( !defined('HEADER_HEIGHT') ) define ('HEADER_HEIGHT', 64); // for anchors if header is fixed
if( !defined('CUSTOM_TINYMCE_FONTS') ) define('CUSTOM_TINYMCE_FONTS', array());

if( !defined('CF7_USE_EMAIL_TEMPLATE') ) define ('CF7_USE_EMAIL_TEMPLATE', true);
if( !defined('CF7_EMAIL_MAIN_COLOR') ) define ('CF7_EMAIL_MAIN_COLOR', '#ff7a00');
if( !defined('CF7_EMAIL_BUTTON_COLOR') ) define ('CF7_EMAIL_BUTTON_COLOR', '#071a36');
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