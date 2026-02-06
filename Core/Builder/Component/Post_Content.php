<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
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
            'display_gjs_block' => false
		);
    }

    public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args = array()){
        global $post;
        $post_id = isset($args['post_id']) ? $args['post_id'] : $post->ID;

        $content = get_post_field('post_content', $post_id);
        if (!empty($content)) {
            ob_start();
            echo '<div class="component">' . do_shortcode(wpautop(oembed( $content ))) . '</div>';
            return ob_get_clean();
        }
    }
}

new Post_Content();