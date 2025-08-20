<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use WP_Query;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Ultimate_Fields\Location\Post_Type;
use Core\Frontend\Nav_Walker;

define ('LISTING_DESKTOP_COLUMNS', 2);
define ('LISTING_LAPTOP_COLUMNS', 2);
define ('LISTING_TABLET_COLUMNS', 2);
define ('LISTING_MOBILE_COLUMNS', 1);

define ('LISTING_DESKTOP_GAP', 50);
define ('LISTING_LAPTOP_GAP', 40);
define ('LISTING_TABLET_GAP', 30);
define ('LISTING_MOBILE_GAP', 20);

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

	public function add_meta_boxes(){
		$archive_location = new Post_Type();
		$archive_location->add_post_type( 'archive_page' );
		$archive_location->context = 'side';

		# Add post types
		$post_types = array();
		$excluded = array( 'attachment', 'page' );
		foreach( get_post_types( array('public'=>true, 'exclude_from_search'=>false), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}
			$post_types[ $id ] = __( $post_type->labels->name );
		}

		$archive_page_fields = array(
			Field::create( 'radio', 'connected_posttype' )->set_orientation( 'horizontal' )->add_options($post_types)
		);
		
		# Add taxonomies
		foreach ($post_types as $post_type_id => $post_type_name) {
			$taxonomies = array( '' => __('Any','mv23theme') );
			foreach( get_taxonomies( array( 'object_type' => array($post_type_id), 'show_ui' => true ), 'objects' ) as $slug => $taxonomy ) {
				$taxonomies[$slug] = $taxonomy->labels->name;
			}
			$archive_page_fields[] = Field::create( 'radio', 'connected_'.$post_type_id.'_taxonomy' )
				->set_orientation( 'horizontal' )
				->add_dependency( 'connected_posttype', $post_type_id, '=' )
				->add_options($taxonomies);

			# Add terms
			foreach ($taxonomies as $tax_slug => $tax_name) {
				if( !empty($tax_slug) ){
					$archive_page_fields[] = Field::create( 'multiselect', 'connected_'.$tax_slug.'_terms', 'Connected '.$tax_name.' terms' )
						->add_terms( $tax_slug )
						->add_dependency( 'connected_posttype', $post_type_id, '=' )
						->add_dependency( 'connected_'.$post_type_id.'_taxonomy', $tax_slug, '=' );
				}
			}
		}

		Container::create( 'archive_page_settings' )
		    ->set_title('Settings')
		    ->add_location( $archive_location )
		    ->add_fields( $archive_page_fields );

		// --------------------------------------------------------------------------------------------------------------------------------------------------------------
		// --------------------------------------------------------------------------------------------------------------------------------------------------------------
		// --------------------------------------------------------------------------------------------------------------------------------------------------------------

		// In archives page '' -> load posttype card template
		$post_template = array_merge( array( '__post' => 'Post' ), LISTING_POST_TEMPLATE );
        if(USE_DOCUMENT_CPT) $post_template['document'] = 'Document';
        if(USE_PORTFOLIO_CPT) $post_template['portfolio'] = 'Portfolio';
		if(WOOCOMMERCE_IS_ACTIVE) $post_template['woocommerce1'] = 'WooCommerce Product Basic';

		$archive_loop_fields = array(
			Field::create( 'tab', 'loop_settings_tabs', __('Loop Settings','mv23theme') ),
			Field::create( 'message', 'lelmsg')
				->hide_label()
				->set_description( __('Place these shortcodes in the page content: [posts] [pagination]'), 'mv23theme' )
				->set_attr( 'style', 'background-color:#eeeeee;color:#000' ),

			Field::create( 'tab', 'loop_columns_tabs', __('Columns quantity','mv23theme') ),
			Field::create( 'complex', 'loop_columns' )->hide_label()->add_fields(array(
				Field::create( 'number', 'desktop' )->set_default_value(LISTING_DESKTOP_COLUMNS)->set_suffix('columns in desktop')->hide_label(),
				Field::create( 'number', 'laptop' )->set_default_value(LISTING_LAPTOP_COLUMNS)->set_suffix('columns in laptop')->hide_label(),
				Field::create( 'number', 'tablet' )->set_default_value(LISTING_TABLET_COLUMNS)->set_suffix('columns in tablet')->hide_label(),
				Field::create( 'number', 'mobile' )->set_default_value(LISTING_MOBILE_COLUMNS)->set_suffix('columns in mobile')->hide_label(),
			)),

			Field::create( 'tab', 'space_between_columns_tab', __('Space between columns','mv23theme') ),
			Field::create( 'complex', 'loop_columns_gap' )->hide_label()->add_fields(array(
				Field::create( 'number', 'desktop')->set_default_value(LISTING_DESKTOP_GAP)->set_suffix('px in desktop')->hide_label(),
				Field::create( 'number', 'laptop' )->set_default_value(LISTING_LAPTOP_GAP)->set_suffix('px in laptop')->hide_label(),
				Field::create( 'number', 'tablet' )->set_default_value(LISTING_TABLET_GAP)->set_suffix('px in tablet')->hide_label(),
				Field::create( 'number', 'mobile' )->set_default_value(LISTING_MOBILE_GAP)->set_suffix('px in mobile')->hide_label(),
			)),

			Field::create( 'tab', 'postcard_settings_tab', __('Post card settings','mv23theme') ),
			Field::create( 'complex', 'postcard_settings' )->hide_label()->add_fields(array(
				Field::create( 'radio', 'template' )->set_orientation( 'vertical' )->add_options($post_template), 
            	Field::create( 'select', 'on_click_post', 'Al hacer click en el post:' )->add_options(array(
            	    'redirect' => 'Redirigir a la página del post',
            	    'show-expander' => 'Mostrar el post en la misma página',
            	    'show-popup' => 'Mostrar el post en un popup',
            	    'none' => 'Ninguna'
            	)),
            	Field::create( 'select', 'on_click_scroll_to', 'Al hacer click mover el scroll a:' )->add_options(array(
            	    '' => 'No mover el scroll',
            	    'postcard' => 'Al post card',
            	    'expander' => 'Al expander'
            	))->add_dependency( 'on_click_post', 'show-expander', '=' ),
			)),

			Field::create( 'tab', 'page_template_tab', __('Page template','mv23theme') ),
			Field::create( 'select', 'page_template')->hide_label()->add_options(array(
				'main-content--sidebar-left' => __('Left Sidebar','deafult'),
				'main-content--sidebar-right' => __('Right Sidebar','deafult'),
				'hide-sidebar' => __('Hide Sidebar','mv23theme')
			))
		);

		Container::create( 'archive_loop_settings_1' )
		    ->set_title('Loop Settings')
		    ->add_location( $archive_location )
		    ->add_fields($archive_loop_fields);

		$page_for_posts = ( get_option('page_for_posts') ) ? get_option('page_for_posts') : 0;

		Container::create( 'archive_loop_settings_2' )
		    ->set_title('Loop Settings')
			->add_location( 'post_type', array('page'), array( 
				'ids' => array($page_for_posts),
				'context' => 'side'
			))
		    ->add_fields($archive_loop_fields);
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
				$post_type = get_queried_object()->name;
			} else {
				$post_type = get_taxonomy(get_queried_object()->taxonomy)->object_type[0];
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

	public function get_loop_columns(){
		$loop_columns = array(
			'desktop' => LISTING_DESKTOP_COLUMNS,
			'laptop' => LISTING_LAPTOP_COLUMNS,
			'tablet' => LISTING_TABLET_COLUMNS,
			'mobile' => LISTING_MOBILE_COLUMNS
		);
	
		$meta_data = self::$instance->check_if_meta_exists('loop_columns');
		if ( $meta_data ) $loop_columns = $meta_data;

		return $loop_columns;
	}

	public function get_columns_gap(){
		$loop_columns_gap = array(
			'desktop' => LISTING_DESKTOP_GAP,
			'laptop' => LISTING_LAPTOP_GAP,
			'tablet' => LISTING_TABLET_GAP,
			'mobile' => LISTING_MOBILE_GAP
		);
	
		$meta_data = self::$instance->check_if_meta_exists('loop_columns_gap');
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
			if( !empty($page_template) && $page_template != 'hide-sidebar' ){
				$page_template_settings['class'] = $page_template;
				$page_template_settings['has_sidebar'] = true;
			}
			if( $page_template === 'hide-sidebar' ){
				$page_template_settings['class'] = '';
				$page_template_settings['has_sidebar'] = false;
			}
		}

		return $page_template_settings;
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
		ob_start();
        $child_terms = get_term_children($current_term->term_id, $current_term->taxonomy);
        if ($child_terms) {
            foreach ($child_terms as $child_term_id) {
                $child_term = get_term($child_term_id, $current_term->taxonomy);
				get_template_part( 'partials/card/folder', $current_term->taxonomy, array('term' => $child_term));
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