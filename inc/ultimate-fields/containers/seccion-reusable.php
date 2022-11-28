<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$sr_components_repeater = clone $components_repeater;
$sr_components_repeater->add_dependency('../section_type','componente','=');

if(ITEMS_GRID) $sr_components_repeater ->add_group( $items_grid );
$sr_components_repeater ->add_group( $columnas_internas );
// $sr_components_repeater ->add_group( $content_slider );
// $sr_components_repeater ->add_group( $columnas_simples );

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
        Field::create( 'repeater', 'v23_modulos' )->hide_label()->set_add_text('Agregar Módulo')
		->add_group($modulos)->add_dependency('../section_type','modulo','='),
        $sr_components_repeater 
	));