<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Core;

Container::create('header_options')
    ->add_location('options', 'header-options')
    ->add_fields( array(
        Field::create('tab', 'Static Header'),
        Field::create('select', 'static_header_logo', 'Versión del Logo')
            ->add_options( Core::getInstance()->get_logos_field_names() ),
        Field::create('image', 'custom_static_header_logo', 'Seleccionar logo')->add_dependency('static_header_logo', 'custom', '='),
        Field::create('complex', 'static_header_bgc', 'Color de fondo')->add_fields(array(
            Field::create('checkbox', 'add_bgc', 'Activar')->set_width(25)->set_text('Activar'),
            Field::create('color', 'bgc', 'Color')->set_width(50)->add_dependency('add_bgc'),
            Field::create('text', 'alpha', 'Transparencia')->set_width(25)->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
        )),
        Field::create('select', 'static_header_color_scheme', 'Color del Texto')->add_options(array(
            '' => 'Default',
            'text-color-1' => 'Negro',
            'text-color-2' => 'Blanco',
        ))->set_default_value(DEFAULT_TEXT_COLOR),
    
        Field::create('tab', 'Sticky Header'),
        Field::create('select', 'sticky_header_logo', 'Versión del Logo')
        ->add_options( Core::getInstance()->get_logos_field_names() ),
        Field::create('image', 'custom_sticky_header_logo', 'Seleccionar logo')->add_dependency('sticky_header_logo', 'custom', '='),
        Field::create('complex', 'sticky_header_bgc', 'Color de fondo')->add_fields(array(
            Field::create('checkbox', 'add_bgc', 'Activar')->set_width(25)->set_text('Activar')->set_default_value(1),
            Field::create('color', 'bgc', 'Color')->set_width(50)->add_dependency('add_bgc')->set_default_value('#ffffff'),
            Field::create('text', 'alpha', 'Transparencia')->set_width(25)->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
        )),
        Field::create('select', 'sticky_header_color_scheme', 'Color del Texto')->add_options(array(
            '' => 'Default',
            'text-color-1' => 'Negro',
            'text-color-2' => 'Blanco',
        ))->set_default_value(DEFAULT_TEXT_COLOR),
    ));
