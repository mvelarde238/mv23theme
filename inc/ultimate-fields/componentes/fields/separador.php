<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'number', 'height', 'Tamaño de alto en pixeles.' )->set_default_value( '30' )
);

$separador_args = array(
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields)
);

$separador = Repeater_Group::create( 'Separador', $separador_args );