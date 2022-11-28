<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$slider = Repeater_Group::create( 'Slider', array(
    'fields' => array(
        Field::create( 'tab', 'Contenido'),
        Field::create( 'textarea', 'slider_desktop' )->set_rows( 1 )->set_width( 50 ),
        Field::create( 'textarea', 'slider_movil' )->set_rows( 1 )->set_width( 50 ),
    )
))
->add_fields($settings_fields_container->get_fields());