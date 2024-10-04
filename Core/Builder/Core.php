<?php
namespace Core\Builder;

use Core\Builder\Common_Settings;
use Core\Builder\Component\Components_Wrapper;

define ('BUILDER_DIR', __DIR__);
define ('BUILDER_PATH', get_template_directory_uri() . '/Core/Builder');
require_once( 'common-settings-control/common-settings-control.php' );

class Core{
	private static $instance = null;

    private static $components = array();

    /**
     * Holds the list of components class names that will be registered in the Builder
     */
    private static $core_components = array(
        'core' => array(
            'Text_Editor',
            'Image',
            'Video',
            'Button',
            'Spacer',
            'Map',
            'Icon_and_Text',
            'Slider',
            'Listing',
            'Html',
            'Gallery',
            'Testimonials',
            'Menu',
            'Carrusel',
            'Accordion'
        ),
        'theme' => array(),
        'wrappers' => array(
            'Flip_Box',
            'Inner_Columns',
            'Columns'
        )
    );

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){}

    public function hide_editor(){
        $posttypes = array('page','product');
        $show_editor_in = get_option('show_editor_in') ? get_option('show_editor_in') : array();
    
        foreach ($posttypes as $slug){
            if ( !in_array($slug, $show_editor_in) ) {
                remove_post_type_support( $slug, 'editor' );
            }
        }
    }

    public function add_meta_boxes(){
        require_once( BUILDER_DIR.'/containers/page-header.php' );
        require_once( BUILDER_DIR.'/containers/page-content.php' );
        require_once( BUILDER_DIR.'/containers/content-blocks.php' );
        require_once( BUILDER_DIR.'/containers/page-settings.php' );

        // uncomment just to generate the container json:
        // require_once( BUILDER_DIR.'/containers/popup/common-settings.php' );
        // require_once( BUILDER_DIR.'/containers/popup/scroll-animations.php' );
        // require_once( BUILDER_DIR.'/containers/popup/actions-container.php' );
        // require_once( BUILDER_DIR.'/containers/popup/blocks-layout-settings.php' );
        // require_once( BUILDER_DIR.'/containers/popup/blocks-layout-container.php' );

        /**
         * Let child theme register its own meta boxes
         */
	    do_action('theme_add_meta_boxes');
    }

    public function init_components(){
        $this->add_core_components_on_demand();

        do_action('theme_init_components');

        Common_Settings::getInstance();

        foreach( self::$core_components as $key => $components_group ) {
            foreach ($components_group as $component) {
                do_action('before_adding_'.$component.'_components');
                require_once( BUILDER_DIR.'/Component/'.$component.'.php' );
            }
            do_action('add_'.$key.'_components');
        }
    }

    public function add_core_components_on_demand(){
        add_action( 'before_adding_Inner_Columns_components', function(){
            if(SIMPLE_COLUMNS) new \Core\Builder\Component\Simple_Columns;
        });
        add_action( 'before_adding_Columns_components', function(){
            if(COMPONENTS_WRAPPER) new Components_Wrapper();
            if(CARD) new \Core\Builder\Component\Card();
            if(ITEMS_GRID) new \Core\Builder\Component\Items_Grid;
            if(CONTENT_SLIDER) new \Core\Builder\Component\Content_Slider;
        });
    }

    public static function register_component( $component, $class_name ){
        $namespace = 'Core\Builder\Component\\';
        $class_name = str_replace($namespace,'',$class_name);

        if( in_array($class_name, self::$core_components['core']) ){
            $category = 'core';
        } else if ( in_array($class_name, self::$core_components['wrappers']) ) {
            $category = 'wrappers';
        } else {
            $category = 'theme';
        }

        self::$components[$category][] = $component;
    }

    public function get_components(){
        $_components = array();
        $order = array('core','theme','wrappers');

        foreach( $order as $category ) {
            if( isset(self::$components[$category]) ){
                foreach ( self::$components[$category] as $component) {
                    $_components[] = $component;
                }
            }
        }

        return $_components;
    }
}