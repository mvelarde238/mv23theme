<?php
function create_mv23_library_post_type() {

	$labels = array(
		'name'                  => _x( 'Library', 'Post Type General Name', 'default' ),
		'singular_name'         => _x( 'Library', 'Post Type Singular Name', 'default' ),
		'menu_name'             => __( 'Library', 'default' ),
		'name_admin_bar'        => __( 'Library', 'default' ),
		'archives'              => __( 'Item Archives', 'default' ),
		'attributes'            => __( 'Item Attributes', 'default' ),
		'parent_item_colon'     => __( 'Parent Item:', 'default' ),
		'all_items'             => __( 'Librería', 'default' ),
		'add_new_item'          => __( 'Agregar nuevo', 'default' ),
		'add_new'               => __( 'Agregar nuevo', 'default' ),
		'new_item'              => __( 'Nuevo item', 'default' ),
		'edit_item'             => __( 'Editar item', 'default' ),
		'update_item'           => __( 'Actualizar item', 'default' ),
		'view_item'             => __( 'Ver item', 'default' ),
		'view_items'            => __( 'Ver item', 'default' ),
		'search_items'          => __( 'Buscar item', 'default' ),
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
		'label'                 => __( 'Library', 'default' ),
		'labels'                => $labels,
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=page',
		// 'menu_position'         => 50,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		// 'rewrite'               => array( 'slug' => '' ),
		'menu_icon'             => 'dashicons-tagcloud',
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'supports'              => array('title','thumbnail'),
		'taxonomies'			=> array('mv23_library_tax')
	);
	register_post_type( 'mv23_library', $args );

}
add_action( 'init', 'create_mv23_library_post_type', 0 );



// Register Taxonomy
function create_mv23_library_taxonomies() {
	$labels = array(
		'name'              => _x( 'Categorías', 'taxonomy general name', 'default' ),
		'singular_name'     => _x( 'Categoría', 'taxonomy singular name', 'default' ),
		'search_items'      => __( 'Buscar Categorías', 'default' ),
		'all_items'         => __( 'Todas los Categorías', 'default' ),
		'parent_item'       => __( 'Parent Categoría', 'default' ),
		'parent_item_colon' => __( 'Parent Categoría:', 'default' ),
		'edit_item'         => __( 'Editar Categoría', 'default' ),
		'update_item'       => __( 'Actualizar Categoría', 'default' ),
		'add_new_item'      => __( 'Agregar nueva Categoría', 'default' ),
		'new_item_name'     => __( 'Nuevo nombre de Categoría', 'default' ),
		'menu_name'         => __( 'Categoría', 'default' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true
		// 'rewrite'           => array( 'slug' => '' ),
	);

	register_taxonomy( 'mv23_library_tax', 'mv23_library', $args );
}

add_action( 'init', 'create_mv23_library_taxonomies', 0 );