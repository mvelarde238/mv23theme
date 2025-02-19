<?php
namespace Core\Posttype;

use Core\Builder\Component;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;
use Core\Utils\CPT;
use Core\Builder\Content_Selector;

class Reusable_Section_CPT {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Reusable_Section_CPT();

			add_action( 'uf.init', array( self::$instance, 'add_metabox' ) );
            add_action( 'theme_init_components', function(){
                new Reusable_Section();
            });
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        new CPT(
            array(
                'post_type_name' => 'reusable_section',
                'singular' => __('Reusable Section', 'mv23theme'),
                'plural' => __('Reusable Sections', 'mv23theme')
            ), 
            array(
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'exclude_from_search' => true,
                'supports' => array('title','page-attributes','revisions')
            )
        );
    }

    public function get_reusable_sections(){
        $reusable_sections = array( '0'=>__('Choose','mv23theme') );
        
        $sections = get_posts( array('post_type' => 'reusable_section','posts_per_page' => -1, 'post_status' => 'publish') );

        for ($i=0; $i < count($sections); $i++) { 
	        setup_postdata( $sections[$i] );
	        $reusable_sections[$sections[$i]->ID] = $sections[$i]->post_title;
        };
        wp_reset_postdata();

        return $reusable_sections;
    }

    public function add_metabox(){
        Container::create( 'content' )
            ->add_location( 'post_type', 'reusable_section')
            ->set_layout( 'grid' )
            ->set_style( 'seamless' )
            ->add_fields(array(
                Content_Selector::the_field()
            ));
    
        /**
         * Add reusable section in page modules repeater
         */
        if( USE_REUSABLE_SECTIONS_AS_PAGE_MODULE ){
            foreach( Container::get_registered() as $container ) {
                if( $container->get_id() == 'page_content' ) {
                    $container_fields = $container->get_fields();
                    foreach ($container_fields as $field) {
                        if ($field->get_name() === 'page_modules'){
                            $field->add_group( Reusable_Section::the_group() );
                        }
                    }
                }
            };
        }
    }
}

class Reusable_Section extends Component{

    /**
     * Holds the repeater group instantiated by the parent constructor
     */
    private static $the_group;

    public function __construct() {
		self::$the_group = parent::__construct(
			'reusable_section',
			__( 'Reusable Section', 'mv23theme' ),
            array(
                'add_common_settings' => false
            )
		);
	}

    public static function the_group() {
        return self::$the_group;
    }

    public static function get_icon() {
        return 'dashicons dashicons-welcome-widgets-menus';
    }

    public static function get_fields() {
        $fields = array( 
            Field::create( 'select', 'reusable_section', __('Select', 'mv23theme') )->add_options( Reusable_Section_CPT::getInstance()->get_reusable_sections() )
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
        $components = get_post_meta( $args['reusable_section'],'components', true);

        if (is_array($components) && count($components) > 0) :
            foreach ($components as $component) {
                echo Template_Engine::getInstance()->handle( $component['__type'], $component );
        	}
        endif;
    }
}