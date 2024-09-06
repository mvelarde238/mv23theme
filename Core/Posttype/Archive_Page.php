<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use WP_Query;

class Archive_Page {
	
	private static $instance = null;

	private static $supported_taxonomies;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Archive_Page();
			self::$supported_taxonomies = array_keys(ARCHIVE_OPTIONS_TAXONOMIES);

			// add_action( 'template_redirect', array( $this, 'redirect_single' ) );
			// add_action( 'admin_bar_menu', array( $this, 'admin_bar_link' ), 90 );
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $archive_page = new CPT(
            'archive_page',
            array(
				'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'supports' => array('title'),
				'taxonomies' => self::$supported_taxonomies,
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

	/**
	 * Redirect single archive_page to connected archive
	 * not used por que podrian ser varias
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
		// $taxonomy = $this->get_taxonomy();
		// if( ! $taxonomy )
		//  	return;

		// if( ! ( is_user_logged_in() && current_user_can( 'edit_post' ) ) )
		// 	return;

		// $archive_id = $this->get_archive_id();
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

	public function get_archive_type() {
		if (is_tax() || is_category()) {
			return 'taxonomy';
		} else if (is_post_type_archive()) {
			return 'posttype';
		} else {
			return 'posttype';
		}
	}

	public function get_taxonomy() {
		$taxonomy = is_category() ? 'category' : ( is_tag() ? 'post_tag' : get_query_var( 'taxonomy' ) );
		if( in_array( $taxonomy, self::$supported_taxonomies ) ){
			return $taxonomy;
		}else{
			return false;
		}
	}

	public function get_posttype() {
		if (is_home()) {
			return 'post';
		} else if(is_date()){
			return 'post';
		} else{
			$queried_object = get_queried_object();
			return $queried_object->name;
		}
	}

	/**
	 * Get Archive page ID in archive.php
	 */
	public function get_archive_id() {
		$args = array(
			'post_type' => 'archive_page',
			'posts_per_page' => 1,
			'fields' => 'ids',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false
		);

		if ( self::$instance->get_archive_type() == 'posttype' ) {
			$posttype = self::$instance->get_posttype();
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => 'appears_on',
					'value'   => 'posttype',
					'compare' => '=',
				),
				array(
					'key' => 'connected_posttype',
					'value' => $posttype,
					'compare' => '='
				)
			);

		} else {
			$taxonomy = self::$instance->get_taxonomy();
			if( empty( $taxonomy ) ) return false;
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => 'appears_on',
					'value'   => 'taxonomy',
					'compare' => '=',
				),
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
}