<?php
use Ultimate_Fields\Field;

$fields = array_merge(
    array( Field::create( 'tab', 'Settings' ) ),
    $id_and_class,
    $fondo_complex
);

$fields[] = Field::create( 'complex', 'color_de_fondo' )->set_width( 20 )->add_fields(array(
    Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
    Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc'),
));
    
$fields[] = Field::create( 'select', 'color_scheme', 'Color del Texto' )->set_width( 20 )->add_options( array(
    '' => 'Seleccionar',
    'default-scheme' => 'Negro',
    'dark-scheme' => 'Blanco',
));

$fields[] = Field::create( 'select', 'layout')->set_width( 33 )->add_options( array(
    'layout1' => 'Estándar',
    'layout2' => 'Fondo extendido / Contenido centrado',
    'layout3' => 'Todo extendido',
    'layout4' => 'Extender columnas laterales',
));

$settings_fields = $fields;