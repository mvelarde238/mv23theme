<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core as Builder_Core;
use Core\Frontend\Page;

class Postcard {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Postcard();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        new CPT(
            array(
                'post_type_name' => 'postcard',
                'singular' => __('Postcard', 'mv23theme'),
                'plural' => __('Postcards', 'mv23theme')
            ), 
            array(
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'exclude_from_search' => true,
                'supports' => array('title','revisions')
            )
        );
    }

    public function add_meta_boxes(){
        $fields = array();

		# Add post types
		$default_connected_posttype = get_post_meta( $_GET['post'] ?? null, 'connected_posttype', true );
		$fields[] = Field::create( 'radio', 'connected_posttype' )
            ->set_description( __('Choose a post type to determine which post will be used as a preview when editing this postcard in the builder.', 'mv23theme') )
			->set_orientation( 'horizontal' )
			->set_default_value( $default_connected_posttype ? $default_connected_posttype : 'post' )
			->set_options_callback( function() {
                return Builder_Core::get_post_types(array(
                    'get_post_type_args' => array( 'public'=>true, 'exclude_from_search'=>false ),
                ));
            });

        # Add page content field
        $fields[] = Field::create( 'ultimate_builder', 'page_content', __('Content','mv23theme') )
            ->add_groups( Builder_Core::getInstance()->get_groups_for_builder() );
		
		Container::create( 'postcard_settings' )
            ->add_location( 'post_type', 'postcard' )
            ->set_description_position('label')
		    ->set_title('Postcard Settings')
		    ->add_fields($fields);
    }

    public function get_postcards(){
        $postcards = array();
        
        $postcards_query = get_posts( array('post_type' => 'postcard','posts_per_page' => -1, 'post_status' => 'publish') );

        for ($i=0; $i < count($postcards_query); $i++) { 
	        $postcards['postcard_'.$postcards_query[$i]->ID] = $postcards_query[$i]->post_title;
        };

        return $postcards;
    }

    public function get_data( $post_id ){
        $container = null;

        $page_content = get_post_meta( $post_id, 'page_content', true );
        $page_content_datastore = get_post_meta( $post_id, 'page_content_datastore', true );
        $page_content = Page::consolidate_content( $page_content, $page_content_datastore );

        if ( is_array( $page_content ) ) :

            $wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
            if ( $wrapper['type'] === 'wrapper' ){
                foreach ( $wrapper['components'] as $component ) {
                    if ( $component['type'] === 'container' ) {
                        $container = $component;
                        break;
                    }
                }
            }
        endif;

        $content = $container;
        $styles  = Page::compile_styles_to_css( $page_content['styles'] ?? [] );
        $styles .= Page::compile_components_custom_css( $page_content );

        return array(
            'component' => $content['components'][0],
            'styles'  => $styles,
            'has_content' => ( is_array( $content ) && count( $content['components'] ) > 0 ) ? true : false
        );
    }
}