<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'Member Info' )
    ->add_location( 
        'post_type', 'equipo', 
        array( 
            'context' => 'side' 
        ) 
    )
    ->set_layout( 'grid' )
    ->add_fields(array(
        Field::create( 'text', 'email' ),
        Field::create( 'repeater', 'phone_numbers', 'Teléfonos' )->set_add_text('Agregar')->add_group('Número', array(
            'fields' => array(
                Field::create( 'text', 'number' ),
            )
        ))
    ));