<?php
define ('THEME_CUSTOM_FIELDS_DIR', __DIR__);
define ('THEME_CUSTOM_FIELDS_PATH', get_template_directory_uri() . '/inc/custom-fields');

if( !defined('ULTIMATE_FIELDS_DISABLE_UI') ) define ('ULTIMATE_FIELDS_DISABLE_UI', true);
if( !defined('DEFAULT_COLOR_SCHEME') ) define( 'DEFAULT_COLOR_SCHEME', '');
if( !defined('DEFAULT_TEXT_COLOR') ) define( 'DEFAULT_TEXT_COLOR', 'text-color-default');
if( !defined('LOGOS_QUANTITY') ) define ('LOGOS_QUANTITY', 2);
if( !defined('COLUMNS_QUANTITY') ) define ('COLUMNS_QUANTITY', 4);
if( !defined('USE_REUSABLE_SECTIONS_AS_PAGE_MODULE') ) define ('USE_REUSABLE_SECTIONS_AS_PAGE_MODULE', false);

if( !defined('UF_POSTTYPES') ) define ('UF_POSTTYPES', array('page','megamenu','archive_page','footer'));
if( !defined('PAGE_SETTINGS_POSTTYPES') ) define ('PAGE_SETTINGS_POSTTYPES', array('page','archive_page'));
if( !defined('CONTENT_BUILDER_POSTTYPES') ) define ('CONTENT_BUILDER_POSTTYPES', array());
if( !defined('MENU_ITEM_MEGAMENU_LOCATIONS') ) define ('MENU_ITEM_MEGAMENU_LOCATIONS', array('main-nav'));

if( !defined('COMPONENTS_WRAPPER') ) define ('COMPONENTS_WRAPPER', true);
if( !defined('CARD') ) define ('CARD', false);
if( !defined('COLUMNAS_SIMPLES') ) define ('COLUMNAS_SIMPLES', false);
if( !defined('ITEMS_GRID') ) define ('ITEMS_GRID', false);
if( !defined('CONTENT_SLIDER') ) define ('CONTENT_SLIDER', false);

require_once( 'ultimate-fields/ultimate-fields.php' );
require_once( 'classes/Core.php' );
require_once( 'classes/Template_Engine.php' );
require_once( 'classes/Content_Layout.php' );
require_once( 'classes/Content_Selector.php' );

Theme_Custom_Fields\Core::getInstance();