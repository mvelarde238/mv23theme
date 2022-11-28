<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$mapa = Repeater_Group::create( 'Mapa', array(
    'fields' => array(
        Field::create( 'tab', 'Contenido'),
        // Field::create( 'text', 'lat', 'Latitud' )->set_width( 33 ),
        // Field::create( 'text', 'lng', 'Longitud' )->set_width( 33 ),
        Field::create( 'map', 'location' )->set_output_width( '100%' )->set_output_height( 280 ),
        Field::create( 'image', 'icono' )->set_width( 50 ),
        Field::create( 'number', 'height', 'Alto en píxeles' )->set_width( 50 )->set_default_value( 280 ),
    ),
))
->add_fields($settings_fields_container->get_fields());