<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$menu_styles = apply_filters( 
    'theme_nav_styles', 
    array( 
        array( 'slug' => 'unordered-list', 'name' => __('None','default'), 'image' => '' ),
        array( 'slug' => 'horizontal-nav-1', 'name' => __('Horizontal Nav 1','default'), 'image' => '' ),
        array( 'slug' => 'horizontal-nav-2', 'name' => __('Horizontal Nav 2','default'), 'image' => '' ),
        array( 'slug' => 'horizontal-nav-3', 'name' => __('Horizontal Nav 3','default'), 'image' => '' ),
        array( 'slug' => 'vertical-nav-1', 'name' => __('Vertical Nav 1','default'), 'image' => '' )
    ) 
);

$images_path = get_template_directory_uri() . '/inc/ultimate-fields/images/';
$menu_styles_for_image_select = array();
foreach ($menu_styles as $style) {
    $image_path = ( $style['image'] != '' ) ? $style['image'] : $images_path.$style['slug'].'.png';
    $menu_styles_for_image_select[ $style['slug'] ] = array( 'label' => $style['name'], 'image' => $image_path );
}

$menu = Repeater_Group::create( 'Menu', array(
    'edit_mode' => 'popup',
    'fields' => array(
        Field::create( 'tab', __('Content','default')),
        Field::create( 'radio', 'type', __('Select','default') )->add_options(array(
			'menu'     => __( 'Show a particular menu', 'default' ),
			'location' => __( 'Select a location. If a menu is assigned to that location, it will be displayed', 'default' ),
        ))->set_width(50),
        Field::create( 'select', 'menu' )->add_terms( 'nav_menu' )->add_dependency('type','menu','=')->set_width(50),
        Field::create( 'select', 'location' )->add_options( get_registered_nav_menus() )->add_dependency('type','location','=')->set_width(50),
        Field::create( 'image_select', 'style', __('Style','default') )->add_options( $menu_styles_for_image_select ),
    )
))
->add_fields($settings_fields_container->get_fields());