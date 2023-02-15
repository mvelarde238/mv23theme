<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$content_slider_componentes_field = clone $components_repeater;
$content_slider_componentes_field->add_group( $columnas_internas );

$content_slider = Repeater_Group::create( 'Slider de Contenidos' )
// ->set_title( 'Slider de Contenidos' )
// ->set_edit_mode( 'popup' )
->add_fields(array(
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'repeater', 'content_slider', 'Slider de Contenidos' )
        ->set_add_text('Agregar Slide')
        ->hide_label()
        ->add_group('Item', array(
            'edit_mode' => 'popup',
            'fields' => array(
                Field::create( 'tab', 'Contenido' ),
                $content_slider_componentes_field,
                Field::create( 'tab', 'Settings' ),
                Field::create( 'text', 'title', 'Título para el menú' )->set_width( 25 ),
                Field::create( 'image', 'bgi', 'Imágen de Fondo' )->set_width( 25 ),
                Field::create( 'complex', 'bgi_options', '' )->set_width( 25 )->add_fields(array(
                    Field::create( 'select', 'size', 'Tamaño')->add_options( array(
                        'auto' => 'Automático',
                        'cover' => 'Cubrir Todo',
                    ) ),
                    Field::create( 'select', 'repeat', 'Repetir')->add_options( array(
                        'repeat' => 'Ambas direcciones',
                        'repeat-x' => 'Solo horizontal',
                        'repeat-y' => 'Solo en vertical',
                        'no-repeat' => 'No Repetir',
                    ) ),
                    Field::create( 'select', 'position_x', 'Posición Eje Horizontal')->add_options( array(
                        'left' => 'Izquierda',
                        'center' => 'Centro',
                        'right' => 'Derecha',
                    ) ),
                    Field::create( 'select', 'position_y', 'Posición Eje Vertical')->add_options( array(
                        'top' => 'Arriba',
                        'center' => 'Centro',
                        'bottom' => 'Abajo',
                    ) ),
                ))->add_dependency('bgi','0','>'),
                Field::create( 'complex', 'color_de_fondo' )->set_width( 25 )->add_fields(array(
                    Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
                    Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc'),
                )),
                Field::create( 'select', 'color_scheme', 'Color del Texto' )->set_width( 25 )->add_options( array(
                    '' => 'Seleccionar',
                    'default-scheme' => 'Negro',
                    'dark-scheme' => 'Blanco',
                )),
            )
    )),

    Field::create( 'tab', 'Navigation' ),
    Field::create( 'checkbox', 'show_nav' )->set_width( 25 )->set_text('Mostrar indicadores de página')->set_default_value('1'),
    Field::create( 'select', 'nav_position', 'Posición')->set_width( 25 )->add_options( array(
        'bottom' => 'Abajo',
        'top' => 'Arriba',
    ))->add_dependency('show_nav'),
    Field::create( 'checkbox', 'nav_show_title', 'Mostrar Títulos')->set_width( 25 )->set_text('Activar')->add_dependency('show_nav'),
    Field::create( 'checkbox', 'scroll_to_top', 'Scroll to top' )->set_width( 25 )->set_text('Activar'),
    Field::create( 'tab', 'Controles' ),
    Field::create( 'checkbox', 'show_controls' )->set_width( 50 )->set_text('Mostrar Flechas')->set_default_value('1'),
    Field::create( 'select', 'controls_position', 'Posición')->set_width( 50 )->add_options( array(
        'bottom' => 'Abajo',
        'center' => 'Al centro',
        'top' => 'Arriba',
    ))->add_dependency('show_controls'),
    Field::create( 'checkbox', 'extender_fondo', 'Aplicar el fondo de los items al componente' )->set_width( 25 )->set_text('Activar')
))
->add_fields($settings_fields_container->get_fields());