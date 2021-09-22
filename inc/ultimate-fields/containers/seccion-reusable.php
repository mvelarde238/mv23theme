<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$sr_componentes_field = Field::create( 'repeater', 'componentes', '' )
    ->set_chooser_type( 'dropdown' )
    ->set_add_text('Agregar Componente')
    ->set_attr( 'style', 'background: #c8d6e4;' )
    ->add_dependency('../section_type','componente','=');

foreach ($componentes as $c) {
    $sr_componentes_field->add_group( $c['variable'] );
}


if(ITEMS_GRID) $sr_componentes_field->add_group( $items_grid );
$sr_componentes_field->add_group( $columnas_internas );
// $sr_componentes_field->add_group( $content_slider );
// $sr_componentes_field->add_group( $columnas_simples );

Container::create( 'settings', 'Tipo de Sección' )
	->add_location( 'post_type', 'seccion_reusable')
	->set_layout( 'grid' )
	->set_style( 'seamless' )
	->add_fields(array(
	    Field::create( 'select', 'section_type', 'Tipo de sección:')->set_orientation( 'horizontal' )->add_options( array(
	    	''=>'Seleccione el tipo de sección',
		    'modulo'=>'Módulo',
		    'componente'=>'Componente',
		)),
        Field::create( 'repeater', 'v23_modulos' )->hide_label()->set_add_text('Agregar Módulo')->add_group($modulos)->add_dependency('../section_type','modulo','='),
        $sr_componentes_field
	));