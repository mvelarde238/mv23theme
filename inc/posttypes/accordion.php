<?php
function create_v23accordion_post_type() {

	$labels = array(
		'name'                  => _x( 'Accordion', 'Post Type General Name', 'asiquim' ),
		'singular_name'         => _x( 'Accordion', 'Post Type Singular Name', 'asiquim' ),
		'menu_name'             => __( 'Accordion', 'asiquim' ),
		'name_admin_bar'        => __( 'Post Type', 'asiquim' ),
		'archives'              => __( 'Item Archives', 'asiquim' ),
		'attributes'            => __( 'Item Attributes', 'asiquim' ),
		'parent_item_colon'     => __( 'Parent Item:', 'asiquim' ),
		'all_items'             => __( 'Ver Todos', 'asiquim' ),
		'add_new_item'          => __( 'Agregar nuevo', 'asiquim' ),
		'add_new'               => __( 'Agregar nuevo', 'asiquim' ),
		'new_item'              => __( 'Nuevo accordion', 'asiquim' ),
		'edit_item'             => __( 'Editar accordion', 'asiquim' ),
		'update_item'           => __( 'Actualizar accordion', 'asiquim' ),
		'view_item'             => __( 'Ver accordion', 'asiquim' ),
		'view_items'            => __( 'Ver accordion', 'asiquim' ),
		'search_items'          => __( 'Buscar accordion', 'asiquim' ),
		'not_found'             => __( 'Not found', 'asiquim' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'asiquim' ),
		'featured_image'        => __( 'Featured Image', 'asiquim' ),
		'set_featured_image'    => __( 'Set featured image', 'asiquim' ),
		'remove_featured_image' => __( 'Remove featured image', 'asiquim' ),
		'use_featured_image'    => __( 'Use as featured image', 'asiquim' ),
		'insert_into_item'      => __( 'Insert into item', 'asiquim' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'asiquim' ),
		'items_list'            => __( 'Items list', 'asiquim' ),
		'items_list_navigation' => __( 'Items list navigation', 'asiquim' ),
		'filter_items_list'     => __( 'Filter items list', 'asiquim' ),
	);
	$args = array(
		'label'                 => __( 'Accordion', 'asiquim' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 50,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		// 'rewrite'               => array( 'slug' => 'abogado' ),
		'menu_icon'             => 'dashicons-feedback',
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'supports'              => array('title')
	);
	register_post_type( 'v23accordion', $args );

}
add_action( 'init', 'create_v23accordion_post_type', 0 );



add_filter( 'manage_v23accordion_posts_columns', function($defaults){
	// unset($defaults['date']);
	$defaults['shortcode'] = 'Shortcode';
	return $defaults;
});

add_action( 'manage_v23accordion_posts_custom_column', function($column_name, $post_id ){
	if ($column_name == 'shortcode') {
	    echo '<code>[accordion id="'.$post_id.'"]</code>';
	}
}, 10, 2 );