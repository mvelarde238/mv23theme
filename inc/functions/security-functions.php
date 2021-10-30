<?php
/**
 * Redirect back to homepage and not allow access to
 * WP admin for Subscribers.
 */
function v23_redirect_admin(){
	if ( ! current_user_can( 'edit_posts' ) ){
		if (defined( 'DOING_AJAX' ) and DOING_AJAX) {
			// do nothing
		} else {
			wp_redirect( home_url() );
			exit;
		}
	}
}
add_action( 'admin_init', 'v23_redirect_admin' );


/**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
function v23_disable_admin_bar() {
    if ( ! current_user_can('edit_posts') ) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action( 'after_setup_theme', 'v23_disable_admin_bar' );




/**
 * Redirect back to homepage and not allow access to
 * certain pages for not logged in users
 */
// function v23_user_check(){
// 	if ( is_page_template('templates/private-page.php') && !is_user_logged_in() ) {
// 		wp_redirect( home_url() );
// 		exit();
// 	}
// }
// add_action( 'template_redirect', 'v23_user_check' );



/**
 * Mostrar la REST API sólo a usuarios administradores
 */
// add_filter( 'rest_authentication_errors', function( $result ) {
//   if ( ! empty( $result ) ) {
//     return $result;
//   }
//   if ( ! is_user_logged_in() ) {
//     return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
//   }
//   if ( ! current_user_can( 'administrator' ) ) {
//     return new WP_Error( 'rest_not_admin', 'You are not an administrator.', array( 'status' => 401 ) );
//   }
//   return $result;
// });