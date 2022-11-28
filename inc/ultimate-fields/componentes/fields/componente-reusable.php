<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$componente_reusable = Repeater_Group::create( 'Componente Reusable', array(
    'title' => 'Componente Reusable',
    'edit_mode' => 'popup',
    'fields' => array(
        Field::create( 'select', 'seccion_reusable', 'Seleccionar' )->add_options( $modulos_reusables )
    )
));