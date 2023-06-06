<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$button = Repeater_Group::create( 'Button', array(
    'title' => 'Botón',
    'layout' => 'table',
    'edit_mode' => 'popup',
    'title_template' => '<%= content %>',
    'fields' => array(
        Field::create( 'tab', 'Contenido' ), 
        Field::create( 'text', 'text', 'Texto del botón')->set_width(25),
        Field::create( 'select', 'style', 'Estilo')->add_options( array(
            '' => 'Link',
            'btn' => 'Botón Simple',
            'btn btn--main-color' => 'Botón Corporativo 1',
            'btn btn--secondary-color' => 'Botón Corporativo 2'
        ))->set_default_value('btn btn--main-color')->set_width(25),
        Field::create( 'radio', 'alignment', 'Alineación')->add_options( array(
            'left' => 'Izquierda',
            'center' => 'Centro',
            'right' => 'Derecha'
        ))->set_orientation( 'horizontal' )->set_width(25),
        Field::create( 'checkbox', 'fullwidth','Botón de ancho completo' )->set_text( 'Activar' )->set_width(25),

        Field::create( 'radio', 'type','Tipo')->set_orientation( 'horizontal' )->add_options( array(
            'link' => 'Link',
            'download' => 'Descarga',
        ))->set_width(25),

        Field::create( 'file', 'file', 'File' )->add_dependency('type','download','=')->set_width(25),

        Field::create( 'radio', 'url_type','Origen:')->set_orientation( 'horizontal' )->add_options( array(
            'interna' => 'Página Interna',
            'externa' => 'Página Externa',
        ))->add_dependency('type','link','=')->set_width(25),
        Field::create( 'wp_object', 'post', 'URL Interna' )->set_button_text( 'Selecciona la página' )->add_dependency('type','link','=')->add_dependency('url_type','interna','=')->set_width(25),
        Field::create( 'text', 'url', 'URL Externa' )->add_dependency('type','link','=')->add_dependency('url_type','externa','=')->set_width(25),

        Field::create( 'checkbox', 'new_tab', 'Abrir en una nueva ventana' )->set_text( 'Activar' )->set_width(25),

        Field::create( 'tab', 'Mobile Options' ),
        Field::create( 'checkbox', 'add_responsive' )->set_text( 'Cambiar alineación en móviles' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
        Field::create( 'select', 'tablet_text_align','Alineación en Tablets')->add_options( array(
            '' => 'Seleccionar',
            'left' => 'Izquierda',
            'center' => 'Centro',
            'right' => 'Derecha',
        ))->set_width(50)->add_dependency('add_responsive'),
        Field::create( 'select', 'mobile_text_align','Alineación en Móviles')->add_options( array(
            '' => 'Seleccionar',
            'left' => 'Izquierda',
            'center' => 'Centro',
            'right' => 'Derecha',
        ))->set_width(50)->add_dependency('add_responsive'),

        Field::create( 'tab', 'Attributes' ),
        Field::create( 'repeater', 'attributes', 'Attributos' )->set_add_text('Agregar')->hide_label()
            ->set_layout( 'table' )
            ->add_group('item', array(
                'title_template' => '<%= attribute %> : <%= value %>',
                'fields' => array(
                    Field::create( 'text', 'attribute' )->set_width( 50 ),
                    Field::create( 'text', 'value' )->set_width( 50 ),
                )
            ))
    ), 
))
->add_fields($settings_fields_container->get_fields());