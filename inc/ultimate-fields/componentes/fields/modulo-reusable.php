<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$modulo_reusable = Repeater_Group::create( 'Modulos Reusables' )
    ->set_title( 'Módulo Reusable' )
    ->set_icon( 'dashicons dashicons-welcome-widgets-menus' )
    ->add_fields(array(
        Field::create( 'select', 'seccion_reusable', 'Seleccionar' )->add_options( $modulos_reusables ),
    )
);