<?php
namespace Theme_Custom_Fields;

use Ultimate_Fields\Options_Page;

class Theme_options{
	private static $instance = null;

    private $slug = 'theme-options-menu';

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Theme_options();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){
        if( !current_user_can('administrator') ) return;

        Options_Page::create( $this->slug, 'Theme Options' )->set_position( 2 )->set_capability( 'manage_options' );
        Options_Page::create( 'theme-options', 'Theme Options' )->set_parent( $this->slug );
        Options_Page::create( 'header-options', 'Header' )->set_parent( $this->slug );
        Options_Page::create( 'custom-scripts-options', 'Custom Scripts' )->set_parent( $this->slug );

        $this->add_theme_options_meta_boxes();
        add_filter( 'custom_menu_order', array( $this, 'rearrange_submenu_order' ));
    }

    public function add_theme_options_meta_boxes(){
        // require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/common-settings-container.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/custom-scripts-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/header-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/main-theme-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/rrss-options.php' );
        require_once( THEME_CUSTOM_FIELDS_DIR.'/containers/global-options.php' );

        /**
         * Let child theme register its own meta boxes
         */
	    do_action('add_theme_options_meta_boxes');
    }

    function rearrange_submenu_order( $menu_ord ){
        global $submenu;
        // Enable the next line to see the menu order
        // echo '<pre>'.print_r($submenu[$this->slug],true).'</pre>';
    
        $order_list = array( 
            'Theme Options',
            'Header', 
            'Pie de Página', 
            'Off-Canvas Elements',
            'Custom Scripts', 
            'Megamenú', 
            'Accordion', 
            'Archive Pages',
            'Secciones Reusables' 
        );
    
        $new_order = array();
        $not_in_list = array();
        for ($i=0; $i < count($order_list); $i++) { 
            $new_order[ $i ] = null;
        }
        for ($i=0; $i < count( $submenu[$this->slug] ); $i++) { 
            $key = array_search( $submenu[$this->slug][$i][3], $order_list );
            if( $key > -1 ){
                $new_order[ $key ] = $submenu[$this->slug][$i]; 
            } else {
                $not_in_list[] = $submenu[$this->slug][$i];
            }
        }
        $new_order = array_merge( $new_order, $not_in_list );
        $submenu[$this->slug] = $new_order;

        return $menu_ord;
    }
}