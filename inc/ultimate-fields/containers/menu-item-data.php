<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'menu-item-data' )
    ->set_title( 'Settings' )
    ->add_location( 'menu_item', array(
        // 'levels' => 1,
        // 'theme_locations' => __locations_multilingual_support( MENU_ITEM_DATA_LOCATIONS ),
        'popup_mode' => true
    ))
    ->add_fields(array(
        Field::create( 'radio', 'identificador','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
            '' => 'Nada',
            'icono' => 'Icono',
            'imagen' => 'Imagen',
        ))->set_width(30),
        Field::create( 'icon', 'menu_item_icon', 'Ícono')->add_set( 'font-awesome' )->add_dependency('identificador','icono','='),
        Field::create( 'image', 'menu_item_image', 'Imágen' )->add_dependency('identificador','imagen','='),
        Field::create( 'checkbox', 'hide_label', 'Label' )->set_text('¿Ocultar texto?')->fancy(),
        Field::create( 'complex', 'menu_item_style', __('Style','default') )->set_layout('rows')->add_fields(array(
            Field::create( 'complex', 'background_color', __('Background color', 'default') )->add_fields(array(
                Field::create( 'checkbox', 'use' )->fancy()->hide_label()->set_width( 20 ),
                Field::create( 'color', 'color' )->add_dependency('use')->hide_label()->set_width( 30 )
                // Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 30 )
            )),
            Field::create( 'complex', 'text_color', __('Text color', 'default') )->add_fields(array(
                Field::create( 'checkbox', 'use' )->fancy()->hide_label()->set_width( 20 ),
                Field::create( 'color', 'color' )->add_dependency('use')->hide_label()->set_width( 30 )
            )),
            Field::create( 'complex', 'radius', __('Border radius', 'default') )->add_fields(array(
                Field::create( 'checkbox', 'use' )->fancy()->hide_label()->set_width( 20 ),
                Field::create( 'number', 'value' )->set_suffix('px')->set_default_value(3)->add_dependency('use')->hide_label()->set_width( 30 )
            )),
            Field::create( 'complex', 'min_width', __('Min Width', 'default') )->add_fields(array(
                Field::create( 'checkbox', 'use' )->fancy()->hide_label()->set_width( 20 ),
                Field::create( 'number', 'value' )->set_suffix('px')->set_default_value(48)->add_dependency('use')->hide_label()->set_width( 30 )
            )),
            Field::create( 'complex', 'border', __('Border', 'default') )->add_fields(array(
                Field::create( 'checkbox', 'use' )->fancy()->hide_label()->set_width( 20 ),
                Field::create( 'number', 'width' )->set_suffix('px')->set_default_value(1)->add_dependency('use')->hide_label()->set_width( 30 ),
                Field::create( 'color', 'color' )->add_dependency('use')->set_default_value('')->hide_label()->set_width( 30 )
            )),
            Field::create( 'complex', 'shadow', __('Box shadow', 'default') )->add_fields(array(
                Field::create( 'checkbox', 'use' )->fancy()->hide_label()->set_width( 20 ),
                Field::create( 'number', 'blur' )->set_suffix('px')->set_default_value(2)->add_dependency('use')->hide_label()->set_width( 30 ),
                Field::create( 'color', 'color' )->set_default_value('#232323')->add_dependency('use')->hide_label()->set_width( 30 )
            ))
        )),
        Field::create( 'select', 'visibility', 'Visibilidad')->add_options( array(
            '' => 'Visible para todos los usuarios',
            'is_private' => 'Solo visible para usuarios admin.',
            'user_is_logged_in' => 'Visible para usuarios registrados',
            'user_is_not_logged_in' => 'Visible para usuarios no registrados',
        ))->set_width(30),
        Field::create( 'wp_object', 'offcanvas_element', __('OffCanvas Element','default') )->add( 'posts','post_type=offcanvas_element' )->set_button_text( __('Select', 'deafult') )
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