<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array(
    Field::create( 'tab', 'Contenido'),
    Field::create( 'textarea', 'slider_desktop' )->set_rows( 1 )->set_width( 50 ),
    Field::create( 'textarea', 'slider_movil' )->set_rows( 1 )->set_width( 50 ),
);

$slider_settings_fields = $settings_fields;
$slider_settings_fields[] = Field::create( 'select', 'layout')->set_width( 33 )->add_options( array(
    'layout1' => 'Estándar',
    'layout2' => 'Fondo extendido / Contenido centrado',
    'layout3' => 'Todo extendido',
));

$slider_args = array(
    'fields' => array_merge($fields, $slider_settings_fields, $margenes, $bordes, $box_shadow, $animation)
);

$slider = Repeater_Group::create( 'Slider', $slider_args );