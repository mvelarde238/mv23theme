<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'settings', 'Tipo de Sección' )
	->add_location( 'post_type', 'seccion_reusable')
	->set_layout( 'grid' )
	->set_style( 'seamless' )
	->add_fields(array(
		Field::create( 'repeater', 'v23_modulos' )
		->hide_label()
		->set_add_text('Agregar Módulo')
		->add_group($modulos),
	));