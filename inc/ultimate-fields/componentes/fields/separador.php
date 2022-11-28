<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$separador = Repeater_Group::create( 'Separador', array(
    'edit_mode' => 'popup',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'number', 'height', 'Tamaño de alto en pixeles.' )->set_default_value( '30' )
    )
))
->add_fields($settings_fields_container->get_fields());