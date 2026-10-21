<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
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
            'posttypes' => array('single_template'),
            'custom_datastore_change_callback' => true
		);
    }

    public static function get_icon() {
        return 'bi-link-45deg';
    }

	public static function get_fields() {
		$fields = array();

        # Add listing fields
		$listing_fields = Listing::get_fields();
		$exclude = ['posttype','woocommerce_key','tax_queries','tax_queries_section','pagination_scrolltop'];
		foreach ( $listing_fields as $field ) {
            $field_name = $field->get_name();
			
            if( in_array( $field_name, $exclude ) ) continue;
            if( $field_name === 'listing_template' ){
                $field->set_default_value('carousel');
            }

			$fields[] = $field;
		}

		return $fields;
	}

    public static function display($args = array()) {
        if( isset($args['post_id']) ) {
            // In the builder use the specific post ID provided in the arguments
            $post = get_post( $args['post_id'] );
        } else {
            // On the frontend, use the global post object
            global $post;
        }

        $post_type = get_post_type( $post->ID );
        $post_type_name = get_post_type_object( $post_type )->labels->name;

        $custom_listing_args = array(
            'show' => 'auto',
            'posttype' => $post->post_type,
        );
        $listing_args = array_merge($custom_listing_args, $args);

        // Merge custom query arguments with the provided arguments
        $custom_query_parameters = array(
            array( '__type' => 'posts_per_page', 'value' => 3 ),
            array( '__type' => 'orderby', 'value' => 'rand' ),
            array( '__type' => 'post__not_in', 'value' => array($post->ID) ),
        );
        $listing_args['query_parameters'] = array_merge($custom_query_parameters, $listing_args['query_parameters'] ?? array());

        // Automatically include taxonomy terms of the current post as query params for related posts
        $taxonomies = get_object_taxonomies( get_post_type( $post->ID ) );

        // fix for polylang plugin, which creates taxonomies that we don't want to use for related posts:
        $excluded_taxonomies = array('post_translations');

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                $posttype__taxonomy = $post_type . '--' . $taxonomy;
                if( !in_array($taxonomy, $excluded_taxonomies) ) {
                    $listing_args['tax_queries'][] = array(
                        '__type' => 'taxgroup__' . $post_type . '__' . $taxonomy,
                        'terms' => wp_list_pluck( $terms, 'term_id' ),
                        'include_children' => true,
                        'operator' => 'IN',
                    );
                }
			}
		}

        // filter the related posts arguments
        $related_posts_args = apply_filters('filter_related_'.$post->post_type.'_args', $listing_args, $post->ID);

        ob_start();
        echo '<div class="related-posts component">';
        echo Listing::display($related_posts_args);
        echo '</div>';
        return ob_get_clean();
    }
}

new Related_Posts();