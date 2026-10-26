<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Frontend\Page;
use Core\Posttype\Single_Template as Single_Template_Posttype;
use Core\Builder\Component\Section;

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
        // Only display the single template content in the admin context with a valid post ID
        if( is_admin() && isset( $args['post_id'] ) ){
            $context_post_id = $args['post_id'];
            $posttype = get_post_type( $context_post_id );
            $single_template_id = Single_Template_Posttype::getInstance()->get_single_template_id( $posttype );

            ob_start();
            if( $single_template_id ){
                $page = new Page();
                $page_content = $page->the_content( $single_template_id );
                echo $page_content;
            } else {
                // fallback to show the default content if no single template is connected to the current post type
			    $default_content = array(
                    'components' => array(
                        array( 
			    			'type' => 'container',
			    			'components' => array(
			    				array( 
			    					'type' => 'main-content',
			    					'template' => 'main-content--sidebar-right',
			    					'components' => array(
			    						array( 
			    							'type' => 'main',
			    							'components' => array(
			    								array( 'type' => 'post-title' ),
			    								array( 'type' => 'post-content' ),
                                                array( 'type' => 'editable-area-slot', 'slot_id' => 'main-slot' )
			    							)
			    						),
			    						array( 
			    							'type' => 'aside',
			    							'components' => array(
			    								array( 'type' => 'sidebar' )
			    							)
			    						)
			    					)
			    				)
			    			)
			    		)
                    )
                );
			    echo Section::display($default_content);
            }
            $output = ob_get_clean();
            return $output;
        }
    }
}

new Single_Template();