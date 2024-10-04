<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

return Container::create( '_borders-settings' )->add_fields( array(
	Field::create( 'checkbox', 'show_border' )->set_text( 'Mostrar Bordes' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
	Field::create( 'complex', 'border' )->set_width( 25 )->add_fields(array(
		Field::create( 'text', 'width', 'Grosor de línea' )->set_width(20)->set_suffix( 'px' )->set_default_value('1'),
		Field::create( 'select', 'style', 'Estilo' )->set_width(20)->add_options(array(
			'solid' => 'normal',
			'dotted' => 'puntos',
			'dashed' => 'lineas',
			'double' => 'double',
			'groove' => 'groove',
			'ridge' => 'ridge',
			'inset' => 'inset',
			'outset' => 'outset'
		)),
		Field::create( 'color', 'color', 'Color' )->set_width(60),
	))->hide_label()->add_dependency('show_border'),
	Field::create( 'radio', 'border_apply_to', 'Aplicar a:')->set_orientation( 'horizontal' )->add_options( array(
		'all' => 'Todos los bordes',
		'custom' => 'Algunos bordes',
	))->add_dependency('show_border'),
	Field::create( 'complex', 'custom_border' )->add_fields(array(
	    Field::create( 'checkbox', 'top' )->set_width( 25 )->set_text('Superior')->hide_label(),
	    Field::create( 'checkbox', 'right' )->set_width( 25 )->set_text('Derecho')->hide_label(),
	    Field::create( 'checkbox', 'bottom' )->set_width( 25 )->set_text('Inferior')->hide_label(),
	    Field::create( 'checkbox', 'left' )->set_width( 25 )->set_text('Izquierdo')->hide_label()
	))->hide_label()->add_dependency('show_border')->add_dependency('border_apply_to','custom','='),
	Field::create( 'checkbox', 'add_border_radius' )->set_text( 'Agregar esquinas redondeadas' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
	Field::create( 'text', 'border_radius', 'Tamaño' )->set_width(20)->set_suffix( 'px' )->add_dependency('add_border_radius'),
	Field::create( 'radio', 'radius_apply_to', 'Aplicar a:')->set_width(80)->set_orientation( 'horizontal' )->add_options( array(
		'all' => 'Todas las esquinas',
		'custom' => 'Algunas esquinas',
	))->add_dependency('add_border_radius'),
	Field::create( 'complex', 'custom_radius' )->add_fields(array(
	    Field::create( 'checkbox', 'top-left' )->set_width( 25 )->set_text('Superior izquierda')->hide_label(),
	    Field::create( 'checkbox', 'top-right' )->set_width( 25 )->set_text('Superior derecha')->hide_label(),
	    Field::create( 'checkbox', 'bottom-right' )->set_width( 25 )->set_text('Inferior derecha')->hide_label(),
	    Field::create( 'checkbox', 'bottom-left' )->set_width( 25 )->set_text('Inferior izquierda')->hide_label()
	))->hide_label()->add_dependency('add_border_radius')->add_dependency('radius_apply_to','custom','='),
));