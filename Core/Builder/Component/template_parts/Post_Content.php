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
        $post_id = isset($args['post_id']) ? $args['post_id'] : $post->ID;

        $classic_editor_content = get_post_field('post_content', $post_id);
        ob_start();
        if (!empty($classic_editor_content)) {
            echo '<div class="component text-editor">' . do_shortcode(wpautop(oembed( $classic_editor_content ))) . '</div>';
        }

        // Render the components inside the gjs-page-content wrapper
        $page_content = get_post_meta( $post_id, 'page_content', true );
        $page_content_datastore = get_post_meta( $post_id, 'page_content_datastore', true );
        $compiled_css = Page::compile_styles_to_css( is_array($page_content) ? ($page_content['styles'] ?? []) : [] );
        $page_content = Page::consolidate_content( $page_content, $page_content_datastore );
        $compiled_css .= Page::compile_components_custom_css( $page_content );
    
        if (is_array($page_content)) :
            $wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
            if ( $wrapper['type'] === 'wrapper' ){
                $page_content_comp = null;
                foreach ( $wrapper['components'] as $component ) {
                	if ( $component['type'] === 'page-content' ) {
                		$page_content_comp = $component;
                		break;
                	}
                }
				$components_to_render = $page_content_comp['components'] ?? [];
				
				if (is_array($components_to_render) && !empty($components_to_render)) :
					if ( !empty($compiled_css) ) echo '<style>' . $compiled_css . '</style>';
				
					foreach ($components_to_render as $component) :
						echo Template_Engine::getInstance()->handle( $component );
					endforeach;
				endif;
            }
    	endif;

        return ob_get_clean();
    }
}

new Post_Content();