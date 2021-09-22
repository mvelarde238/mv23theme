<?php
function create_producto_post_type() {

	$labels = array(
		'name'                  => _x( 'Productos', 'Post Type General Name', 'default' ),
		'singular_name'         => _x( 'Producto', 'Post Type Singular Name', 'default' ),
		'menu_name'             => __( 'Productos', 'default' ),
		'name_admin_bar'        => __( 'Post Type', 'default' ),
		'archives'              => __( 'Item Archives', 'default' ),
		'attributes'            => __( 'Item Attributes', 'default' ),
		'parent_item_colon'     => __( 'Parent Item:', 'default' ),
		'all_items'             => __( 'Ver Todos', 'default' ),
		'add_new_item'          => __( 'Agregar nuevo', 'default' ),
		'add_new'               => __( 'Agregar nuevo', 'default' ),
		'new_item'              => __( 'Nuevo producto', 'default' ),
		'edit_item'             => __( 'Editar producto', 'default' ),
		'update_item'           => __( 'Actualizar producto', 'default' ),
		'view_item'             => __( 'Ver producto', 'default' ),
		'view_items'            => __( 'Ver producto', 'default' ),
		'search_items'          => __( 'Buscar producto', 'default' ),
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
		'label'                 => __( 'Producto', 'default' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 50,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		// 'rewrite'               => array( 'slug' => 'producto' ),
		'menu_icon'             => 'dashicons-awards',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'supports'              => array('title','editor','thumbnail')
	);
	register_post_type( 'wbproduct', $args );

}
add_action( 'init', 'create_producto_post_type', 0 );





function create_producto_cat() {
	$labels = array(
		'name'              => _x( 'Categorias', 'taxonomy general name', 'default' ),
		'singular_name'     => _x( 'Categoria', 'taxonomy singular name', 'default' ),
		'search_items'      => __( 'Buscar Categorias', 'default' ),
		'all_items'         => __( 'Todos los Categorias', 'default' ),
		'parent_item'       => __( 'Parent Categoria', 'default' ),
		'parent_item_colon' => __( 'Parent Categoria:', 'default' ),
		'edit_item'         => __( 'Editar Categoria', 'default' ),
		'update_item'       => __( 'Actualizar Categoria', 'default' ),
		'add_new_item'      => __( 'Agregar nueva Categoria', 'default' ),
		'new_item_name'     => __( 'Nuevo nombre de Categoria', 'default' ),
		'menu_name'         => __( 'Categoria', 'default' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		// 'rewrite'           => array( 'slug' => 'producto_category' ),
	);

	register_taxonomy( 'wbproduct_tax', 'wbproduct', $args );
}

add_action( 'init', 'create_producto_cat', 0 );