<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Frontend\Page;
use Core\Builder\Template_Engine;

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
        $post_id = isset($args['post_id']) ? $args['post_id'] : ($post ? $post->ID : 0);
        
        if( !$post_id ){
            ob_start();
            echo '<p class="component">' . __('No post selected for Posts_Content', 'mv23theme') . '</p>';
            return ob_get_clean();
        }

        $classic_editor_content = get_post_field('post_content', $post_id);
        ob_start();
        if (!empty($classic_editor_content)) {
            echo '<div class="component text-editor">' . do_shortcode(wpautop(oembed( $classic_editor_content ))) . '</div>';
        }
        return ob_get_clean();
    }
}

new Post_Content();