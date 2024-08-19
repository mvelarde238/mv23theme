<?php
use Theme\CPT;

$footers = new CPT(
    array(
        'post_type_name' => 'footer',
        'plural' => 'Pie de Página',
    ), 
    array(
        'show_in_menu' => 'theme-options-menu',
        'show_in_nav_menus' => false,
        'supports' => array('title')
    )
);

/*
 * Add footer CPT to Polylang
 */
function add_footer_cpt_to_pll($post_types, $hide) {
    if ($hide){
        // hides 'footer' from the list of custom post types in Polylang settings
        unset($post_types['footer']);
    } else {
        // enables language and translation management for 'footer'
        $post_types['footer'] = 'footer';
    }
    return $post_types;
}
add_filter('pll_get_post_types', 'add_footer_cpt_to_pll', 10, 2);

/*
 * Manage CPT columns
 */
$footers->columns(array(
    'cb' => '<input type="checkbox" />',
    'title' => __('Title'),
    'is_theme_footer' => __('Estado'),
    'date' => __('Date')
));
    
$footers->populate_column('is_theme_footer', function($column_name, $post) {
    $theme_footer_post = get_option('theme_footer_post');

    if( $theme_footer_post == 'post_'.$post->ID ){
        echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
    } else {
        $url = add_query_arg(
            [
              'post' => $post->ID,
              'action' => 'save_as_theme_footer_post',
            ],
            admin_url( 'post.php' )
        );
        echo '<a href="'.esc_url($url).'"><span class="dashicons dashicons-marker" style="color:silver"></span></a>';
    }
});

/*
 * Add action to update active theme footer
 */
add_action( 'admin_action_save_as_theme_footer_post', function () {
    $post_id = $_REQUEST['post'];
    update_option('theme_footer_post', 'post_'.$post_id);
    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit();
});

/**
 * Add link in admin list to update active theme footer
 */
add_filter( 'post_row_actions', function($actions, $post) {
    global $post;

	if ( $post->post_type != 'footer' ) {
		return $actions;
	}

    if ( ! current_user_can( 'manage_options', $post->ID ) ) {
        return $actions;
	}

	$url = add_query_arg(
		[
		  'post' => $post->ID,
		  'action' => 'save_as_theme_footer_post',
		],
		admin_url( 'post.php' )
	);

	$actions['save_as_theme_footer_post'] = sprintf(
		'<a href="%1$s">%2$s</a>',
		$url,
		'Use as theme footer'
	);

    return $actions;
}, 10, 2 );