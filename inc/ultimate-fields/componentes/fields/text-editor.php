<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$text_editor = Repeater_Group::create( 'Editor de Texto', array(
    'edit_mode' => 'popup',
    // 'title_template' => '<%= content %>', // breaks column width
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 20 ),
        Field::create( 'tab', 'Mobile Options' ),
        Field::create( 'multiselect', 'theme_clases', 'Helpers' )->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options(array(
            'hide-br' => 'Ocultar saltos de línea en tablet y móviles',
            'hide-br-tablet' => 'Ocultar saltos de línea en tablet',
            'hide-br-mobile' => 'Ocultar saltos de línea en móviles',
        ))->hide_label(),
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
    ),
))
->add_fields($settings_fields_container->get_fields());