<?php
namespace Offcanvas_Elements;

use Offcanvas_Elements\Settings;
use \WP_Query;
use \Content_Layout;

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
        $this->add_action( 'footer_code', array($this, 'print_elements') );
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
				// 'show_in_menu' => 'theme-options/theme-options-admin.php',
				'show_in_nav_menus' => false,
				'supports' => array('title')
			)
		);
	}

    function register_settings(){
        Settings::instance();
    }

    function print_elements(){
        $query = new WP_Query( array( 'post_type' => $this->slug ) );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $post_id = get_the_ID();
                $type = get_post_meta( $post_id, $this->slug.'_type', true );
                $content = get_post_meta( $post_id, $this->slug.'_content', true );
                $kebab_cased_slug = str_replace('_','-',$this->slug);
                $element_id = $kebab_cased_slug.'-'.$post_id;
                $trigger_events = get_post_meta( $post_id, $this->slug.'_trigger_events', true );
                $settings = get_post_meta( $post_id, $this->slug.'_'.$type.'_settings', true );

                if( $type === 'sidenav' ){
                    echo "<div id='".$element_id."' class='".$kebab_cased_slug." side-nav' data-type='".$type."' ";
                    echo "data-settings='".json_encode($settings)."' ";
                    echo "data-trigger-events='".json_encode($trigger_events)."'>";
                    echo Content_Layout::the_content($content);
                    echo '<a href="#" class="modal-close"></a>';
                    echo '</div>';
                }
            }
        }
        wp_reset_postdata();
    }
}