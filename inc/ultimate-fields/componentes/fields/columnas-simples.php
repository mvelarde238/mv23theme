<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$col_sim_componentes_field = Content_Layout::the_field(array('slug' => 'columnas_simples'))->add_group( 'Card', $card_args );

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    $col_sim_componentes_field
);

$columnas_simples_margenes = $margenes;
$columnas_simples_margenes[] = Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20);

$columnas_simples_args = array(
    'title' => 'Columnas Simples',
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $settings_fields, $columnas_simples_margenes, $bordes, $box_shadow, $animation)
);

$columnas_simples = Repeater_Group::create( 'Columnas Simples', $columnas_simples_args);