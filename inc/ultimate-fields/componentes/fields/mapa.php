<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array(
    Field::create( 'tab', 'Contenido'),
    // Field::create( 'text', 'lat', 'Latitud' )->set_width( 33 ),
    // Field::create( 'text', 'lng', 'Longitud' )->set_width( 33 ),
    Field::create( 'map', 'location' )->set_output_width( '100%' )->set_output_height( 300 ),
    Field::create( 'image', 'icono' )->set_width( 33 ),
);

$mapa_args = array(
    'fields' => array_merge($fields, $default_settings_fields)
);

$mapa = Repeater_Group::create( 'Mapa', $mapa_args );