<?php
define ('IS_MOBILE', wp_is_mobile());
define ('GM_IS_ACTIVE', get_option( 'uf_google_maps_api_key' ) && get_option('activate_gm'));
define ('LEAFLET_IS_ACTIVE', get_option('activate_leaflet'));
define ('INITIAL_MAP_POSITION', array('lat' => -33.4377756, 'lng' => -70.6504502, 'zoom' => 12));
define ('MASONRY_IS_ACTIVE', get_option('activate_masonry'));
define ('SCROLL_ANIMATIONS', scroll_animation_is_active());
define ('FONT_AWESOME', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');
define ('BOOTSTRAP_ICONS', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
define ('POSTS_SUBSCRIPTION', posts_subscription_is_active());
define ('LIBS_PATH', get_template_directory_uri() . '/libs');

// define ('IS_MULTILANGUAGE', class_exists('Polylang') );
define ('IS_MULTILANGUAGE', function_exists('pll_the_languages') );

$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';
define ('WOOCOMMERCE_IS_ACTIVE', in_array( $plugin_path, wp_get_active_and_valid_plugins() ));

$wp_media_folder_path = trailingslashit( WP_PLUGIN_DIR ) . 'wp-media-folder/wp-media-folder.php';
define ('WPMEDIAFOLDER_IS_ACTIVE', in_array( $wp_media_folder_path, wp_get_active_and_valid_plugins() ));

if( !defined('DEBUG_SCRIPTS') ) define ('DEBUG_SCRIPTS', false);

if( !defined('STICKY_HEADER_BREAKPOINT') ) define ('STICKY_HEADER_BREAKPOINT', 20);
if( !defined('HEADER_HEIGHT') ) define ('HEADER_HEIGHT', 64); // for anchors if header is fixed
if( !defined('CUSTOM_TINYMCE_FONTS') ) define('CUSTOM_TINYMCE_FONTS', array());

if( !defined('CF7_USE_EMAIL_TEMPLATE') ) define ('CF7_USE_EMAIL_TEMPLATE', true);
if( !defined('CF7_EMAIL_MAIN_COLOR') ) define ('CF7_EMAIL_MAIN_COLOR', '#ff7a00');
if( !defined('CF7_EMAIL_BUTTON_COLOR') ) define ('CF7_EMAIL_BUTTON_COLOR', '#071a36');
// logo url: useful if logo is svg or another not supported format in emails
if( !defined('CF7_EMAIL_LOGO') ) define ('CF7_EMAIL_LOGO', false);

if( !defined('MAIN_NAV_STYLE') ) define ('MAIN_NAV_STYLE', array('horizontal-nav','horizontal-nav-1'));
if( !defined('MOBILE_NAV_STYLE') ) define ('MOBILE_NAV_STYLE', array('horizontal-nav','horizontal-nav-1'));
// if( !defined('MENU_ITEM_DATA_LOCATIONS') ) define ('MENU_ITEM_DATA_LOCATIONS', array('main-nav'));

if( !defined('SEARCH_PLACEHOLDER') ) define ('SEARCH_PLACEHOLDER', array('es' => 'Buscar...', 'en' => 'Search...' ));
if( !defined('MODAL_OUT_DURATION') ) define( 'MODAL_OUT_DURATION', 1);
if( !defined('OPEN_MINICART_ON_ADD_TO_CART') ) define ('OPEN_MINICART_ON_ADD_TO_CART', true);
if( !defined('MINICART_SIDENAV_POSITION') ) define ('MINICART_SIDENAV_POSITION', 'right');
if( !defined('DEFAULT_TEXT_COLOR') ) define( 'DEFAULT_TEXT_COLOR', 'text-color-default');
if( !defined('MAYBE_FIX_SCROLL_POSITION_STYLES') ) define ('MAYBE_FIX_SCROLL_POSITION_STYLES', array('tab-style1', 'accordion-style1'));
if( !defined('LOGOS_QUANTITY') ) define ('LOGOS_QUANTITY', 2);

if( !defined('USE_PORTFOLIO_CPT') ) define( 'USE_PORTFOLIO_CPT', false);
if( !defined('USE_DOCUMENT_CPT') ) define( 'USE_DOCUMENT_CPT', false);

if( !defined('LISTING_LOADING_TEXT') ) define( 'LISTING_LOADING_TEXT', array('es' => 'Cargando...', 'en' => 'Loading...' ));
if( !defined('LISTING_LOAD_MORE_TEXT') ) define( 'LISTING_LOAD_MORE_TEXT', array('es' => 'Cargar más...', 'en' => 'Load more...' ));
if( !defined('LISTING_EXPANDER_HEIGHT') ) define( 'LISTING_EXPANDER_HEIGHT', '500px');
if( !defined('LISTING_EXPANDER_RESPONSE_HEIGHT') ) define( 'LISTING_EXPANDER_RESPONSE_HEIGHT', '500px');
if( !defined('LISTING_EXPANDER_SCROLL_DURATION') ) define( 'LISTING_EXPANDER_SCROLL_DURATION', '500');
if( !defined('LISTING_GAP') ) define( 'LISTING_GAP', array('desktop' => 50,'laptop' => 40,'tablet' => 30,'mobile' => 20 ));
if( !defined('LISTING_COLUMNS') ) define( 'LISTING_COLUMNS', array('desktop' => 3,'laptop' => 3,'tablet' => 2,'mobile' => 1));
if( !defined('LISTING_CPTS') ) define( 'LISTING_CPTS', array('post' => 'Entradas'));
if( !defined('LISTING_TAXONOMIES') ) define( 'LISTING_TAXONOMIES', array( array( 'cpt_slug' => 'post', 'slug' => 'category' ) ));
if( !defined('LISTING_TEMPLATES') ){ 
    define( 'LISTING_TEMPLATES', array(
        '' => 'Default Listing Template', 
       'carousel' => 'Carousel',
       'masonry' => 'Masonry',
    ));
}
if( !defined('LISTING_PAGINATION_TYPES') ) {
    define( 'LISTING_PAGINATION_TYPES', array(
        'none' => 'None', 
        'numeric' => 'Numeric pagination', 
        'load-more' => 'Load more pagination'
    ));
}
if( !defined('LISTING_POST_TEMPLATE') ){
    define( 'LISTING_POST_TEMPLATE', array(
        '_default' => 'Default', // special value/placeholder to use the posttype as template
        'post' => 'Post',
        'post-horizontal' => 'Horizontal Post'
    ));
} 
// TOGGLEBOX_STATE_ICON
if( !defined('TOGGLEBOX_STATE_ICON') ) define( 'TOGGLEBOX_STATE_ICON', 'bi-caret-down');

// CAROUSEL ICONS
if ( ! defined( 'PREV_CAROUSEL_ICON' ) ) define( 'PREV_CAROUSEL_ICON', 'fa-angle-left' );
if ( ! defined( 'NEXT_CAROUSEL_ICON' ) ) define( 'NEXT_CAROUSEL_ICON', 'fa-angle-right' );

// BUILDER CONSTANTS
if( !defined('BUILDER_DEV_MODE') ) define ('BUILDER_DEV_MODE', false);
define ('ULTIMATE_FIELDS_DISABLE_UI', get_option('uf_disable_ui', false) );
define ('DEFAULT_BUILDER_POSTTYPES', array('post','page','megamenu','footer','reusable_section','header','single_template','archive_template'));