<?php
namespace Offcanvas_Elements;

use Offcanvas_Elements\Settings;

class Core{
	private static $instance = null;

    private $slug = 'offcanvas_element';

    // private $elements = array();

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){

        define( 'OFFCANVAS_ELEMENTS_DIR', trailingslashit( dirname( __DIR__ ) ) );
        require_once( __DIR__ . '/Autoloader.php' );
		new Autoloader( 'Offcanvas_Elements', OFFCANVAS_ELEMENTS_DIR . DIRECTORY_SEPARATOR . 'classes' );

        $this->register_post_type();
        $this->add_action( 'uf.init', array($this, 'register_settings') );
    }

    /**
     * Helper function to add add_action WordPress filters.
     */
    function add_action( $action, $function, $priority = 10, $accepted_args = 1 ) {
        add_action( $action, $function, $priority, $accepted_args );
    }

    public function get_slug(){
        return $this->slug;
    }

	private function register_post_type(){
		$offcanvas_pt = new \CPT(
			array(
				'post_type_name' => $this->slug,
				'plural' => 'Off-Canvas Elements',
			), 
			array(
				'show_in_menu' => 'theme-options/theme-options-admin.php',
				'show_in_nav_menus' => false,
				'supports' => array('title')
			)
		);
	}

    function register_settings(){
        Settings::instance();
    }
}