<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'opciones_de_despacho' )
	->add_location( 'post_type', 'mv23_location')
	->set_layout( 'grid' )
	->add_fields(array(
	    Field::create( 'tab', 'Settings' ),
	    Field::create( 'number', 'shipping_price', 'Precio de Envío' )->set_width( 33 ),
	    Field::create( 'checkbox', 'has_time_blocks', 'Bloques Horarios' )->set_text( 'Activar' )->set_width( 33 ),
	    Field::create( 'checkbox', 'has_specific_area', 'Dibujar Área' )->set_text( 'Activar' )->set_width( 33 ),
	    Field::create( 'tab', 'Bloques Horarios' )->add_dependency('has_time_blocks'),
	    Field::create( 'repeater', '_morning_time_blocks', 'En la mañana' )->set_add_text('Agregar')->set_width( 33 )
	        ->add_group('Opciones', array(
	            'fields' => array(
	                Field::create( 'text', 'start', 'Hora Inicio' )->set_width( 10 ),
	                Field::create( 'text', 'end', 'Hora Final' )->set_width( 10 ),
	                Field::create( 'number', 'precio' )->set_width( 10 ),
	                Field::create( 'multiselect', 'days', 'Activar solo estos días:' )->set_width( 65 )->set_orientation( 'horizontal' )->add_options( array(
	                    'lun' => 'Lunes',
	                    'mar' => 'Martes',
	                    'mie' => 'Miercoles',
	                    'jue' => 'Jueves',
	                    'vie' => 'Viernes',
	                    'sab' => 'Sabado',
	                    'dom' => 'Domingo'
	                )),
	            )
	        )),
	    Field::create( 'repeater', '_evening_time_blocks', 'En la tarde' )->set_add_text('Agregar')->set_width( 33 )
	        ->add_group('Opciones', array(
	            'fields' => array(
	                Field::create( 'text', 'start', 'Hora Inicio' )->set_width( 10 ),
	                Field::create( 'text', 'end', 'Hora Final' )->set_width( 10 ),
	                Field::create( 'number', 'precio' )->set_width( 10 ),
	                Field::create( 'multiselect', 'days', 'Activar solo estos días:' )->set_width( 65 )->set_orientation( 'horizontal' )->add_options( array(
	                    'lun' => 'Lunes',
	                    'mar' => 'Martes',
	                    'mie' => 'Miercoles',
	                    'jue' => 'Jueves',
	                    'vie' => 'Viernes',
	                    'sab' => 'Sabado',
	                    'dom' => 'Domingo'
	                )),
	            )
	        )),
	    Field::create( 'repeater', '_night_time_blocks', 'En la noche' )->set_add_text('Agregar')->set_width( 33 )
	        ->add_group('Opciones', array(
	            'fields' => array(
	                Field::create( 'text', 'start', 'Hora Inicio' )->set_width( 10 )->required(),
	                Field::create( 'text', 'end', 'Hora Final' )->set_width( 10 ),
	                Field::create( 'number', 'precio' )->set_width( 10 )->required(),
	                Field::create( 'multiselect', 'days', 'Activar solo estos días:' )->set_width( 65 )->set_orientation( 'horizontal' )->add_options( array(
	                    'lun' => 'Lunes',
	                    'mar' => 'Martes',
	                    'mie' => 'Miercoles',
	                    'jue' => 'Jueves',
	                    'vie' => 'Viernes',
	                    'sab' => 'Sabado',
	                    'dom' => 'Domingo'
	                )),
	            )
	        )),
	    Field::create( 'tab', 'Área de Despacho' )->add_dependency('has_specific_area'),
	    Field::create( 'map_multiple', 'the_map' )->set_output_width( '100%' )->set_output_height( 300 ),
	));
	