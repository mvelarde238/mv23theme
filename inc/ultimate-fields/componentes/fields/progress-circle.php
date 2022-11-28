<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$progress_circle = Repeater_Group::create( 'Progress Circle', array(
    'edit_mode' => 'popup',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'number', 'percentage', 'Porcentaje' )->enable_slider( 1, 100 )->set_default_value(23),
    ),
))
->add_fields($settings_fields_container->get_fields());