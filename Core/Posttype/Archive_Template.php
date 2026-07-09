<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Ultimate_Fields\Location\Post_Type;
use WP_Query;
use Core\Frontend\Page;
use Core\Builder\Component\Main_Content;
use Core\Builder\Core as Builder_Core;

class Archive_Template {
	
	private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Archive_Template();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $archive_template = new CPT(
            'archive_template',
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
				'menu_icon'           => 'dashicons-editor-table',
				'public'              => false,
                'show_ui'             => true,
                'show_in_admin_bar'   => false,
            )
        );

        $archive_template->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'archive_template_data' => __('Data','mv23theme'),
            'date' => __('Date')
        ));

        $archive_template->populate_column('archive_template_data', array($this, 'handle_archive_template_data_admin_column'));
	}

    public function handle_archive_template_data_admin_column($column_name, $post) {
        if ($column_name === 'archive_template_data') {
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
                echo '<em>' . sprintf(__('This template is used for all <strong>%s</strong> archives.', 'mv23theme'), esc_html($posttype_label)) . '</em>';
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
                    __('This template is used for <strong>%1$s</strong> archives in the %2$s: <strong>%3$s</strong>.', 'mv23theme'),
                    esc_html($posttype_label),
                    esc_html($taxonomy_label),
                    implode(', ', $term_names)
                ) . '</em>';
            } else {
                /* translators: 1: post type label, 2: taxonomy label */
                echo '<em>' . sprintf(
                    __('This template is used for <strong>%1$s</strong> archives with any <strong>%2$s</strong>.', 'mv23theme'),
                    esc_html($posttype_label),
                    esc_html($taxonomy_label)
                ) . '</em>';
            }
        }
    }

	public static function get_fields(){
		$archive_template_fields = array();
		$archive_template_fields[] = Field::create( 'tab', 'content_tab', __('Content Type','mv23theme') );

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
		$archive_template_fields[] = Field::create( 'radio', 'connected_posttype' )
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
			$archive_template_fields[] = Field::create( 'radio', 'connected_'.$post_type_id.'_taxonomy' )
				->set_orientation( 'horizontal' )
				->set_default_value( $default_connected_taxonomy ? $default_connected_taxonomy : '' )
				->add_dependency( 'connected_posttype', $post_type_id, '=' )
				->add_options($taxonomies);

			# Add terms
			foreach ($taxonomies as $tax_slug => $tax_name) {
				if( !empty($tax_slug) ){
					$default_connected_terms = get_post_meta( $_GET['post'] ?? null, 'connected_'.$tax_slug.'_terms', true );
					$archive_template_fields[] = Field::create( 'multiselect', 'connected_'.$tax_slug.'_terms', 'Connected '.$tax_name.' terms' )
						->add_terms( $tax_slug )
						->set_default_value( $default_connected_terms )
						->add_dependency( 'connected_posttype', $post_type_id, '=' )
						->add_dependency( 'connected_'.$post_type_id.'_taxonomy', $tax_slug, '=' );
				}
			}
		}

		return $archive_template_fields;
	}

	public function add_meta_boxes(){
		$archive_template_location = new Post_Type();
		$archive_template_location->add_post_type( 'archive_template' );
		$archive_template_location->context = 'side';
		$archive_template_fields = self::get_fields();

		Container::create( 'archive_template_settings_1' )
		    ->set_title('Archive Template Settings')
		    ->add_location( $archive_template_location )
		    ->add_fields($archive_template_fields);

		Container::create( 'archive_template_settings_2' )
            ->add_location( 'post_type', 'archive_template' )
            ->set_description_position('label')
		    ->set_title('Archive Template Settings')
		    ->add_fields(array(
                Field::create( 'ultimate_builder', 'page_content', __('Content','mv23theme') )
                    ->add_groups( Builder_Core::getInstance()->get_groups_for_builder() )
            ));
	}

	/**
	 * Get archive post type
	 */
	public function get_archive_post_type(){
		$post_type = get_post_type();
	
		// when term is emtpy get_post_type() return empty
		if( empty($post_type) ){
			// trying this to get the post type
			if( is_post_type_archive() ){
				$post_type = get_query_var( 'post_type' );
			} else {
                $queried_object = get_queried_object();
				if( $queried_object && isset($queried_object->taxonomy) ){
					$post_type = get_taxonomy($queried_object->taxonomy)->object_type[0];
				}
			}
		}
	
		return $post_type;
	}

    public function get_taxonomy() {
		if(is_category()){
			return 'category';
		} else if( is_tag() ){
			return 'post_tag';
		} else {
			return get_query_var( 'taxonomy' );
		}
	}

	/**
	 * Get archive template page ID in archive.php
	 */
	public function get_archive_template_id() {
		$is_connected = 0;
		$posttype = self::$instance->get_archive_post_type();

		$args = array(
			'post_type' => 'archive_template',
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

			$current_term_id  = (int) get_queried_object_id();
			$current_taxonomy = self::$instance->get_taxonomy();

			foreach ($posts as $post_id) {
				$connected_taxonomy = get_post_meta($post_id, 'connected_'.$posttype.'_taxonomy', true);
				if( empty($connected_taxonomy) ){
					// level 1: matches any term/taxonomy for this post type
					if( !$match_by_posttype ) $match_by_posttype = $post_id;
				} else {
					$connected_terms = get_post_meta($post_id, 'connected_'.$connected_taxonomy.'_terms', true);
					if( is_array($connected_terms) && !empty($connected_terms) ){
						// level 3: matches only if the current queried term is among the connected terms
						if( !$match_by_terms && in_array( $current_term_id, array_map('intval', $connected_terms) ) ){
							$match_by_terms = $post_id;
						}
					} else {
						// level 2: matches any term of the connected taxonomy
						if( !$match_by_taxonomy && $current_taxonomy === $connected_taxonomy ){
							$match_by_taxonomy = $post_id;
						}
					}
				}
			}

			// Return the most specific match found
			$is_connected = $match_by_terms ?: ( $match_by_taxonomy ?: $match_by_posttype );
		}

		// If there isnt any archive template configured and is in blog pages (home, tag, cat) use the settings in page for posts
		if( !$is_connected && ( is_home() || is_tag() || is_category() ) ) return get_option('page_for_posts');

		return $is_connected;
	}

    public function get_default_listing_args(){
        $listing_args = array(
            'additional_classes' => array( 'disable-numeric-ajax-pagination' ),
            'source' => 'auto',
            'listing_template' => '',
            'columns' => LISTING_COLUMNS,
            'columns_gap' => LISTING_GAP,
            'carousel_settings' => array(),
			// posttype is needed to handle the "_default" postcard template placeholder in the listing component:
			'posttype' => self::$instance->get_archive_post_type(),
            'postcard_settings' => array(
			    'template' => '_default',
			    'on_click_post' => '',
			    'on_click_scroll_to' => ''
		    ),
            'pagination_type' => 'numeric',
            'pagination_scrolltop' => false
        );

        return $listing_args;
    }

    public function display(){
        $archive_template_id = self::$instance->get_archive_template_id();
        if( $archive_template_id ){
            $page = new Page();
		    $page_content = $page->the_content( $archive_template_id );
	        if (!empty($page_content)) {
                echo $page_content;
            }
        } else {
            // fallback to show the default content if no archive template is connected to the current post type
            echo Main_Content::display(array(
                'template' => 'main-content--sidebar-left',
                'components' => array(
                    array( 
                        'type' => 'main', 
                        'components' => array(
                            array( 'type' => 'archive-title' ),
                            array( 'type' => 'archive-posts' )
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
        }
    }

    /**
	 * Redirect single archive_template to connected archive
	 */
	function redirect_single() {
		if( !is_singular( 'archive_template' ) ) return;

		$archive_template_id = get_the_ID();
		$redirect_to = null;

		$connected_posttype = get_post_meta($archive_template_id, 'connected_posttype', true);
		if ($connected_posttype == 'post') {
			$redirect_to = get_permalink( get_option( 'page_for_posts' ) );
		} else {
			$redirect_to = home_url($connected_posttype);
		}

		$connected_taxonomy = get_post_meta($archive_template_id, 'connected_'.$connected_posttype.'_taxonomy', true);
		if( !empty($connected_taxonomy) ){
			$connected_taxonomy_url_slug = ($connected_taxonomy == 'product_cat') ? 'categoria-producto' : $connected_taxonomy;
			$redirect_to = get_home_url() . '/' . $connected_taxonomy_url_slug . '/';
		}
	
		$connected_terms = get_post_meta($archive_template_id, 'connected_'.$connected_taxonomy.'_terms', true);
		if( is_array($connected_terms) && !empty($connected_terms) ){
			$term_link = get_term_link( (int) $connected_terms[0], $connected_taxonomy );
			if ( ! is_wp_error( $term_link ) ) {
				$redirect_to = $term_link;
			}
		}

		if ($redirect_to) {
			// if isset ub_preview, add it to the redirect URL to allow previewing the archive template
			if( isset($_GET['ub_preview']) ) {
				$redirect_to = add_query_arg( 'ub_preview', $_GET['ub_preview'], $redirect_to );
			}

			wp_redirect( $redirect_to );
			exit;
		}
	}















	/**
	 * Used to hook an action on_listing_start
	 * if the current term has the content type 'terms_and_posts_children' meta
	 */
	public function wp_head_archive() {
		if( is_tax() || is_category() || is_tag() ){
			$current_term = get_queried_object();
			$content_type = get_term_meta($current_term->term_id, 'content_type', true);
			if( $content_type === 'terms_and_posts_children' ){
				add_action( 'on_listing_start', function() use ( $current_term ) {
					echo self::terms_and_posts_children_content($current_term);
				});
			}
		}
	}

	/**
	 * Filter the query to exclude posts from Child Terms
	 * if the current term has the content type 'terms_and_posts_children' meta
	 */
	public function pre_get_posts( $query ){
		if( 
			!is_admin() && $query->is_main_query() 
			&& ( $query->is_tax() || $query->is_category() || $query->is_tag() )
		){
			$current_term = get_queried_object();
			$content_type = get_term_meta($current_term->term_id, 'content_type', true);
			if( $content_type === 'terms_and_posts_children' ){
				// filter the query to exclude posts from Child Terms
				$taxonomy = $current_term->taxonomy;
				$term_id = $current_term->term_id;
				$term_slug = $current_term->slug;

				// Retrieve all child terms of the specified parent term
				$child_terms = get_terms(array(
        			'taxonomy'   => $taxonomy,
        			'child_of'   => $term_id,
        			'fields'     => 'ids',
        			'hide_empty' => false
    			));
    			// Include the parent term in the exclusion list
    			$exclude_terms = $child_terms;

				$exclude_query = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $exclude_terms,
					'operator' => 'NOT IN',
				);

				$query->tax_query->queries[] = $exclude_query; 
    			$query->query_vars['tax_query'] = $query->tax_query->queries;
			}
		}
	}

    public static function terms_and_posts_children_content($current_term) {
		$term_children = get_terms(
		    $current_term->taxonomy,
		    array(
		        'parent' => $current_term->term_id
		    )
		);
		ob_start();
		if ( ! is_wp_error( $term_children ) ) {
		    foreach ( $term_children as $child ) {
				get_template_part( 'partials/card/folder', $current_term->taxonomy, array('term' => $child));
		    }
		}
		return ob_get_clean();
	}

	// public static function the_content() {
	// 	$current_term = get_queried_object();
	// 	$content_type = ( is_tax() || is_category() || is_tag() ) 
	// 		? get_term_meta($current_term->term_id, 'content_type', true)
	// 		: '_____void___is_post_type_date_author_etc';

	// 	ob_start();
	// 	if( $content_type === 'terms_hierarchy' ){
	// 		$content = apply_filters(
	// 			'filter_terms_hierarchy_content_in_archive', 
	// 			self::terms_hierarchy_content($current_term), 
	// 			$current_term
	// 		);
	// 		echo $content;
	// 	} else {
	// 		get_template_part('partials/archive');
	// 	}
	// 	return ob_get_clean();
	// }

	// public static function terms_hierarchy_content($current_term){
	// 	ob_start();
    // 	echo '<div class="component vertical-nav vertical-nav-2 menu-comp">';
    // 	echo Nav_Walker::list_terms_recursive($current_term->taxonomy, $current_term->term_id, 0);
    // 	echo '</div>';
    // 	return ob_get_clean();
	// }
}