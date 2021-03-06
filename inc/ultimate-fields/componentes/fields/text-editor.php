<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 20 ),
);

$responsive_settings = array(
    // Field::create( 'tab', 'Responsive' ),
    Field::create( 'checkbox', 'add_responsive' )->set_text( 'Cambiar alineación de textos en móviles' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
    Field::create( 'select', 'tablet_text_align','Alineación del texto en Tablets')->add_options( array(
        '' => 'Seleccionar',
        'left' => 'Izquierda',
        'center' => 'Centro',
        'right' => 'Derecha',
    ))->set_width(50)->add_dependency('add_responsive'),
    Field::create( 'select', 'mobile_text_align','Alineación del texto en Móviles')->add_options( array(
        '' => 'Seleccionar',
        'left' => 'Izquierda',
        'center' => 'Centro',
        'right' => 'Derecha',
    ))->set_width(50)->add_dependency('add_responsive'),
);

$text_editor_args = array(
    'title' => 'Editor de Texto',
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields, $responsive_settings),
    'title_template' => '<%= content %>'
);

$text_editor = Repeater_Group::create( 'Editor de Texto', $text_editor_args );