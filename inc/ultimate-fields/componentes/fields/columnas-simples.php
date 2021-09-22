<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$col_sim_componentes_field = Field::create( 'layout', 'columnas_simples', '' )->set_columns( 12 );

$col_sim_componentes_field->add_group( 'Editor de Texto', $text_editor_args );
$col_sim_componentes_field->add_group( 'Separador', $separador_args );
$col_sim_componentes_field->add_group( 'Accordion', $accordion_args );
$col_sim_componentes_field->add_group( 'Carrusel', $carrusel_args );
$col_sim_componentes_field->add_group( 'Mapa', $mapa_args );
$col_sim_componentes_field->add_group( 'Progress Circle', $progress_circle_args );
$col_sim_componentes_field->add_group( 'Progress Bar', $progress_bar_args );
$col_sim_componentes_field->add_group( 'Icono y texto', $icon_and_text_args );
$col_sim_componentes_field->add_group( 'Slider', $slider_args );

$col_sim_componentes_field->add_group( 'Componente Reusable', $componente_reusable_args );
$col_sim_componentes_field->add_group( 'Card', $card_args );

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