<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'wysiwyg', 'text', 'Texto' )->hide_label()->set_rows( 2 ),
    Field::create( 'number', 'percentage', 'Porcentaje' )->enable_slider( 1, 100 )->set_default_value(23),
);

$progress_bar_args = array(
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields)
);

$progress_bar = Repeater_Group::create( 'Progress Bar', $progress_bar_args );