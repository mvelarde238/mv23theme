<?php
namespace Core\Posttype;

use Core\Utils\CPT;

class Accordion {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Accordion();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $accordions = new CPT(
            array(
                'post_type_name' => 'v23accordion',
                'singular' => 'Accordion',
                'plural' => 'Accordion',
            ), 
            array(
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'exclude_from_search' => true,
                'supports' => array('title')
                )
            );

        $accordions->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'shortcode' => __('Shortcode'),
            'date' => __('Date')
        ));
            
        $accordions->populate_column('shortcode', function($column_name, $post) {
            echo '<code>[accordion id="'.$post->ID.'"]</code>';
        });
    }
}

