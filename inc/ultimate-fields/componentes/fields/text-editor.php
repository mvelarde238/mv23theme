<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 20 ),
);

$responsive_settings = array(
    Field::create( 'tab', 'Responsive' ),
    Field::create( 'select', 'tablet_text_align','Alineación del texto en Tablets')->add_options( array(
        '' => 'Seleccionar',
        'left' => 'Izquierda',
        'center' => 'Centro',
        'right' => 'Derecha',
    ))->set_width(50),
    Field::create( 'select', 'mobile_text_align','Alineación del texto en Móviles')->add_options( array(
        '' => 'Seleccionar',
        'left' => 'Izquierda',
        'center' => 'Centro',
        'right' => 'Derecha',
    ))->set_width(50),
);

$text_editor_args = array(
    'title' => 'Editor de Texto',
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields, $responsive_settings)
);

$text_editor = Repeater_Group::create( 'Editor de Texto', $text_editor_args );