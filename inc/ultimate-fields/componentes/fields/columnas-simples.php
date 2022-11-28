<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$columnas_simples = Repeater_Group::create( 'Columnas Simples', array(
    'title' => 'Columnas Simples',
    'edit_mode' => 'popup',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Content_Layout::the_field(array('slug' => 'columnas_simples')),
        // ->add_group( 'Card', $card_args ),
        Field::create( 'tab', 'Márgenes' ),
        Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20)
    )
))
->add_fields($settings_fields_container->get_fields());