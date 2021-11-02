<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$modulo_componentes_field = clone $componentes_field;
$modulo_componentes_field->add_group( $columnas );
if(CONTENT_SLIDER) $modulo_componentes_field->add_group( $content_slider );
if(COLUMNAS_SIMPLES) $modulo_componentes_field->add_group( $columnas_simples );
if(ITEMS_GRID) $modulo_componentes_field->add_group( $items_grid );
if(ROW) $modulo_componentes_field->add_group( $row );

$fields = array(
    Field::create( 'tab', 'Contenido' ),
    $modulo_componentes_field,
    Field::create( 'tab', 'Settings' ),
);

$nobug_id_and_class = $id_and_class;
$fields = array_merge($fields,$nobug_id_and_class);

$fields[] = Field::create( 'select', 'text_color', 'Color del texto' )->add_options( array(
    'text-color-default' => 'Negro',
    'text-color-2' => 'Blanco',
))->set_default_value( 'text-color-default' )->set_width( 33 );

$fields[] = Field::create( 'select', 'visibility', 'Visibilidad')->add_options( array(
    '' => 'Visible para todos los usuarios',
    'user_is_logged_in' => 'Visible para usuarios registrados',
    'user_is_not_logged_in' => 'Visible para usuarios no registrados',
    'is_private' => 'Solo visible para usuarios admin.',
))->set_width( 33 );

$fields[] = Field::create( 'select', 'layout')->add_options( array(
    'layout1' => 'Estándar',
    'layout2' => 'Fondo extendido / Contenido centrado',
    'layout3' => 'Todo extendido',
))->set_width( 33 );

$fields[] = Field::create( 'tab', 'Fondo del módulo' );

$nobug_fondo_complex = $fondo_complex;
$fields = array_merge($fields,$nobug_fondo_complex);

$others = array(
    Field::create( 'checkbox', 'add_bgc', 'Usar color de fondo' )->set_width( 20 )->set_text('Activar'),
    Field::create( 'color', 'bgc', 'Color de Fondo' )->set_width( 20 )->set_default_value('#ffffff')->add_dependency('add_bgc'),
    Field::create( 'checkbox', 'parallax', 'Parallax' )->set_width( 20 ),
    Field::create( 'tab', 'Márgenes' ),
    Field::create( 'complex', 'padding', 'Borrar Márgenes' )->set_width( 25 )->add_fields(array(
        Field::create( 'checkbox', 'top' )->set_width( 25 )->set_text('Superior')->hide_label(),
        Field::create( 'checkbox', 'bottom' )->set_width( 25 )->set_text('Inferior')->hide_label(),
    )),
);
$fields = array_merge($fields,$others);

$modulos = Repeater_Group::create( 'Módulos' )
    ->set_title( 'Módulo' )
    ->set_icon( 'dashicons dashicons-welcome-widgets-menus' )
    ->add_fields($fields);