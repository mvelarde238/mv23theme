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
        Field::create( 'complex', 'height')->hide_label()->add_fields(array(
            Field::create( 'number', 'height', 'Altura' )->set_width( 50 )->set_default_value( 280 ),
            Field::create( 'text', 'unit', 'Medida (px,%,vh..)' )->set_width( 50 )->set_default_value( 'px' ),
        ))->set_width( 50 ),

        Field::create( 'tab', 'Info Window'),
        Field::create( 'wysiwyg', 'info' )->hide_label()->set_rows( 20 ),
    ),
))
->add_fields($settings_fields_container->get_fields());