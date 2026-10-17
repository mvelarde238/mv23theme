<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;

class Post_Content extends Component {

    public function __construct() {
		parent::__construct(
			'post-content',
			__( 'Post Content', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template')
		);
    }

    public static function get_icon() {
        return 'bi-blockquote-left';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', 'post_content_message')->hide_label()
                ->set_description(__('This component displays the post content saved in the classic WordPress editor.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        global $post;
        $post_id = isset($args['post_id']) ? $args['post_id'] : $post->ID;

        ob_start();
        // if ( isset($args['post_id']) ) {
            // show a placeholder in the builder
            // echo '<p class="center-align">' . __('This is a placeholder for the content area. It will display the post content in the frontend.', 'mv23theme') . '</p>';
        // } else{
            // render content on frontend
            $content = get_post_field('post_content', $post_id);

            if (!empty($content)) {
                echo '<div class="component">' . do_shortcode(wpautop(oembed( $content ))) . '</div>';
            }
        // }
        return ob_get_clean();
    }
}

new Post_Content();