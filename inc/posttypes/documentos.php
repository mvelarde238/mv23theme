<?php
function create_documento_post_type() {

	$labels = array(
		'name'                  => _x( 'Documentos', 'Post Type General Name', 'reactor' ),
		'singular_name'         => _x( 'Documento', 'Post Type Singular Name', 'reactor' ),
		'menu_name'             => __( 'Documentos', 'reactor' ),
		'name_admin_bar'        => __( 'Post Type', 'reactor' ),
		'archives'              => __( 'Item Archives', 'reactor' ),
		'attributes'            => __( 'Item Attributes', 'reactor' ),
		'parent_item_colon'     => __( 'Parent Item:', 'reactor' ),
		'all_items'             => __( 'Ver Todos', 'reactor' ),
		'add_new_item'          => __( 'Agregar nuevo', 'reactor' ),
		'add_new'               => __( 'Agregar nuevo', 'reactor' ),
		'new_item'              => __( 'Nuevo documento', 'reactor' ),
		'edit_item'             => __( 'Editar documento', 'reactor' ),
		'update_item'           => __( 'Actualizar documento', 'reactor' ),
		'view_item'             => __( 'Ver documento', 'reactor' ),
		'view_items'            => __( 'Ver documento', 'reactor' ),
		'search_items'          => __( 'Buscar documento', 'reactor' ),
		'not_found'             => __( 'Not found', 'reactor' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'reactor' ),
		'featured_image'        => __( 'Featured Image', 'reactor' ),
		'set_featured_image'    => __( 'Set featured image', 'reactor' ),
		'remove_featured_image' => __( 'Remove featured image', 'reactor' ),
		'use_featured_image'    => __( 'Use as featured image', 'reactor' ),
		'insert_into_item'      => __( 'Insert into item', 'reactor' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'reactor' ),
		'items_list'            => __( 'Items list', 'reactor' ),
		'items_list_navigation' => __( 'Items list navigation', 'reactor' ),
		'filter_items_list'     => __( 'Filter items list', 'reactor' ),
	);
	$args = array(
		'label'                 => __( 'Documento', 'reactor' ),
		'labels'                => $labels,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 50,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		// 'rewrite'               => array( 'slug' => 'documentos' ),
		'menu_icon'             => 'dashicons-media-text',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'supports'              => array('title','excerpt')
	);
	register_post_type( 'documentos', $args );

}
add_action( 'init', 'create_documento_post_type', 0 );


// Register Taxonomy
function create_documentos_taxonomies() {
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

	register_taxonomy( 'doc-category', 'documentos', $args );
}

add_action( 'init', 'create_documentos_taxonomies', 0 );