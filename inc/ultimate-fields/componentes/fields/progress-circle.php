<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'number', 'percentage', 'Porcentaje' )->enable_slider( 1, 100 )->set_default_value(23),
);

$progress_circle_args = array(
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields)
);

$progress_circle = Repeater_Group::create( 'Progress Circle', $progress_circle_args );