<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Ultimate_Fields\Location\Post_Type;
use WP_Query;
use Core\Frontend\Page;
use Core\Builder\Component\Main_Content;

class Single_Template {
	
	private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Single_Template();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $single_template = new CPT(
            'single_template',
            array(
				'show_in_menu'        => 'theme-options-menu',
                'show_in_nav_menus'   => false,
                'supports'            => array('title','revisions'),
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'menu_icon'           => 'dashicons-editor-table'
            )
        );

        $single_template->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'single_template_data' => __('Data','mv23theme'),
            'date' => __('Date')
        ));

        $single_template->populate_column('single_template_data', array($this, 'handle_single_template_data_admin_column'));
	}

    public function handle_single_template_data_admin_column($column_name, $post) {
        if ($column_name === 'single_template_data') {
            $connected_posttype = get_post_meta($post->ID, 'connected_posttype', true);
            $connected_taxonomy = get_post_meta($post->ID, 'connected_'.$connected_posttype.'_taxonomy', true);
            $connected_terms = get_post_meta($post->ID, 'connected_'.$connected_taxonomy.'_terms', true);

            // Resolve human-readable post type label
            $posttype_obj = $connected_posttype ? get_post_type_object($connected_posttype) : null;
            $posttype_label = $posttype_obj ? $posttype_obj->labels->name : ($connected_posttype ?: __('any post type', 'mv23theme'));

            if (!$connected_posttype) {
                echo '<em>' . __('This template is used for all post types.', 'mv23theme') . '</em>';
                return;
            }

            if (!$connected_taxonomy) {
                /* translators: %s: post type label */
                echo '<em>' . sprintf(__('This template is used for all <strong>%s</strong> posts.', 'mv23theme'), esc_html($posttype_label)) . '</em>';
                return;
            }

            // Resolve human-readable taxonomy label
            $taxonomy_obj = get_taxonomy($connected_taxonomy);
            $taxonomy_label = $taxonomy_obj ? $taxonomy_obj->labels->singular_name : $connected_taxonomy;

            if (is_array($connected_terms) && !empty($connected_terms)) {
                // Resolve term names
                $term_names = array();
                foreach ($connected_terms as $term_id) {
                    $term = get_term($term_id, $connected_taxonomy);
                    $term_names[] = (!is_wp_error($term) && $term) ? esc_html($term->name) : esc_html($term_id);
                }
                /* translators: 1: post type label, 2: taxonomy label, 3: comma-separated term names */
                echo '<em>' . sprintf(
                    __('This template is used for <strong>%1$s</strong> posts in the %2$s: <strong>%3$s</strong>.', 'mv23theme'),
                    esc_html($posttype_label),
                    esc_html($taxonomy_label),
                    implode(', ', $term_names)
                ) . '</em>';
            } else {
                /* translators: 1: post type label, 2: taxonomy label */
                echo '<em>' . sprintf(
                    __('This template is used for <strong>%1$s</strong> posts with any <strong>%2$s</strong>.', 'mv23theme'),
                    esc_html($posttype_label),
                    esc_html($taxonomy_label)
                ) . '</em>';
            }
        }
    }

	public static function get_fields(){
		$single_template_fields = array();
		$single_template_fields[] = Field::create( 'tab', 'content_tab', __('Content Type','mv23theme') );

		# Add post types
		$post_types = array();
		$excluded = array( 'attachment', 'page', 'product' );
		foreach( get_post_types( array('public'=>true, 'exclude_from_search'=>false), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}
			$post_types[ $id ] = __( $post_type->labels->name );
		}
		$default_connected_posttype = get_post_meta( $_GET['post'] ?? null, 'connected_posttype', true );
		$single_template_fields[] = Field::create( 'radio', 'connected_posttype' )
			->set_orientation( 'horizontal' )
			->set_default_value( $default_connected_posttype ? $default_connected_posttype : 'post' )
			->add_options($post_types);
		
		# Add taxonomies
		foreach ($post_types as $post_type_id => $post_type_name) {
			$taxonomies = array( '' => __('Any','mv23theme') );
			foreach( get_taxonomies( array( 'object_type' => array($post_type_id), 'show_ui' => true ), 'objects' ) as $slug => $taxonomy ) {
				$taxonomies[$slug] = $taxonomy->labels->name;
			}
			$default_connected_taxonomy = get_post_meta( $_GET['post'] ?? null, 'connected_'.$post_type_id.'_taxonomy', true );
			$single_template_fields[] = Field::create( 'radio', 'connected_'.$post_type_id.'_taxonomy' )
				->set_orientation( 'horizontal' )
				->set_default_value( $default_connected_taxonomy ? $default_connected_taxonomy : '' )
				->add_dependency( 'connected_posttype', $post_type_id, '=' )
				->add_options($taxonomies);

			# Add terms
			foreach ($taxonomies as $tax_slug => $tax_name) {
				if( !empty($tax_slug) ){
					$default_connected_terms = get_post_meta( $_GET['post'] ?? null, 'connected_'.$tax_slug.'_terms', true );
					$single_template_fields[] = Field::create( 'multiselect', 'connected_'.$tax_slug.'_terms', 'Connected '.$tax_name.' terms' )
						->add_terms( $tax_slug )
						->set_default_value( $default_connected_terms )
						->add_dependency( 'connected_posttype', $post_type_id, '=' )
						->add_dependency( 'connected_'.$post_type_id.'_taxonomy', $tax_slug, '=' );
				}
			}
		}

		return $single_template_fields;
	}

	public function add_meta_boxes(){
		$single_template_location = new Post_Type();
		$single_template_location->add_post_type( 'single_template' );
		$single_template_location->context = 'side';
		$single_template_fields = self::get_fields();

		Container::create( 'single_template_settings' )
		    ->set_title('Single Template Settings')
		    ->add_location( $single_template_location )
		    ->add_fields($single_template_fields);
	}

	/**
	 * Get single post type
	 */
	public function get_single_post_type(){
		$post_type = get_post_type();	
		return $post_type;
	}

	/**
	 * Get single template page ID in single.php
	 */
	public function get_single_template_id() {
		$is_connected = 0;
		$posttype = self::$instance->get_single_post_type();

		$args = array(
			'post_type' => 'single_template',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => 'connected_posttype',
					'value' => $posttype,
					'compare' => '='
				)
			)
		);
		$loop = new WP_Query( $args );
		$posts = $loop->posts;

		if( !empty($posts) ){
			// Track matches by specificity level (higher = more specific wins)
			$match_by_terms    = 0; // level 3: connected to specific terms
			$match_by_taxonomy = 0; // level 2: connected to any term of a taxonomy
			$match_by_posttype = 0; // level 1: connected to the post type with any taxonomy

			$current_post_id = get_queried_object_id();

			foreach ($posts as $post_id) {
				$connected_taxonomy = get_post_meta($post_id, 'connected_'.$posttype.'_taxonomy', true);
				if( empty($connected_taxonomy) ){
					// level 1: matches any post of this post type
					if( !$match_by_posttype ) $match_by_posttype = $post_id;
				} else {
					$connected_terms = get_post_meta($post_id, 'connected_'.$connected_taxonomy.'_terms', true);
					$post_terms = wp_get_post_terms( $current_post_id, $connected_taxonomy, array( 'fields' => 'ids' ) );
					if( is_array($connected_terms) && !empty($connected_terms) ){
						// level 3: matches only if the current post has one of the connected terms
						if( !$match_by_terms && is_array($post_terms) && !empty( array_intersect( array_map('intval', $connected_terms), $post_terms ) ) ){
							$match_by_terms = $post_id;
						}
					} else {
						// level 2: matches if the current post has any term of the connected taxonomy
						if( !$match_by_taxonomy && is_array($post_terms) && !empty($post_terms) ){
							$match_by_taxonomy = $post_id;
						}
					}
				}
			}

			// Return the most specific match found
			$is_connected = $match_by_terms ?: ( $match_by_taxonomy ?: $match_by_posttype );
		}

		return $is_connected;
	}

    public function display(){
        $single_template_id = self::$instance->get_single_template_id();
        if( $single_template_id ){
            $page = new Page();
		    $page_content = $page->the_content( $single_template_id );
	        if (!empty($page_content)) {
                echo $page_content;
            }
        } else {
            // fallback to show the default content if no single template is connected to the current post type
			$default_content = apply_filters('filter_default_single_content', array(
                'components' => array(
                    array( 
                        'type' => 'main', 
                        'components' => array(
                            array( 'type' => 'post-title' ),
                            array( 'type' => 'post-content' )
                        )
                    ),
                    array( 
                        'type' => 'aside',
                        'components' => array(
                            array( 'type' => 'sidebar' )
                        )
                    )
                )
            ));
			echo Main_Content::display($default_content);
        }
    }

	/**
	 * Redirect single single_template to connected post type single
	 */
	function redirect_single() {
		if( !is_singular( 'single_template' ) ) return;

		$single_template_id = get_the_ID();
		$redirect_to = null;

		$connected_posttype = get_post_meta($single_template_id, 'connected_posttype', true);
		if ($connected_posttype) {
			// get some post of the connected post type to find its single URL
			$args = array(
				'post_type' => $connected_posttype,
				'posts_per_page' => 1,
				'fields' => 'ids'
			);
			$loop = new WP_Query($args);
			if ($loop->have_posts()) {
				$connected_post_id = $loop->posts[0];
				$redirect_to = get_permalink($connected_post_id);
			}
		}

		if ($redirect_to) {
			// if isset ub_preview, add it to the redirect URL to allow previewing the single template
			if( isset($_GET['ub_preview']) ) {
				$redirect_to = add_query_arg( 'ub_preview', $_GET['ub_preview'], $redirect_to );
			}

			wp_redirect( $redirect_to );
			exit;
		}
	}
}