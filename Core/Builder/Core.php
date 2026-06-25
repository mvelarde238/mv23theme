<?php
namespace Core\Builder;

use Core\Builder\Component\Components_Wrapper;
use Ultimate_Fields\Container;
use Core\Builder\Template_Engine;

define ('BUILDER_DIR', __DIR__);
define ('BUILDER_PATH', get_template_directory_uri() . '/Core/Builder');

class Core{
	private static $instance = null;

    private static $components = array();

    private static $popup_containers = array(
        'actions',
        'common_settings',
        'scroll_animations',
        'visibility',
    );

    /**
     * Holds the list of components class names that will be registered in the Builder
     */
    private static $core_components = array(
        'structure' => array(
            'Page',
            'Container',
            'Section',
            'Row',
            'Column',
            'Components_Wrapper',
            'Inner_Wrapper',
            'Template_Placeholder',
        ),
        'content' => array(
            'Text_Editor',
            'Heading',
            'Figure',
            'Image',
            'Video',
            'Button',
            'Spacer',
            'Icon_Box',
            'Icon_and_Text',
            'Icon_List',
            'Gallery',
            'Listing',
            'Menu',
            'Flip_Box',
            'Flip_Box_Front',
            'Flip_Box_Back',
            'Carousel_Controls',
            'Carousel',
            'Inner_Accordion',
            'Accordion_Button',
            'Accordion_Item',
            'Accordion',
            'Inner_Row',
            'Map',
            'Shortcode',
            'Code',
            'Testimonial',
            'Testimonial_Header',
            'Counter',
        ),
        'theme' => array(),
        'wrappers' => array(),
        'template_parts' => array(
            'Main_Content',
            'Main',
            'Aside',
            'Archive_Title',
            'Archive_Posts',
            'Sidebar',
            'Post_Title',
            'Post_Content',
            'Social_Share',
            'Related_Posts',
            'Comments_Area',
            'Breadcrumbs',
        ),
        'oce' => array(
            'OCE_Modal_Content',
            'OCE_Dynamic_Content',
            'Offcanvas_Element',
        ),
        'core' => array(
            'Theme_Options',
            'Global_Styles',
            'Header',
            'Header_Logo',
            'Header_Preview',
            'Footer',
            'Footer_Preview',
        ),
        'postcard' => array(
            'Postcard',
            'Featured_Media',
            'Postcard_Trigger',
        ),
    );

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){}

    public function set_initial_values(){
        // Set default values for builder options if they don't exist
        $builder_posttypes = get_option('builder_posttypes');
        if ( $builder_posttypes === false ) {
            add_option('builder_posttypes', DEFAULT_BUILDER_POSTTYPES);
        }
    }

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
        $builtin_posttypes = array( 
            'offcanvas_element', 'postcard', 'header', 
            'footer', 'single_template', 'archive_template', 
            'reusable_section', 'megamenu' 
        );

		if ( in_array( $post_type, $builder_posttypes ) || in_array( $post_type, $builtin_posttypes ) ) {
			$builder_url = add_query_arg( array(
				'action' => 'ultimate-builder',
				'meta'   => 'page_content',
			), get_edit_post_link( $post->ID, 'raw' ) );

			$actions['ultimate_builder'] = '<a href="' . esc_url( $builder_url ) . '"><b>' . __( 'Edit with Ultimate Builder', 'mv23theme' ) . '</b></a>';
		}

		return $actions;
	}

    public function add_meta_boxes(){
        require_once( BUILDER_DIR.'/containers/page-content.php' );

        // load containers that wil be generated in a pop up:
        foreach (self::$popup_containers as $container) {
            require_once( BUILDER_DIR.'/containers/'.$container.'.php' );
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
			if( in_array( str_replace('_container', '', $container_id), self::$popup_containers) ) {
				$popup_containers[$container_id] = $container->export_fields_settings();
				// Enqueue scripts for all fields inside each popup container so that
				// field-type-specific scripts (e.g. uf-field-date, uf-field-time) are
				// available when the popup renders its fields in JS.
				$container->enqueue_scripts();
			}
		}
		wp_localize_script( 'uf-field-common-settings-control', 'POPUP_CONTAINERS', $popup_containers);
    }

    public function init_components(){
        foreach( self::$core_components as $category => $components_group ) {
            do_action('theme_init_components_before_'.$category.'_category');

            foreach ($components_group as $componentName) {
                // Try category subfolder first, then fall back to flat Component/ directory.
                // The class file must keep namespace Core\Builder\Component regardless of location.
                $category_path = 'Core/Builder/Component/' . $category . '/' . $componentName . '.php';
                $flat_path     = 'Core/Builder/Component/' . $componentName . '.php';

                if( locate_template( $category_path ) ) {
                    locate_template( $category_path, true, true );
                } else {
                    locate_template( $flat_path, true, true );
                }
            }

            do_action('theme_init_components_after_'.$category.'_category');
        }

        do_action('theme_init_components');
    }

    public static function register_component( $component, $class_name ){
        $namespace = 'Core\Builder\Component\\';
        $class_name = str_replace($namespace,'',$class_name);

        // determine the category of the component, default to 'content' if not set
        $category = 'content';
        $builder_data = $component->get_builder_data();
        if( isset($builder_data['block_category']) && !empty($builder_data['block_category']) ){
            $category = $builder_data['block_category'];
        } else {
            // if the category is not set in the builder data, we can try to find it in the core components list
            foreach( self::$core_components as $cat => $components_group ) {
                if ( in_array( $class_name, $components_group ) ) {
                    $category = $cat;
                    break;
                }
            }
        }
        // add category to builder data to be used in the templates
        $component->set_builder_data( array_merge( $builder_data, array('block_category' => $category) ) );
        
        self::$components[$category][] = $component;
    }

    public function get_components(){
        $_components = array();

        foreach( self::$components as $category => $components_group ) {
            foreach ( $components_group as $component) {
                $_components[] = $component;
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
     * for async components rendering
     * 
     * apply_filters & filters_to_apply: flags to apply filters before getting the component view
     * used on theme options data changes to imitate the Customizer behavior
     * 
     */
    public function ajax_get_component_view(){
        if ( isset( $_REQUEST['apply_filters'] ) && isset($_REQUEST['filters_to_apply'] ) ) {
            if ( is_array( $_REQUEST['filters_to_apply'] ) ) {
                foreach ( $_REQUEST['filters_to_apply'] as $filter ) {
                    add_filter( $filter['name'], function() use ( $filter ) {
                        return $filter['value'];
                    } );
                }
            }
        }

        if( isset( $_REQUEST['type'] ) ) {
            $component_view = Template_Engine::getInstance()->handle( $_REQUEST );
            $result = $component_view ? $component_view : '';
            wp_send_json_success($result);
        } else {
            wp_send_json_success('--ajax response--');
        }
    }

    /**
    * Get post types excluding specific ones
    * 
    * @return array
    */
    public static function get_post_types( $args = array() ) {
        $default_args = array(
            'exclude_post_types' => array( 'offcanvas_element','attachment','templates_library','reusable_section','megamenu','footer' ),
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

    public function get_component_datastore( $component ){
        $post_id = (isset($component['__post_id']) && $component['__post_id']) ? $component['__post_id'] : get_the_ID();
        $content_datastore = get_post_meta( $post_id, 'page_content_datastore', true );
        $__id = $component['__id'] ?? null;
        $datastore = (is_array($content_datastore) && isset($content_datastore[$__id])) ? 
            $content_datastore[$__id] :
            array();
        return $datastore;
    }
}