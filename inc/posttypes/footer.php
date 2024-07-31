<?php
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

/*
 * Transform old footer option to footer post type on switch theme
 */
function manage_footer_option_to_post( $footer_option_name, $post_title = 'Pie de página' ){
    $old_footer_meta = get_option( $footer_option_name );
    $post_id = null;

    if( $old_footer_meta ){
        $post_data = array(
            'post_title'    => $post_title,
            'post_status'   => 'publish',
            'post_type'     => 'footer',
        );
    
        $post_id = wp_insert_post($post_data);
    
        if (!is_wp_error($post_id)) {
            add_post_meta( $post_id, 'v23_modulos', $old_footer_meta );
            delete_option( $footer_option_name );
        }
    }
    return $post_id;
}

add_action('after_switch_theme', function(){
    $main_footer_id = manage_footer_option_to_post( 'footer_modules' );
    // asignar como theme footer
    if($main_footer_id) add_option('theme_footer_post', 'post_'.$main_footer_id);

    if (IS_MULTILANGUAGE) {
        $lang_footers = array(
            'es' => $main_footer_id,
            'en' => null,
            'pt' => null
        );

        foreach ($lang_footers as $lang => $footer_id) {
            if($lang != 'es'){
                $lang_footer_id = manage_footer_option_to_post( 'footer_modules_'.$lang, 'Pie de página ('.$lang.')' );	                
                if ($lang_footer_id && function_exists('pll_set_post_language')) {
                    pll_set_post_language($lang_footer_id, $lang);
                    $lang_footers[$lang] = $lang_footer_id;
                }
            } else {
                pll_set_post_language($main_footer_id, $lang);
            }
        }

        if (function_exists('pll_save_post_translations')) pll_save_post_translations($lang_footers);
    }
});