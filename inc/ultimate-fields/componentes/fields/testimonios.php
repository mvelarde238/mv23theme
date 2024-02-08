<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$testimonios = Repeater_Group::create( 'Testimonios', array(
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'repeater', 'testimonios' )->set_add_text('Agregar')->add_group( 'testimonio', array(
            'edit_mode' => 'popup',
            'title_template' => '<%= type %>',
            'fields' => array(
                Field::create( 'radio', 'type', 'Tipo de testimonio:')->set_orientation('horizontal')->add_options(array(
                    'text' => 'Texto', 'video' => 'Video'
                )),
                Field::create( 'image', 'author_img' )->add_dependency('type', 'text', '=')->set_width(25),
                Field::create( 'wysiwyg', 'author' )->add_dependency('type', 'text', '=')->set_width(70),
                Field::create( 'wysiwyg', 'comment' )->add_dependency('type', 'text', '='),
                Field::create( 'video', 'video' )->add_dependency('type', 'video', '=')->set_width(25),
            )
        )),
        Field::create( 'tab', 'Columnas' ),
        Field::create( 'number', 'cols_in_desktop', 'Columnas en desktop' )->set_default_value( '4' )->set_width( 25 )->set_minimum(1)->set_maximum(4),
        Field::create( 'number', 'cols_in_tablet', 'Columnas en tablet' )->set_default_value( '2' )->set_width( 25 )->set_minimum(1)->set_maximum(4),
        Field::create( 'number', 'cols_in_mobile', 'Columnas en móviles' )->set_default_value( '1' )->set_width( 25 )->set_minimum(1)->set_maximum(4),
    )
))
->add_fields($settings_fields_container->get_fields());