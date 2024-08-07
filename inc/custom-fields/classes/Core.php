<?php
namespace Theme_Custom_Fields;

use Theme_Custom_Fields\Theme_options;
use Theme_Custom_Fields\Common_Settings;
use Theme_Custom_Fields\Component\Components_Wrapper;

class Core{
	private static $instance = null;

    private static $components = array();

    /**
     * Holds the list of components class names that will be registered in the Core
     */
    private static $core_components = array(
        'core' => array(
            'Text_Editor',
            'Image',
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
            'Inner_Columns',
            'Columns'
        )
    );

    /**
     * Hold the list of available logo versions to be used as select options
     */
    private static $logos_field_names = array();

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){
        $core_directory = trailingslashit( dirname( __DIR__ ) );
        require_once( __DIR__ . '/Autoloader.php' );
		new Autoloader( 'Theme_Custom_Fields', $core_directory . DIRECTORY_SEPARATOR . 'classes' );

        $this->add_core_components_on_demand();
        $this->add_action( 'after_setup_theme', array($this, 'init_components'), 15 ); // theme launch at 10
        $this->add_action( 'uf.init', array($this, 'add_meta_boxes') );
        $this->add_action( 'init', array($this, 'hide_editor') );
        $this->add_action( 'init', array($this, 'init_theme_options') );
    }

    /**
     * Helper function to add add_action WordPress filters.
     */
    private function add_action( $action, $function, $priority = 10, $accepted_args = 1 ) {
        add_action( $action, $function, $priority, $accepted_args );
    }

    public function hide_editor(){
        $posttypes = array('page','product');
        $show_editor_in = get_option('show_editor_in') ? get_option('show_editor_in') : array();
    
        foreach ($posttypes as $slug){
            if ( !in_array($slug, $show_editor_in) ) {
                remove_post_type_support( $slug, 'editor' );
            }
        }
    }

    public function init_theme_options(){
        Theme_options::getInstance();
    }

    public function add_meta_boxes(){
        $this->set_logos_field_names();

        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/page-header.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/page-content.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/content-blocks.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/page-settings.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/menu-item-data.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/menu-item-megamenu.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/accordion-settings.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/archive-page-settings.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/library-item-data.php' );

        /**
         * Let child theme register its own meta boxes
         */
	    do_action('theme_add_meta_boxes');
    }

    private function set_logos_field_names(){
        for ($i=1; $i <= LOGOS_QUANTITY; $i++) { 
            switch ($i) {
                case 1:
                    $field_name = 'main_logo';
                    break;
        
                case 2:
                    $field_name = 'secondary_logo';
                    break;
                
                default:
                    $field_name = 'logo_v'.$i;
                    break;
            }
            $field_title = 'Logo Versión '.$i;
            self::$logos_field_names[$field_name] = $field_title;
        }
        self::$logos_field_names['custom'] = 'Custom';
    }

    public static function get_logos_field_names(){
        return self::$logos_field_names;
    }

    public function init_components(){
        do_action('theme_init_components');

        Common_Settings::getInstance();

        foreach( self::$core_components as $key => $components_group ) {
            foreach ($components_group as $component) {
                do_action('before_adding_'.$component.'_components');
                require_once( THEME_CUSTOM_FIELDS_DIR.'/classes/Component/'.$component.'.php' );
            }
            do_action('add_'.$key.'_components');
        }
    }

    public function add_core_components_on_demand(){
        add_action( 'before_adding_Columns_components', function(){
            if(COMPONENTS_WRAPPER) new Components_Wrapper();
            if(CARD) new \Theme_Custom_Fields\Component\Card();
            if(COLUMNAS_SIMPLES) new \Theme_Custom_Fields\Component\Simple_Columns;
            if(ITEMS_GRID) new \Theme_Custom_Fields\Component\Items_Grid;
            if(CONTENT_SLIDER) new \Theme_Custom_Fields\Component\Content_Slider;
        });
    }

    public static function register_component( $component, $class_name ){
        $namespace = 'Theme_Custom_Fields\Component\\';
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