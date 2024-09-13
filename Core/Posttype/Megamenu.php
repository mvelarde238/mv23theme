<?php
namespace Core\Posttype;

use Core\Utils\CPT;

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
}
