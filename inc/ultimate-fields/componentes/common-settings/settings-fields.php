<?php
use Ultimate_Fields\Field;

$fields = array(
    Field::create( 'tab', 'Settings' )
);

$fields = array_merge($fields,$id_and_class);
$fields = array_merge($fields,$fondo_complex);

$fields[] = Field::create( 'complex', 'color_de_fondo' )->set_width( 20 )->add_fields(array(
    Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
    Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc'),
));
    
$fields[] = Field::create( 'select', 'color_scheme', 'Color del Texto' )->set_width( 20 )->add_options( array(
    '' => 'Seleccionar',
    'default-scheme' => 'Negro',
    'dark-scheme' => 'Blanco',
));

$settings_fields = $fields;