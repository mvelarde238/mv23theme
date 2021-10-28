<?php
class Archive_Page {
	/**
	 * Instance of the class.
	 */
	private static $instance;

	/**
	 * Supported taxonomies
	 */
	public $supported_taxonomies;

	public function __construct(){
		$this->supported_taxonomies = array_keys(ARCHIVE_OPTIONS_TAXONOMIES);		
	}

	/**
	 * Class Instance.
	 * @return Archive_Page
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && !( self::$instance instanceof Archive_Page ) ) {
			self::$instance = new Archive_Page();

			// Do stuff
			add_action( 'init',					array( self::$instance, 'register_cpt'    ) );
			add_action( 'template_redirect',	array( self::$instance, 'redirect_single' ) );
			// add_action( 'admin_bar_menu',		array( self::$instance, 'admin_bar_link' ), 90 );
		}
		return self::$instance;
	}

	/**
	 * Get archive type
	 */
	function get_archive_type() {
		if (is_tax() || is_category()) {
			return 'taxonomy';
		} else if (is_post_type_archive()) {
			return 'posttype';
		} else {
			return 'posttype';
		}
	}

	/**
	 * Get taxonomy
	 */
	function get_taxonomy() {
		$taxonomy = is_category() ? 'category' : ( is_tag() ? 'post_tag' : get_query_var( 'taxonomy' ) );
		if( in_array( $taxonomy, archive_page()->supported_taxonomies ) ){
			return $taxonomy;
		}else{
			return false;
		}
	}

	/**
	 * Get Post Type
	 */
	function get_posttype() {
		if (is_home()) {
			return 'post';
		} else{
			$queried_object = get_queried_object();
			return $queried_object->name;
		}
	}

	/**
	 * Get Archive page ID in archive.php
	 */
	function get_archive_id() {
		$args = array(
			'post_type' => 'archive_page',
			'posts_per_page' => 1,
			'fields' => 'ids',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		if ( archive_page()->get_archive_type() == 'posttype' ) {
			$posttype = archive_page()->get_posttype();
			$args['meta_query'] = array(
				array(
					'key' => 'connected_posttype',
					'value' => $posttype,
					'compare' => '='
				)
			);

		} else {
			$taxonomy = archive_page()->get_taxonomy();
			if( empty( $taxonomy ) ) return false;
			$args['meta_query'] = array(
				array(
					'key' => 'connected_taxonomy',
					'value' => $taxonomy,
					'compare' => '='
				)
			);
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'term_id',
					'terms' => array( get_queried_object_id() ),
					'include_children' => false,
				)
			);
		}

		$loop = new WP_Query( $args );

		if( empty( $loop->posts ) ){
			return false;
		}else{
			return $loop->posts[0];
		}
	}

	/**
	 * Register the custom post type
	 */
	function register_cpt() {
		$labels = array(
			'name'               => 'Archive Pages',
			'singular_name'      => 'Archive Page',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Page',
			'edit_item'          => 'Edit Page',
			'new_item'           => 'New Archive Page',
			'view_item'          => 'View Page',
			'search_items'       => 'Search Pages',
			'not_found'          => 'No Pages found',
			'not_found_in_trash' => 'No Pages found in Trash',
			'parent_item_colon'  => 'Parent Page:',
			'menu_name'          => 'Archive Pages',
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'taxonomies'          => archive_page()->supported_taxonomies,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-editor-table', // https://developer.wordpress.org/resource/dashicons/
		);

		register_post_type( 'archive_page', $args );
	}

	/**
	 * Redirect single archive_page to connected archive
	 * boorrado por que podrian ser varias
	 */
	function redirect_single() {
		if( !is_singular( 'archive_page' ) ) return;
		$redirect = null;
		$archive_page_id = get_the_ID();
		$appears_on = get_post_meta( $archive_page_id, 'appears_on', true );

		if ($appears_on == 'posttype') {
			$connected_posttype = get_post_meta( $archive_page_id, 'connected_posttype', true );
			if ($connected_posttype == 'post') {
				$redirect = get_permalink( get_option( 'page_for_posts' ) );
			} else {
				$redirect = home_url($connected_posttype);
			}
		}

		if ($appears_on == 'taxonomy') {
			$taxonomy = get_post_meta( $archive_page_id, 'connected_taxonomy', true );
			$tax_slug = ($taxonomy == 'product_cat') ? 'categoria-producto' : $taxonomy;
			$terms = get_the_terms( $archive_page_id, $taxonomy );

			if( !empty( $terms ) ) {
				// redirige al primer term enlazado
				$redirect = home_url($tax_slug.'/'.$terms[0]->slug);
			}
		}

		if ($redirect) {
			wp_redirect( $redirect );
			exit;
		}
	}

	/**
	 * Admin Bar Link
	 */
	function admin_bar_link( $wp_admin_bar ) {
		// $taxonomy = archive_page()->get_taxonomy();
		// if( ! $taxonomy )
		//  	return;

		// if( ! ( is_user_logged_in() && current_user_can( 'edit_post' ) ) )
		// 	return;

		// $archive_id = archive_page()->get_archive_id();
		// if( !empty( $archive_id ) ) {
		// 	$wp_admin_bar->add_node( array(
		// 		'id' => 'archive_page',
		// 		'title' => 'Edit Page Modules',
		// 		'href'  => get_edit_post_link( $archive_id ),
		// 	) );

		// } else {
		// 	$wp_admin_bar->add_node( array(
		// 		'id' => 'archive_page',
		// 		'title' => 'Add Page Modules',
		// 		'href'  => admin_url( 'post-new.php?post_type=archive_page' )
		// 	) );
		// }
	}
}

/**
 * The function provides access to the class methods.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return object
 */
function archive_page() {
	return Archive_Page::instance();
}

archive_page();