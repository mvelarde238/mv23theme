<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use WP_Query;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Ultimate_Fields\Location\Post_Type;
use Core\Frontend\Nav_Walker;
use Core\Builder\Component\Listing;

class Archive_Page {
	
	private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Archive_Page();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $archive_page = new CPT(
            'archive_page',
            array(
				'show_in_menu'        => 'edit.php?post_type=page',
                'show_in_nav_menus'   => false,
                'supports'            => array('title'),
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'menu_icon'           => 'dashicons-editor-table'
            )
        );
	}

	public static function get_fields(){
		$archive_page_fields = array();
		$archive_page_fields[] = Field::create( 'tab', 'content_tab', __('Content Type','mv23theme') );

		# Add post types
		$post_types = array();
		$excluded = array( 'attachment', 'page' );
		foreach( get_post_types( array('public'=>true, 'exclude_from_search'=>false), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}
			$post_types[ $id ] = __( $post_type->labels->name );
		}
		$default_connected_posttype = get_post_meta( $_GET['post'] ?? null, 'connected_posttype', true );
		$archive_page_fields[] = Field::create( 'radio', 'connected_posttype' )
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
			$archive_page_fields[] = Field::create( 'radio', 'connected_'.$post_type_id.'_taxonomy' )
				->set_orientation( 'horizontal' )
				->set_default_value( $default_connected_taxonomy ? $default_connected_taxonomy : '' )
				->add_dependency( 'connected_posttype', $post_type_id, '=' )
				->add_options($taxonomies);

			# Add terms
			foreach ($taxonomies as $tax_slug => $tax_name) {
				if( !empty($tax_slug) ){
					$default_connected_terms = get_post_meta( $_GET['post'] ?? null, 'connected_'.$tax_slug.'_terms', true );
					$archive_page_fields[] = Field::create( 'multiselect', 'connected_'.$tax_slug.'_terms', 'Connected '.$tax_name.' terms' )
						->add_terms( $tax_slug )
						->set_default_value( $default_connected_terms )
						->add_dependency( 'connected_posttype', $post_type_id, '=' )
						->add_dependency( 'connected_'.$post_type_id.'_taxonomy', $tax_slug, '=' );
				}
			}
		}

		# Add listing fields
		$listing_fields = Listing::get_fields();
		$exclude = ['content_tab','source','posttype','woocommerce_key','tax_params','query_settings_tab','query_params','status_params','pagination_scrolltop'];
		foreach ( $listing_fields as $field ) {
			if( in_array( $field->get_name(), $exclude ) ) continue;

			if( $field->get_name() === 'pagination_type' ){
				$pagination_options = LISTING_PAGINATION_TYPES;
				$field->remove_option('none');
			}

			// set default value for listing fields based on archive page meta
			$default_value = get_post_meta( $_GET['post'] ?? null, $field->get_name(), true );
			$field->set_default_value( $default_value );

			$archive_page_fields[] = $field;
		}

		# Add page template fields
		$archive_page_fields[] = Field::create( 'tab', 'page_template_tab', __('Page template','mv23theme') );
		$default_page_template = get_post_meta( $_GET['post'] ?? null, 'page_template', true );
		$archive_page_fields[] = Field::create( 'select', 'page_template')
			->hide_label()
			->set_default_value( $default_page_template ? $default_page_template : 'main-content--sidebar-right' )
			->add_options(array(
				'main-content--sidebar-left' => __('Left Sidebar','mv23theme'),
				'main-content--sidebar-right' => __('Right Sidebar','mv23theme'),
				'main-content--sidebarless' => __('No Sidebar','mv23theme')
			));
		$default_hide_archive_title = get_post_meta( $_GET['post'] ?? null, 'hide_archive_title', true );
		$archive_page_fields[] = Field::create( 'checkbox', 'hide_archive_title')
			->fancy()
			->set_default_value( $default_hide_archive_title )
			->hide_label()
			->set_text( __( 'Hide the archive title', 'mv23theme' ) );

		return $archive_page_fields;
	}

	public function add_meta_boxes(){
		$archive_location = new Post_Type();
		$archive_location->add_post_type( 'archive_page' );
		$archive_location->context = 'side';
		$archive_page_fields = self::get_fields();

		Container::create( 'archive_loop_settings_1' )
		    ->set_title('Loop Settings')
		    ->add_location( $archive_location )
		    ->add_fields($archive_page_fields);

		$page_for_posts = ( get_option('page_for_posts') ) ? get_option('page_for_posts') : 0;

		Container::create( 'archive_loop_settings_2' )
		    ->set_title('Loop Settings')
			->add_location( 'post_type', array('page'), array( 
				'ids' => array($page_for_posts),
				'context' => 'side'
			))
		    ->add_fields($archive_page_fields);
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
	 * Get Archive post type
	 */
	public function get_archive_post_type(){
		$post_type = get_post_type();
	
		// when term is emtpy get_post_type() return empty
		if( empty($post_type) ){
			// trying this to get the post type
			if( is_post_type_archive() ){
				$post_type = get_query_var( 'post_type' );
			} else {
				if( get_queried_object()->taxonomy ){
					$post_type = get_taxonomy(get_queried_object()->taxonomy)->object_type[0];
				}
			}
		}
	
		return $post_type;
	}

	/**
	 * Get Archive page ID in archive.php
	 */
	public function get_archive_id() {
		$is_connected = 0;
		$posttype = self::$instance->get_archive_post_type();

		$args = array(
			'post_type' => 'archive_page',
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
			foreach ($posts as $post_id) {
				$connected_taxonomy = get_post_meta($post_id, 'connected_'.$posttype.'_taxonomy', true);
				if( empty($connected_taxonomy) ){
					// the archive page is configured to work with any taxonomy of the selected posttype
					// break the foreach to return the latest published
					$is_connected = $post_id;
					break;
				} else { 
					$connected_terms = get_post_meta($post_id, 'connected_'.$connected_taxonomy.'_terms', true);

					if( is_array($connected_terms) && !empty($connected_terms) ){
						// the archive page is configured to work with certain terms
						$term = get_queried_object_id();
						if( in_array( $term, $connected_terms) ){
							$is_connected = $post_id;
							break;
						}
					} else {
						// the archive page is configured to work with a certain taxonomy
						$taxonomy = self::$instance->get_taxonomy();
						if( $connected_taxonomy == $taxonomy ){
							$is_connected = $post_id;
							break;
						}
					}
				}
			}
		}

		// If there isnt any archive page configured and is in blog pages (home, tag, cat) use the settings in page for posts
		if( !$is_connected && ( is_home() || is_tag() || is_category() ) ) return get_option('page_for_posts');

		return $is_connected;
	}

	/**
	 * Post meta related methods
	 */
	private function check_if_meta_exists($meta_name){
		$meta_data = false;

		$archive_page_id = self::$instance->get_archive_id();

		if ( !empty($archive_page_id) ){
			$post_meta = get_post_meta( $archive_page_id, $meta_name, true );
			if( $post_meta ) $meta_data = $post_meta;
		}

		return $meta_data;
	}

	public function get_listing_template(){
		$listing_template = '';

		$meta_data = self::$instance->check_if_meta_exists('listing_template');
		if ( $meta_data ) $listing_template = $meta_data;

		return $listing_template;
	}

	public function get_loop_columns(){
		$loop_columns = LISTING_COLUMNS;
	
		$meta_data = self::$instance->check_if_meta_exists('columns');
		if ( $meta_data ) $loop_columns = $meta_data;

		return $loop_columns;
	}

	public function get_columns_gap(){
		$loop_columns_gap = LISTING_GAP;
	
		$meta_data = self::$instance->check_if_meta_exists('columns_gap');
		if ( $meta_data ) $loop_columns_gap = $meta_data;

		return $loop_columns_gap;
	}

	public function get_postcard_settings(){
		$postcard_settings = array(
			'template' => '',
			'on_click_post' => '',
			'on_click_scroll_to' => ''
		);
	
		$meta_data = self::$instance->check_if_meta_exists('postcard_settings');
		if ( $meta_data ) $postcard_settings = $meta_data;

		return $postcard_settings;
	}

	public function get_page_template_settings(){
		$page_template_settings = array(
			'class' => 'main-content--sidebar-left',
			'has_sidebar' => true
		);
	
		$archive_page_id = self::$instance->get_archive_id();

		if ( !empty($archive_page_id) ){
			$page_template = get_post_meta( $archive_page_id, 'page_template', true );
			if( !empty($page_template) && $page_template != 'main-content--sidebarless' ){
				$page_template_settings['class'] = $page_template;
				$page_template_settings['has_sidebar'] = true;
			}
			if( $page_template === 'main-content--sidebarless' ){
				$page_template_settings['class'] = '';
				$page_template_settings['has_sidebar'] = false;
			}
		}

		return $page_template_settings;
	}

	public function hide_archive_title(){
		$hide_archive_title = false;
	
		$meta_data = self::$instance->check_if_meta_exists('hide_archive_title');
		if ( $meta_data ) $hide_archive_title = true;

		return $hide_archive_title;
	}

	public function get_carousel_settings(){
		$carousel_settings = array();

		$meta_data = self::$instance->check_if_meta_exists('carousel_settings');
		if ( $meta_data ) $carousel_settings = $meta_data;

		return $carousel_settings;
	}

	public function get_pagination_type(){
		$pagination_type = 'numeric';

		$meta_data = self::$instance->check_if_meta_exists('pagination_type');
		if ( $meta_data ) $pagination_type = $meta_data;
		// force numeric pagination in archive pages if 'none' is selected
		if ( $meta_data === 'none' ) $pagination_type = 'numeric';

		return $pagination_type;
	}

	public function show_filter(){
		$show_filter = false;

		$meta_data = self::$instance->check_if_meta_exists('show_filter');
		if ( $meta_data ) $show_filter = true;

		return $show_filter;
	}

	public function get_filters(){
		$filters = array();

		$meta_data = self::$instance->check_if_meta_exists('filters');
		if ( $meta_data ) $filters = $meta_data;

		return $filters;
	}

	public function get_archive_tax_params(){
		$tax_params = array();

		// get tax from context: category, tag, or custom taxonomy
		$posttype = self::$instance->get_archive_post_type();
		$taxonomy = self::$instance->get_taxonomy();
		$term = get_queried_object_id();
		if( !empty($taxonomy) && !empty($term) ){
			$tax_params = array(
				$posttype.'--'.$taxonomy => array( (int) $term )
			);
		}

		return $tax_params;
	}

	public function get_pagination_scrolltop(){
		$scrolltop = false;

		$meta_data = self::$instance->check_if_meta_exists('pagination_scrolltop');
		if ( $meta_data ) $scrolltop = true;

		return $scrolltop;
	}

	/**
	 * Redirect single archive_page to connected archive
	 */
	function redirect_single() {
		if( !is_singular( 'archive_page' ) ) return;

		$archive_page_id = get_the_ID();
		$redirect_to = null;

		$connected_posttype = get_post_meta($archive_page_id, 'connected_posttype', true);
		if ($connected_posttype == 'post') {
			$redirect_to = get_permalink( get_option( 'page_for_posts' ) );
		} else {
			$redirect_to = home_url($connected_posttype);
		}

		$connected_taxonomy = get_post_meta($archive_page_id, 'connected_'.$connected_posttype.'_taxonomy', true);
		if( !empty($connected_taxonomy) ){
			$connected_taxonomy_url_slug = ($connected_taxonomy == 'product_cat') ? 'categoria-producto' : $connected_taxonomy;
			$redirect_to = get_home_url() . '/' . $connected_taxonomy_url_slug . '/';
		}
	
		$connected_terms = get_post_meta($archive_page_id, 'connected_'.$connected_taxonomy.'_terms', true);
		if( is_array($connected_terms) && !empty($connected_terms) ){
			$term_link = get_term_link( (int) $connected_terms[0], $connected_taxonomy );
			if ( ! is_wp_error( $term_link ) ) {
				$redirect_to = $term_link;
			}
		}

		if ($redirect_to) {
			wp_redirect( $redirect_to );
			exit;
		}
	}

	/**
	 * Used to hook an action on_archive_listing_start
	 * if the current term has the content type 'terms_and_posts_children' meta
	 */
	public function wp_head_archive() {
		if( is_tax() || is_category() || is_tag() ){
			$current_term = get_queried_object();
			$content_type = get_term_meta($current_term->term_id, 'content_type', true);
			if( $content_type === 'terms_and_posts_children' ){
				add_action( 'on_archive_listing_start', function() use ( $current_term ) {
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

	public static function the_content() {
		$current_term = get_queried_object();
		$content_type = ( is_tax() || is_category() || is_tag() ) 
			? get_term_meta($current_term->term_id, 'content_type', true)
			: '_____void___is_post_type_date_author_etc';

		ob_start();
		if( $content_type === 'terms_hierarchy' ){
			$content = apply_filters(
				'filter_terms_hierarchy_content_in_archive', 
				self::terms_hierarchy_content($current_term), 
				$current_term
			);
			echo $content;
		} else {
			get_template_part('partials/archive');
		}
		return ob_get_clean();
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

	public static function terms_hierarchy_content($current_term){
		ob_start();
    	echo '<div class="component vertical-nav vertical-nav-2 menu-comp">';
    	echo Nav_Walker::list_terms_recursive($current_term->taxonomy, $current_term->term_id, 0);
    	echo '</div>';
    	return ob_get_clean();
	}
}