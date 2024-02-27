<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$content_group_fields = array_merge(
    array(
        Field::create( 'tab', 'Contenido' ),
        Content_Layout::the_field(array( 
            'slug' => 'content_layout', 
            'components' => array( 'Editor de Texto', 'Imágen', 'Componente Reusable', 'Mapa', 'Button', 'HTML', 'Separador' )
        ))
    ),
    $settings_fields
);

$carrusel = Repeater_Group::create( 'Carrusel', array(
    'fields' => array(
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'repeater', 'items', '' )
        ->set_add_text('Agregar')
        ->set_chooser_type( 'dropdown' )
        ->add_group('Content', array(
            'title' => 'Contenido',
            'edit_mode' => 'popup',
            'fields' => $content_group_fields
        ))
        ->add_group('Item', array(
            'title' => 'Imágen',
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
        Field::create( 'checkbox', 'show_controls' )->set_text('Mostrar Flechas')->set_width( 10 ),
        Field::create( 'checkbox', 'show_nav' )->set_text('Mostrar indicadores de página')->set_width( 10 ),
        Field::create( 'select', 'nav_position' )->add_options( array(
                    'bottom' => 'Abajo',
                    'top' => 'Arriba',
                ))->add_dependency('show_nav')->set_width( 10 ),
        Field::create( 'checkbox', 'autoplay' )->set_text('Empezar automáticamente')->set_width( 10 ),
        Field::create( 'checkbox', 'auto_height' )->set_text('Activar')->set_width( 10 ),
        Field::create( 'select', 'axis', 'Axis' )->add_options( array(
            'horizontal' => 'Horizontal',
            'vertical' => 'Vertical',
        ))->set_width( 10 ),
    
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

        Field::create( 'section','tamanio-de-imagenes','Tamaño de imágenes'),
        Field::create( 'select', 'imgs_height', 'Seleccionar:' )->add_options( array(
            'auto' => 'Automático',
            'custom' => 'Personalizar',
        ))->set_width( 25 ),
        Field::create( 'number', 'img_max_height', 'Tamaño de alto máximo en pixeles' )->set_default_value( '60' )->add_dependency('imgs_height','custom','=')->set_width( 25 ),
    ),
))
->add_fields($settings_fields_container->get_fields());