<?php
function create_bloque_horario_post_type() {

	$labels = array(
		'name'                  => _x( 'Secciones Reusables', 'Post Type General Name', 'default' ),
		'singular_name'         => _x( 'Seccion Reusable', 'Post Type Singular Name', 'default' ),
		'menu_name'             => __( 'Secciones Reusables', 'default' ),
		'name_admin_bar'        => __( 'Post Type', 'default' ),
		'archives'              => __( 'Item Archives', 'default' ),
		'attributes'            => __( 'Item Attributes', 'default' ),
		'parent_item_colon'     => __( 'Parent Item:', 'default' ),
		'all_items'             => __( 'Secciones Reusables', 'default' ),
		'add_new_item'          => __( 'Agregar nueva', 'default' ),
		'add_new'               => __( 'Agregar nueva', 'default' ),
		'new_item'              => __( 'Nueva seccion reusable', 'default' ),
		'edit_item'             => __( 'Editar seccion reusable', 'default' ),
		'update_item'           => __( 'Actualizar seccion reusable', 'default' ),
		'view_item'             => __( 'Ver seccion reusable', 'default' ),
		'view_items'            => __( 'Ver seccion reusable', 'default' ),
		'search_items'          => __( 'Buscar seccion reusable', 'default' ),
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
		'label'                 => __( 'Secciones Reusables', 'default' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'theme-options/theme-options-admin.php',
		'menu_position'         => 50,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		// 'rewrite'               => array( 'slug' => 'mv23-bloque_horario' ),
		// 'menu_icon'             => 'dashicons-admin-site',
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'supports'              => array('title','page-attributes','revisions')
	);
	register_post_type( 'seccion_reusable', $args );

}
add_action( 'init', 'create_bloque_horario_post_type', 0 );