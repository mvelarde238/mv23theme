<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Common_Settings;

return Container::create( '_main-settings' )
    ->add_fields( array( 
        Field::create( 'tab', 'Settings' ) 
    ))
    ->add_fields( Common_Settings::get_fields('id-and-class') )
    ->add_fields( Common_Settings::get_fields('background-image') )
    ->add_fields( array( 
        Field::create( 'complex', 'color_de_fondo' )->set_width( 20 )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc'),
        )), 
        Field::create( 'select', 'color_scheme', 'Color del Texto' )->set_width( 20 )->add_options( array(
            '' => 'Seleccionar',
            'default-scheme' => 'Negro',
            'dark-scheme' => 'Blanco',
        ))->set_default_value(DEFAULT_COLOR_SCHEME),
        Field::create( 'select', 'layout')->set_width( 33 )->add_options( array(
            'layout1' => 'Estándar',
            'layout2' => 'Fondo extendido / Contenido centrado',
            'layout3' => 'Todo extendido'
        ))
    ));