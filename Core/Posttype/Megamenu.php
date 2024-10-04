<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

if( !defined('MENU_ITEM_MEGAMENU_LOCATIONS') ) define ('MENU_ITEM_MEGAMENU_LOCATIONS', array('main-nav'));

class Megamenu {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Megamenu();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $megamenus = new CPT(
            array(
                'post_type_name' => 'megamenu',
                'plural' => 'Megamenú',
            ), 
            array(
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'exclude_from_search' => true,
                'supports' => array('title')
            )
        );
    }

    public function add_meta_boxes(){
        Container::create( 'menu-item-megamenu' )
            ->set_title( 'Megamenú' )
            ->add_location( 'menu_item', array(
                'levels' => 1,
                'theme_locations' => __locations_multilingual_support( MENU_ITEM_MEGAMENU_LOCATIONS ),
                'popup_mode' => true
            ))
            ->add_fields(array(
                Field::create( 'checkbox', 'is_megamenu', 'Activar' )->set_text('¿Activar Megamenú?'),
                Field::create( 'wp_object', 'megamenu_post' )->add( 'posts', 'post_type=megamenu' )->set_button_text( 'Seleccione el megamenú' )->add_dependency('is_megamenu'),
            ));
    }
}
