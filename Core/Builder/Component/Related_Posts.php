<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Builder\Component\Listing;
use Core\Theme_Options\Theme_Options;

class Related_Posts extends Component {

    public function __construct() {
		parent::__construct(
			'related-posts',
			__( 'Related Posts', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'block_category' => 'Template Parts',
            'posttypes' => array('single_template')
		);
    }

    public static function get_icon() {
        return 'bi-link-45deg';
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args = array()) {        
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
            'query_params' => array(
                'posts_per_page' => 5,
                'orderby' => 'rand',
            ),
            'columns' => LISTING_COLUMNS,
            'columns_gap' => LISTING_GAP,
            'post_template' => $post_type,
            'listing_template' => 'carousel',
            'carousel_settings' => array(
                'show_controls' => true
            ),
            'posttype' => $post->post_type,
        ), $post->ID);

        ob_start();
        echo '<div class="related-posts component">';
        printf('<h4 class="related-posts__title">%s</h4>', $title);
        echo Listing::display($related_posts_args);
        echo '</div>';
        return ob_get_clean();
    }
}

new Related_Posts();