<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'menu-item-data' )
    ->set_title( 'Settings' )
    ->add_location( 'menu_item', array(
        'levels' => 1,
        'theme_locations' => __locations_multilingual_support( MENU_ITEM_DATA_LOCATIONS ),
        'popup_mode' => true
    ))
    ->add_fields(array(
        Field::create( 'radio', 'identificador','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
            '' => 'Nada',
            'icono' => 'Icono',
            'imagen' => 'Imagen',
        ))->set_width(30),
        Field::create( 'icon', 'menu_item_icon', 'Ícono')->add_set( 'font-awesome' )->add_dependency('identificador','icono','=')->set_width(30),
        Field::create( 'image', 'menu_item_image', 'Imágen' )->add_dependency('identificador','imagen','=')->set_width(30),

        Field::create( 'select', 'visibility', 'Visibilidad')->add_options( array(
            '' => 'Visible para todos los usuarios',
            'is_private' => 'Solo visible para usuarios admin.',
            'user_is_logged_in' => 'Visible para usuarios registrados',
            'user_is_not_logged_in' => 'Visible para usuarios no registrados',
        ))->set_width(30),
        Field::create( 'select', 'style', 'Estilo')->add_options( array(
            '' => 'Estilo Normal',
            'add-border' => 'Agregar bordes',
            'add-color-1' => 'Agregar color principal',
            'add-color-2' => 'Agregar color secundario',
        ))->set_width(30),
        Field::create( 'checkbox', 'hide_label', 'Label' )->set_text('¿Ocultar texto?')->set_width(30),
    ));

Container::create( 'menu-item-megamenu' )
    ->set_title( 'Megamenú' )
    ->add_location( 'menu_item', array(
        'levels' => 1,
        'theme_locations' => __locations_multilingual_support( MENU_ITEM_MEGAMENU_LOCATIONS ),
        'popup_mode' => true
    ))
    ->add_fields(array(
        Field::create( 'checkbox', 'is_megamenu', 'Activar' )->set_text('¿Activar Megamenú?'),
        Field::create( 'wp_object', 'megamenu_post' )->add( 'posts', 'post_type=megamenu' )->set_button_text( 'Seleccione el megamenú' )->add_dependency('is_megamenu'),
    ));