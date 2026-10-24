<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Frontend\Page;
use Core\Posttype\Single_Template as Single_Template_Posttype;

class Single_Template extends Component {

    public function __construct() {
		parent::__construct(
			'single-template',
			__( 'Single Template', 'mv23theme' ),
			array(
				'common_settings' => array()
			)
		);
    }
        
    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
        );
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display( $args ){
        if( is_admin() && isset( $args['post_id'] ) ){
            global $post;
            $context_post_id = $args['post_id'];
            $posttype = get_post_type( $context_post_id );
            $single_template_id = Single_Template_Posttype::getInstance()->get_single_template_id( $posttype );

            $previous_post = $post;
            $post = get_post( $context_post_id );
            setup_postdata( $post );

            $page = new Page();
            $page_content = $page->the_content( $single_template_id );

            $post = $previous_post;
            if ( $previous_post ) setup_postdata( $previous_post ); else wp_reset_postdata();

            return $page_content;
        }
    }
}

new Single_Template();