<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array(
    Field::create( 'tab', 'Contenido'),
    Field::create( 'textarea', 'slider_desktop' )->set_rows( 1 )->set_width( 50 ),
    Field::create( 'textarea', 'slider_movil' )->set_rows( 1 )->set_width( 50 ),
);

$slider_args = array(
    'fields' => array_merge($fields, $settings_fields, $margenes, $bordes, $box_shadow, $animation)
);

$slider = Repeater_Group::create( 'Slider', $slider_args );