<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Comments_Area extends Component {

    public function __construct() {
		parent::__construct(
			'comments-area',
			__( 'Comments Area', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'block_category' => 'Template Parts',
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
        ob_start();
        // show a placeholder in the builder
        if ( isset($args['post_id']) ) {
            echo '<p class="center-align">' . __('This is a placeholder for the comments area component. It will display comments in the frontend.', 'mv23theme') . '</p>';
        }

        // render comments template on frontend
        if ( comments_open() || get_comments_number() ) :
            echo '<div class="comments-area component">';
            comments_template();
            echo '</div>';
        endif;
        return ob_get_clean();
    }
}

new Comments_Area();