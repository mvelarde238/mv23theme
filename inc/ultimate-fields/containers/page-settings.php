<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$uf_posttypes = \array_diff(UF_POSTTYPES, DISABLE_PAGE_HEADER_IN);

Container::create( 'page_settings' )
    ->add_location( 'post_type', $uf_posttypes )
    // ->add_location( 'taxonomy', 'category' )
    // ->add_location( 'taxonomy', 'product_cat')
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'tab', 'Page' ),
        Field::create( 'complex', 'page_bgc', 'Color de fondo' )->set_width( 20 )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc')->hide_label(),
        )),
        Field::create( 'tab', 'Header' ),
        Field::create( 'select', 'header_theme', 'Color' )->add_options(array(
            'theme1' => 'Transparente con letras blancas',
            'theme2' => 'Negro con letras blancas',
            'theme3' => 'Corporativo con letras blancas',
            'theme4' => 'Transparente con letras negras',
            'theme5' => 'blanco con letras negras',
            'theme6' => 'Corporativo con letras negras',
        ))->set_default_value( HEADER_THEME ),
        Field::create( 'checkbox', 'hide_logo')->set_text('Ocultar el logo')->hide_label(),
        Field::create( 'checkbox', 'hide_menu')->set_text('Ocultar el menu')->hide_label(),
        Field::create( 'checkbox', 'replace_logo')->set_text('Cambiar Logo')->hide_label(),
        Field::create( 'image', 'header_logo' )->hide_label()->add_dependency('replace_logo')
    ));