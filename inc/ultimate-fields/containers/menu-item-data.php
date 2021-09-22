<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'menu-item-data' )
    ->add_location( 'menu_item', array(
        'levels' => 1,
        'theme_locations' => MENU_ITEM_DATA_LOCATIONS,
        // 'popup_mode' => true,
        // 'menus' => 16, 
    ))
    ->add_fields(array(
        Field::create( 'radio', 'identificador','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
            '' => 'Nada',
            'icono' => 'Icono',
            'imagen' => 'Imagen',
        )),
        Field::create( 'icon', 'menu_item_icon')->add_set( 'font-awesome' )->add_dependency('identificador','icono','=')->hide_label(),
        Field::create( 'image', 'menu_item_image', 'Imágen' )->add_dependency('identificador','imagen','=')->hide_label(),
        Field::create( 'checkbox', 'is_megamenu' )->set_text('¿Activar Megamenú?')->hide_label(),
        // Field::create( 'select', 'megamenu_post', 'Menú' )->hide_label()->add_dependency('is_megamenu')->add_posts( 'megamenu' ),
        Field::create( 'wp_object', 'megamenu_post' )->add( 'posts', 'post_type=megamenu' )->set_button_text( 'Seleccione el megamenú' )->hide_label()->add_dependency('is_megamenu'),
        Field::create( 'select', 'visibility', 'Visibilidad')->add_options( array(
            '' => 'Visible para todos los usuarios',
            'is_private' => 'Solo visible para usuarios admin.',
            'user_is_logged_in' => 'Visible para usuarios registrados',
            'user_is_not_logged_in' => 'Visible para usuarios no registrados',
        ))->hide_label(),
        Field::create( 'select', 'style', 'Estilo')->add_options( array(
            '' => 'Estilo Normal',
            'add-border' => 'Agregar bordes',
            'add-color-1' => 'Agregar color principal',
            'add-color-2' => 'Agregar color secundario',
        ))->hide_label(),
        Field::create( 'checkbox', 'hide_label' )->set_text('¿Ocultar texto?')->hide_label(),
    ));