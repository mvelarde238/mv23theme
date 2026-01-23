<?php
namespace Core\Builder;

use Core\Builder\Component\Components_Wrapper;
use Ultimate_Fields\Container;
use Core\Builder\Template_Engine;

define ('BUILDER_DIR', __DIR__);
define ('BUILDER_PATH', get_template_directory_uri() . '/Core/Builder');
require_once( 'uf-extend/common-settings-control/common-settings-control.php' );
require_once( 'uf-extend/columns-layout/columns-layout.php' );
require_once( 'uf-extend/ultimate-builder/ultimate-builder.php' );

class Core{
	private static $instance = null;

    private static $components = array();

    private static $popup_containers = array(
        'actions_container',
        'blocks_layout_settings_container',
        'common_settings_container',
        'scroll_animations_container',
        'row_settings_container',
        'column_settings_container'
    );

    /**
     * Holds the list of components class names that will be registered in the Builder
     */
    private static $core_components = array(
        'core' => array(
            'Text_Editor',
            'Heading',
            'Figure',
            'Image',
            'Video',
            'Button',
            'Spacer',
            'Map',
            'Icon_and_Text',
            'Shortcode',
            'Listing',
            'Code',
            'Gallery',
            'Testimonials',
            'Menu',
            'Column'
        ),
        'theme' => array(),
        'wrappers' => array(
            'Inner_Wrapper',
            'Flip_Box',
            'Carousel',
            'Inner_Accordion',
            'Accordion_Button',
            'Accordion',
            'Components_Wrapper',
            'Inner_Row',
            'Row',
            'OCE_Modal_Content',
            'OCE_Dynamic_Content',
            'Offcanvas_Element',
            // 'Hero_Section',
            'Section',
            'Page',
            'Single_Page_Structure',
            'Post_Title',
            'Sidebar',
            'Social_Share',
            'Related_Posts'
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
        $hide_wp_editor_on = get_option('hide_wp_editor_on') ? get_option('hide_wp_editor_on') : array();
    
        foreach ($hide_wp_editor_on as $post_type){
            if ( in_array($post_type, $hide_wp_editor_on) ) {
                remove_post_type_support( $post_type, 'editor' );
            }
        }
    }

    public function add_ultimate_builder_link( $actions, $post ) {
		$post_type = get_post_type( $post );
        $builder_posttypes = ( is_array(get_option('builder_posttypes')) ) ? get_option('builder_posttypes') : array();

		if ( in_array( $post_type, $builder_posttypes ) || $post_type === 'offcanvas_element' ) {
			$builder_url = add_query_arg( array(
				'action' => 'ultimate-builder',
				'meta'   => 'page_content',
			), get_edit_post_link( $post->ID, 'raw' ) );

			$actions['ultimate_builder'] = '<a href="' . esc_url( $builder_url ) . '">' . __( 'Edit with Ultimate Builder', 'mv23theme' ) . '</a>';
		}

		return $actions;
	}

    public function add_meta_boxes(){
        require_once( BUILDER_DIR.'/containers/page-content.php' );

        // load containers that wil be generated in a pop up:
        foreach (self::$popup_containers as $container) {
            require_once( BUILDER_DIR.'/containers/popup/'.$container.'.php' );
        }

        /**
         * Let child theme register its own meta boxes
         */
	    do_action('theme_add_meta_boxes');
    }

    public function register_popup_containers(){
        if(!is_admin()) return;

        $popup_containers = array();
		foreach( Container::get_registered() as $container ) {
            $container_id = $container->get_id(); 
			if( in_array($container_id, self::$popup_containers) ) {
				$popup_containers[$container_id] = $container->export_fields_settings();
			}
		}
		wp_localize_script( 'uf-field-common-settings-control', 'POPUP_CONTAINERS', $popup_containers);
    }

    public function init_components(){
        $this->add_core_components_on_demand();

        do_action('theme_init_components');

        foreach( self::$core_components as $key => $components_group ) {
            foreach ($components_group as $component) {
                do_action('before_adding_'.$component.'_components');

                // Use locate_template() function to check for the class in the child theme
                locate_template( 'Core/Builder/Component/'.$component.'.php', true, true);

                do_action('after_adding_'.$component.'_components');
            }
            do_action('add_'.$key.'_components');
        }
    }

    public function add_core_components_on_demand(){
        add_action( 'before_adding_Inner_Row_components', function(){
            if(SIMPLE_COLUMNS) new \Core\Builder\Component\Simple_Columns;
        });
        add_action( 'before_adding_Row_components', function(){
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

    public function get_groups_for_builder(){
        $groups = array();
        $components = $this->get_components();

        if(is_array($components) && count($components) > 0){
            foreach ($components as $component) {
                $groups[] = array(
                    '__group_id' => $component->get_id(),
                    'min_width' => 1,
                    'title' => $component->get_title(),
                    'icon' => $component->get_icon(),
                    'builder_data' => $component->get_builder_data(),
                    'title_template' => $component->get_title_template(),
                    'view_template' => $component->get_view_template(),
                    'fields' => $component->get_fields(),
                    'edit_mode' => $component->get_edit_mode(),
                    'layout' => $component->get_layout()
                );
            }
        }

        return $groups;
    }

    /** AJAX Handlers
     * used for the builder to get the component view
     * for ajaxified components rendering
     */
    public function ajax_get_component_view(){
        $component_view = Template_Engine::getInstance()->handle( $_REQUEST['__type'], $_REQUEST );
        $result = $component_view ? $component_view : '';
        wp_send_json_success($result);
    }

    /**
    * Get post types excluding specific ones
    * 
    * @return array
    */
    public static function get_post_types( $args = array() ) {
        $default_args = array(
            'exclude_post_types' => array( 'offcanvas_element','attachment','mv23_library','reusable_section','megamenu','archive_page','footer' ),
            'get_post_type_args' => array( 'public'=>true )
        );
        $args = wp_parse_args( $args, $default_args );

        $post_types = array();
        $excluded = $args['exclude_post_types'];
        foreach( get_post_types( $args['get_post_type_args'], 'objects' ) as $id => $post_type ) {
            if( in_array( $id, $excluded ) ) {
                continue;
            }
            $post_types[ $id ] = __( $post_type->labels->name );
        }
        
        return $post_types;
    }
}