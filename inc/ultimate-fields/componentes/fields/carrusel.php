<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array(
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'message','lel2')->set_description('Se recomienda usar imágenes del mismo tamaño.')->hide_label(),
    Field::create( 'repeater', 'items', '' )
    ->set_add_text('Agregar')
    ->add_group('Item', array(
        'fields' => array(
            Field::create( 'image', 'imagen' )->set_width( 25 ),
            Field::create( 'complex', 'enlace' )->rows_layout()->add_fields(array(
                Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
                        '' => 'Ninguno',
                        'interna' => 'Página Interna',
                        'externa' => 'Página Externa',
                        'popup' => 'Mostrar la imágen en un PopUp',
                )),
                Field::create( 'wp_object', 'post', 'URL Interna' )->add( 'posts' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
                Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
                Field::create( 'checkbox', 'new_tab' )->set_text( 'Abrir en una nueva ventana.' )->hide_label()->add_dependency('url_type','externa','='),
            ))->set_width( 75 )
        )
    )),
    Field::create( 'tab', 'Carrusel' ),
    Field::create( 'checkbox', 'show_controls' )->set_width( 25 )->set_text('Mostrar Flechas'),
    Field::create( 'checkbox', 'show_nav' )->set_width( 25 )->set_text('Mostrar indicadores de página'),
    Field::create( 'select', 'nav_position' )->add_options( array(
                'bottom' => 'Abajo',
                'top' => 'Arriba',
            ))->set_width( 25 )->add_dependency('show_nav'),
    Field::create( 'checkbox', 'autoplay' )->set_width( 25 )->set_text('Empezar automáticamente'),

    Field::create( 'section','lel','Cantidad de Items visibles'),
    Field::create( 'number', 'items_in_desktop', 'Items en desktop' )->set_default_value( '4' )->set_width( 25 ),
    Field::create( 'number', 'items_in_laptop', 'Items en laptop' )->set_default_value( '3' )->set_width( 25 ),
    Field::create( 'number', 'items_in_tablet', 'Items en tablet' )->set_default_value( '2' )->set_width( 25 ),
    Field::create( 'number', 'items_in_mobile', 'Items en móviles' )->set_default_value( '1' )->set_width( 25 ),

    Field::create( 'section','gat-betwwen-items','Espacio entre items'),
    Field::create( 'number', 'gutter_in_desktop', 'Gutter en desktop' )->set_default_value( '0' )->set_width( 25 ),
    Field::create( 'number', 'gutter_in_laptop', 'Gutter en laptop' )->set_default_value( '0' )->set_width( 25 ),
    Field::create( 'number', 'gutter_in_tablet', 'Gutter en tablet' )->set_default_value( '0' )->set_width( 25 ),
    Field::create( 'number', 'gutter_in_mobile', 'Gutter en móviles' )->set_default_value( '0' )->set_width( 25 ),
);

$carrusel_args = array(
    'fields' => array_merge($fields, $default_settings_fields)
);

$carrusel = Repeater_Group::create( 'Carrusel', $carrusel_args );