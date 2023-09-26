<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$separador = Repeater_Group::create( 'Separador', array(
    'edit_mode' => 'popup',
    'title_template' => '<%= height %><%= unit %>',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'number', 'height', 'Tamaño de alto' )->set_default_value( '30' )->set_width( 50 ),
        Field::create( 'text', 'unit', 'Medida (px,%,vh..)' )->set_default_value( 'px' )->set_width( 50 ),
    )
))
->add_fields($settings_fields_container->get_fields());