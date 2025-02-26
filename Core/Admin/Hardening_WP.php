<?php
namespace Core\Admin;

class Hardening_WP{
    public function redirect_admin(){
        if ( ! current_user_can( 'edit_posts' ) ){
            if (defined( 'DOING_AJAX' ) and DOING_AJAX) {
                // do nothing
            } else {
                wp_redirect( home_url() );
                exit;
            }
        }
    }

    public function disable_admin_bar() {
        if ( ! current_user_can('edit_posts') ) {
            add_filter('show_admin_bar', '__return_false');
        }
    }

    public function redirect_author_page() {
        if (is_author()) {
            wp_redirect(home_url());
            exit;
        }
    }

    public function disable_rest_endpoints($endpoints) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        return $endpoints;
    }
}