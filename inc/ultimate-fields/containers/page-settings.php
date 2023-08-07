<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

// $uf_posttypes = \array_diff(UF_POSTTYPES, DISABLE_PAGE_HEADER_IN);
$uf_posttypes = PAGE_SETTINGS_POSTTYPES;

Container::create( 'page_settings' )
    ->add_location( 'post_type', $uf_posttypes )
    // ->add_location( 'taxonomy', 'category' )
    // ->add_location( 'taxonomy', 'product_cat')
    // ->set_layout( 'grid' )
    // ->set_style( 'seamless' )
    ->add_fields(array(
        // Field::create( 'section', 'page_settings_section', 'Page Settings' )->set_color( 'blue' ),
        Field::create( 'tab', 'Page' ),
        Field::create( 'complex', 'page_bgc', 'Color de fondo' )->set_width( 20 )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc')->hide_label(),
        )),

        Field::create( 'tab', 'Header' ),
        Field::create( 'checkbox', 'custom_fixed_header')->set_text('Personalizar Header Fijo')->hide_label(),
        Field::create( 'checkbox', 'custom_floating_header')->set_text('Personalizar Header Flotante')->hide_label(),
        Field::create( 'checkbox', 'hide_logo')->set_text('Ocultar el logo')->hide_label(),
        Field::create( 'checkbox', 'replace_logo')->set_text('Cambiar Logo')->hide_label(),
        Field::create( 'image', 'header_logo' )->hide_label()->add_dependency('replace_logo'),

        Field::create( 'tab', 'Header Fijo' )->add_dependency('custom_fixed_header'),
        Field::create( 'select', 'fixed_header_logo', 'Versión del Logo')->add_options($logos_field_names),
        Field::create( 'complex', 'fixed_header_bgc', 'Color de fondo' )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc'),
            Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
        )),
        Field::create( 'select', 'fixed_header_color_scheme', 'Color del Texto' )->add_options( array(
            'text-color-default' => 'Negro',
            'text-color-2' => 'Blanco',
        ))->set_default_value( DEFAULT_TEXT_COLOR ),
    
        Field::create( 'tab', 'Header Flotante' )->add_dependency('custom_floating_header'),
        Field::create( 'select', 'floating_header_logo', 'Versión del Logo')->add_options($logos_field_names),
        Field::create( 'complex', 'floating_header_bgc', 'Color de fondo' )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->set_default_value(1),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc')->set_default_value('#ffffff'),
            Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
        )),
        Field::create( 'select', 'floating_header_color_scheme', 'Color del Texto' )->add_options( array(
            'text-color-default' => 'Negro',
            'text-color-2' => 'Blanco',
        ))->set_default_value( DEFAULT_TEXT_COLOR ),
    ));