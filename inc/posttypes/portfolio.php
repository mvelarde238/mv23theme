<?php
function create_portfolio_post_type() {

	$labels = array(
		'name'                  => _x( 'Portfolio', 'Post Type General Name', 'default' ),
		'singular_name'         => _x( 'Portfolio', 'Post Type Singular Name', 'default' ),
		'menu_name'             => __( 'Portfolio', 'default' ),
		'name_admin_bar'        => __( 'Portfolio', 'default' ),
		'archives'              => __( 'Item Archives', 'default' ),
		'attributes'            => __( 'Item Attributes', 'default' ),
		'parent_item_colon'     => __( 'Parent Item:', 'default' ),
		'all_items'             => __( 'Ver Todos', 'default' ),
		'add_new_item'          => __( 'Agregar nuevo', 'default' ),
		'add_new'               => __( 'Agregar nuevo', 'default' ),
		'new_item'              => __( 'Nueva entrada', 'default' ),
		'edit_item'             => __( 'Editar entrada', 'default' ),
		'update_item'           => __( 'Actualizar entrada', 'default' ),
		'view_item'             => __( 'Ver entrada', 'default' ),
		'view_items'            => __( 'Ver entrada', 'default' ),
		'search_items'          => __( 'Buscar entrada', 'default' ),
		'not_found'             => __( 'Not found', 'default' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'default' ),
		'featured_image'        => __( 'Featured Image', 'default' ),
		'set_featured_image'    => __( 'Set featured image', 'default' ),
		'remove_featured_image' => __( 'Remove featured image', 'default' ),
		'use_featured_image'    => __( 'Use as featured image', 'default' ),
		'insert_into_item'      => __( 'Insert into item', 'default' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'default' ),
		'items_list'            => __( 'Items list', 'default' ),
		'items_list_navigation' => __( 'Items list navigation', 'default' ),
		'filter_items_list'     => __( 'Filter items list', 'default' ),
	);
	$args = array(
		'label'                 => __( 'Portfolio', 'default' ),
		'labels'                => $labels,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 50,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		// 'rewrite'               => array( 'slug' => 'portfolio' ),
		'menu_icon'             => 'dashicons-screenoptions',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'supports'              => array('title','editor','thumbnail','revisions','excerpt')
	);
	register_post_type( 'portfolio', $args );

}
add_action( 'init', 'create_portfolio_post_type', 0 );

// Register Taxonomy
function create_portfolio_taxonomies() {
	$labels = array(
		'name'              => _x( 'Categorías', 'taxonomy general name', 'reactor' ),
		'singular_name'     => _x( 'Categoría', 'taxonomy singular name', 'reactor' ),
		'search_items'      => __( 'Buscar Categorías', 'reactor' ),
		'all_items'         => __( 'Todos los Categorías', 'reactor' ),
		'parent_item'       => __( 'Parent Categoría', 'reactor' ),
		'parent_item_colon' => __( 'Parent Categoría:', 'reactor' ),
		'edit_item'         => __( 'Editar Categoría', 'reactor' ),
		'update_item'       => __( 'Actualizar Categoría', 'reactor' ),
		'add_new_item'      => __( 'Agregar nueva Categoría', 'reactor' ),
		'new_item_name'     => __( 'Nuevo nombre de Categoría', 'reactor' ),
		'menu_name'         => __( 'Categoría', 'reactor' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true
		// 'rewrite'           => array( 'slug' => '' ),
	);

	register_taxonomy( 'portfolio-cat', 'portfolio', $args );
}

add_action( 'init', 'create_portfolio_taxonomies', 0 );