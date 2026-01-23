<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Builder\Component\Listing;

class Related_Posts extends Component {

    public function __construct() {
		parent::__construct(
			'related_posts',
			__( 'Related Posts', 'mv23theme' )
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

    public static function display($args){
        global $post;
        if( isset($args['post_id']) ) {
            $post = get_post( $args['post_id'] );
        }

        $post_type = get_post_type( $post->ID );
        $post_type_name = get_post_type_object( $post_type )->labels->name;
        $title = sprintf( __( 'Related %s', 'mv23theme' ), $post_type_name );

        // filter the related posts arguments
        $related_posts_args = apply_filters('filter_related_'.$post->post_type.'_args', array(
            'show' => 'auto',
            'post__not_in' => array($post->ID),
            'qty' => 5,
            'items_in_desktop' => 3,
            'items_in_laptop' => 3,
            'items_in_tablet' => 3,
            'items_in_mobile' => 1,
            'd_gap' => 30,
            'l_gap' => 30,
            't_gap' => 30,
            'm_gap' => 15,
            'post_template' => $post_type,
            'list_template' => 'carousel',
            'show_controls' => 1,
            'posttype' => $post->post_type,
        ), $post->ID);

        ob_start();
        echo '<div class="related-posts">';
        printf('<h4 class="related-posts__title">%s</h4>', $title);
        echo Listing::display($related_posts_args);
        echo '</div>';
        return ob_get_clean();
    }
}

new Related_Posts();