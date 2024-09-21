<?php
define ('IS_MOBILE', wp_is_mobile());
define ('GM_IS_ACTIVE', get_option('activate_gm'));
define ('SCROLL_ANIMATIONS', scroll_animation_is_active());
define ('SCROLL_INDICATORS', scroll_indicators_is_active());
define ('FONT_AWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');
define ('BOOTSTRAP_ICONS', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

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

if( !defined('SEARCH_PLACEHOLDER') ) define ('SEARCH_PLACEHOLDER', array('es' => 'Buscar...', 'en' => 'Search...' ));

if( !defined('USE_PORTFOLIO_CPT') ) define( 'USE_PORTFOLIO_CPT', false);
if( !defined('LISTING_LOADING_TEXT') ) define( 'LISTING_LOADING_TEXT', array('es' => 'Cargando...', 'en' => 'Loading...' ));
if( !defined('LISTING_LOAD_MORE_TEXT') ) define( 'LISTING_LOAD_MORE_TEXT', array('es' => 'Cargar más...', 'en' => 'Load more...' ));
if( !defined('LISTING_PORTFOLIO_EXPANDER_HEIGHT') ) define( 'LISTING_PORTFOLIO_EXPANDER_HEIGHT', '500px');
if( !defined('LISTING_PORTFOLIO_SCROLL_DURATION') ) define( 'LISTING_PORTFOLIO_SCROLL_DURATION', '500');

if( !defined('MODAL_OUT_DURATION') ) define( 'MODAL_OUT_DURATION', 1);

if( !defined('OPEN_MINICART_ON_ADD_TO_CART') ) define ('OPEN_MINICART_ON_ADD_TO_CART', true);
if( !defined('MINICART_SIDENAV_POSITION') ) define ('MINICART_SIDENAV_POSITION', 'right');