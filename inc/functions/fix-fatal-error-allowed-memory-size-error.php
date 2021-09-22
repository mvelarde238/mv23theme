<?php
/**
 * Disable Posts' meta from being preloaded.
 * This fixes memory problems in the WordPress Admin.
 */
function jb_pre_get_posts( WP_Query $wp_query ) {
	// if ( in_array( $wp_query->get( 'post_type' ), array( 'page' ) ) ) {
		$wp_query->set( 'update_post_meta_cache', false );
	// }
}

// Only do this for admin.
if ( is_admin() ) {
	add_action( 'pre_get_posts', 'jb_pre_get_posts' );
}