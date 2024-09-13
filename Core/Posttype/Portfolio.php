<?php
namespace Core\Posttype;

use Core\Utils\CPT;

class Portfolio {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Portfolio();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
		$portfolios = new CPT(
			'portfolio', 
			array(
				'public' => true,
				'has_archive' => true,
				'supports' => array('title','editor','thumbnail','revisions','excerpt'),
				'menu_icon' => 'dashicons-screenoptions',
			)
		);
		
		$portfolios->register_taxonomy(array(
			'taxonomy_name' => 'portfolio-cat',
			'singular' => 'Portfolio Category',
			'plural' => 'Portfolio Categories',
			'show_ui' => true,
			'slug' => 'portfolio-cat'
		));

		$portfolios->register_taxonomy(array(
			'taxonomy_name' => 'portfolio-tag',
			'hierarchical' => false,
			'show_ui' => true,
			'singular' => 'Portfolio Tag',
			'plural' => 'Portfolio Tags',
			'slug' => 'portfolio-tag'
		));
	}
}