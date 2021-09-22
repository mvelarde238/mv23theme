<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
    
Container::create( 'local_info' )
    ->add_location( 'taxonomy', 'location' )
    ->add_location( 'post_type', 'local' )
    ->set_layout( 'grid' )
    ->add_fields(array(
        Field::create( 'text', 'direccion' ),
        Field::create( 'repeater', 'telefonos', 'Teléfonos' )->set_width( 50 )->set_add_text('Agregar')->add_group('Número', array(
            'fields' => array(
                Field::create( 'text', 'numero' ),
            )
        )),
        Field::create( 'repeater', 'horarios')->set_width( 50 )->set_add_text('Agregar')->add_group('Número', array(
            'fields' => array(
                Field::create( 'text', 'descripcion', 'Descripción' )->set_width( 50 ),
                Field::create( 'text', 'horario' )->set_width( 50 ),
            )
        )),
        Field::create( 'map', 'location' )->set_output_width( '100%' )->set_output_height( 300 ),
    ));