<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$uf_posttypes = \array_diff(UF_POSTTYPES, DISABLE_PAGE_HEADER_IN);

Container::create( 'page_header' )
    ->add_location( 'post_type', $uf_posttypes )
    ->add_location( 'taxonomy', 'category' )
    // ->add_location( 'taxonomy', 'product_cat')
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'section', 'page_header_section', 'Page Header' )->set_color( 'blue' ),
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'radio', 'page_header_element','Seleccione que tipo de contenido se va mostrar:')->set_orientation( 'horizontal' )->add_options( array(
            'default' => 'Título de la página',
            'slider' => 'Slider',
            'contenido' => 'Contenido',
            'ninguno' => 'Ninguno',
        ) ),
        // Field::create( 'wp_objects', 'posts', '' )->add( 'posts', 'posts' )->set_button_text( 'Selecciona los posts' )->add_dependency('page_header_element','siempre-hidden-mv23','='),
        Field::create( 'textarea', 'slider_desktop' )->set_rows( 1 )->set_width( 50 )->add_dependency('page_header_element','slider','='),
        Field::create( 'textarea', 'slider_movil' )->set_rows( 1 )->set_width( 50 )->add_dependency('page_header_element','slider','='),
        Field::create( 'wysiwyg', 'page_header_content' )->hide_label()->set_rows( 1 )->add_dependency('page_header_element','contenido','='),
        Field::create( 'tab', 'Settings' ),
        Field::create( 'text', 'page_header_id', 'ID' )->set_width( 50 )->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
        ->set_description( 'Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )' ),
        Field::create( 'text', 'page_header_class', 'Clases' )->set_width( 50 )
        ->set_description( 'Clases de la sección, usar solo minúsculas y guiones ( -/_ )' ),
        Field::create( 'select', 'page_header_text_color', 'Color del texto' )->set_width( 33 )->add_options( array(
            'text-color-2' => 'Blanco',
            'text-color-default' => 'Negro',
        ) )->set_default_value( PAGE_HEADER_TEXT_COLOR ),
        Field::create( 'checkbox', 'page_header_padding', 'Márgenes' )->set_width( 33 )->set_text('Borrar Márgenes'),
        Field::create( 'select', 'page_header_layout')->set_width( 33 )->add_options( array(
            'layout2' => 'Fondo extendido / Contenido centrado',
            'layout1' => 'Estándar',
            'layout3' => 'Todo extendido',
        ) ),
        Field::create( 'tab', 'Fondo del módulo' ),
        Field::create( 'image', 'page_header_bgi', 'Imágen de Fondo' )->set_width( 33 )->set_default_value( PAGE_HEADER_BGI ),
        Field::create( 'color', 'page_header_bgc', 'Color de Fondo' )->set_width( 33 )->set_default_value( PAGE_HEADER_BGC ),
        Field::create( 'radio', 'page_header_bgi_parallax', 'Parallax' )->set_width( 33 )->add_options( array(
            '0' => 'Desactivar',
            '1' => 'Activar',
        ) )->set_orientation( 'horizontal' )->set_default_value('0'),
        Field::create( 'tab', 'Header Options' ),
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