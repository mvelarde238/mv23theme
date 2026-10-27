<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Comments_Area extends Component {

    public function __construct() {
		parent::__construct(
			'comments-area',
			__( 'Comments Area', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template')
		);
    }

    public static function get_icon() {
        return 'bi-chat-left-text';
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args = array()) {
        
        // show a placeholder in the builder
        if ( isset($args['post_id']) ) {
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            echo '<p class="center-align">' . __('This is a placeholder for the comments area component. It will display comments in the frontend.', 'mv23theme') . '</p>';
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }

        // render comments template on frontend
        if ( comments_open() || get_comments_number() ) :
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            comments_template();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        endif;
    }
}

new Comments_Area();