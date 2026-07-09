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
		$exclude = ['posttype','woocommerce_key','tax_params','status_params','pagination_scrolltop'];
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
        global $post;

        if( isset($args['post_id']) ) {
            // Is builder context with a specific post ID provided
            $post = get_post( $args['post_id'] );

            if( isset( $args['post_type'] ) && $args['post_type'] === "single_template" ) {
                // If it's a single_template, try to resolve the connected post type and use a sample post of that type
                $connected_posttype = get_post_meta( $args['post_id'], 'connected_posttype', true );
                if ( $connected_posttype ) {
                    $sample = get_posts( array(
                        'post_type'      => $connected_posttype,
                        'posts_per_page' => 1,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'fields'         => 'ids',
                    ) );
                    if ( ! empty( $sample ) ) {
                        $post = get_post( $sample[0] );
                    }
                }
            }
        }

        $post_type = get_post_type( $post->ID );
        $post_type_name = get_post_type_object( $post_type )->labels->name;

        $custom_listing_args = array(
            'show' => 'auto',
            'post__not_in' => array($post->ID),
            'query_params' => array(
                'posts_per_page' => 3,
                'orderby' => 'rand',
            ),
            'posttype' => $post->post_type,
        );
        $listing_args = array_merge($custom_listing_args, $args);

        // Automatically include taxonomy terms of the current post as query params for related posts
        $taxonomies = get_object_taxonomies( get_post_type( $post->ID ) );

        // fix for polylang plugin, which creates taxonomies that we don't want to use for related posts:
        $excluded_taxonomies = array('post_translations');

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                $posttype__taxonomy = $post_type . '--' . $taxonomy;
                if( !in_array($taxonomy, $excluded_taxonomies) ) {
                    $listing_args['tax_params'][$posttype__taxonomy] = wp_list_pluck( $terms, 'term_id' );
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