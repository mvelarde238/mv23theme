<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Core;

Container::create( 'page_settings' )
    ->add_location( 'post_type', PAGE_SETTINGS_POSTTYPES )
    ->add_fields(array(
        Field::create( 'tab', 'Page' ),
        Field::create( 'complex', 'page_bgc', 'Color de fondo' )->set_width( 20 )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc')->hide_label(),
        )),
        Field::create( 'select', 'page_color_scheme', 'Color del Texto' )->set_width( 20 )->add_options( array(
            '' => 'Seleccionar',
            'default-scheme' => 'Negro',
            'dark-scheme' => 'Blanco',
        ))->set_default_value(DEFAULT_COLOR_SCHEME),

        Field::create( 'tab', 'Static Header' ),
        Field::create( 'checkbox', 'hide_static_header', 'Ocultar')->set_text('Ocultar el header estático'),
        Field::create( 'checkbox', 'hide_static_header_logo', 'Ocultar el logo')->set_text('Ocultar el logo'),
        Field::create( 'checkbox', 'custom_static_header', 'Personalizar')->set_text('Personalizar el header estático'),
        Field::create( 'select', 'static_header_logo', 'Versión del Logo')
            ->add_options( Core::getInstance()->get_logos_field_names() )
            ->add_dependency('custom_static_header'),
        Field::create( 'image', 'custom_static_header_logo', 'Seleccionar logo' )->add_dependency('static_header_logo','custom','=')->add_dependency('custom_static_header'),
        Field::create( 'complex', 'static_header_bgc', 'Color de fondo' )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc'),
            Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
        ))->add_dependency('custom_static_header'),
        Field::create( 'select', 'static_header_color_scheme', 'Color del Texto' )->add_options( array(
            '' => 'Default',
            'text-color-1' => 'Negro',
            'text-color-2' => 'Blanco',
        ))->set_default_value( DEFAULT_TEXT_COLOR )->add_dependency('custom_static_header'),
    
        Field::create( 'tab', 'Sticky Header' ),
        Field::create( 'checkbox', 'hide_sticky_header', 'Ocultar')->set_text('Ocultar el sticky header'),
        Field::create( 'checkbox', 'hide_sticky_header_logo', 'Ocultar el logo')->set_text('Ocultar el logo'),
        Field::create( 'checkbox', 'custom_sticky_header', 'Personalizar')->set_text('Personalizar el sticky header'),
        Field::create( 'select', 'sticky_header_logo', 'Versión del Logo')
            ->add_options( Core::getInstance()->get_logos_field_names() )
            ->add_dependency('custom_sticky_header'),
        Field::create( 'image', 'custom_sticky_header_logo', 'Seleccionar logo' )->add_dependency('sticky_header_logo','custom','=')->add_dependency('custom_sticky_header'),
        Field::create( 'complex', 'sticky_header_bgc', 'Color de fondo' )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->set_default_value(1),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc')->set_default_value('#ffffff'),
            Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
        ))->add_dependency('custom_sticky_header'),
        Field::create( 'select', 'sticky_header_color_scheme', 'Color del Texto' )->add_options( array(
            '' => 'Default',
            'text-color-1' => 'Negro',
            'text-color-2' => 'Blanco',
        ))->set_default_value( DEFAULT_TEXT_COLOR )->add_dependency('custom_sticky_header'),
    ));