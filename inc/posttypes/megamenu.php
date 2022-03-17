<?php
function create_megamenu_post_type() {

	$labels = array(
		'name'                  => _x( 'Megamenú', 'Post Type General Name', 'mv23' ),
		'singular_name'         => _x( 'Megamenú', 'Post Type Singular Name', 'mv23' ),
		'menu_name'             => __( 'Megamenú', 'mv23' ),
		'name_admin_bar'        => __( 'Post Type', 'mv23' ),
		'archives'              => __( 'Item Archives', 'mv23' ),
		'attributes'            => __( 'Item Attributes', 'mv23' ),
		'parent_item_colon'     => __( 'Parent Item:', 'mv23' ),
		'all_items'             => __( 'Megamenú', 'mv23' ),
		'add_new_item'          => __( 'Agregar nuevo', 'mv23' ),
		'add_new'               => __( 'Agregar nuevo', 'mv23' ),
		'new_item'              => __( 'Nuevo megamenú', 'mv23' ),
		'edit_item'             => __( 'Editar megamenú', 'mv23' ),
		'update_item'           => __( 'Actualizar megamenú', 'mv23' ),
		'view_item'             => __( 'Ver megamenú', 'mv23' ),
		'view_items'            => __( 'Ver megamenú', 'mv23' ),
		'search_items'          => __( 'Buscar megamenú', 'mv23' ),
		'not_found'             => __( 'Not found', 'mv23' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'mv23' ),
		'featured_image'        => __( 'Featured Image', 'mv23' ),
		'set_featured_image'    => __( 'Set featured image', 'mv23' ),
		'remove_featured_image' => __( 'Remove featured image', 'mv23' ),
		'use_featured_image'    => __( 'Use as featured image', 'mv23' ),
		'insert_into_item'      => __( 'Insert into item', 'mv23' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'mv23' ),
		'items_list'            => __( 'Items list', 'mv23' ),
		'items_list_navigation' => __( 'Items list navigation', 'mv23' ),
		'filter_items_list'     => __( 'Filter items list', 'mv23' ),
	);
	$args = array(
		'label'                 => __( 'Megamenú', 'mv23' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'theme-options/theme-options-admin.php',
		'menu_position'         => 50,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		// 'rewrite'               => array( 'slug' => 'abogado' ),
		'menu_icon'             => 'dashicons-image-filter',
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'supports'              => array('title')
	);
	register_post_type( 'megamenu', $args );

}
add_action( 'init', 'create_megamenu_post_type', 0 );
