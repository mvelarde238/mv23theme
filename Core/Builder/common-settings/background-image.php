<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

return Container::create( '_background-image-settings' )->add_fields( array(
	Field::create( 'image', 'bgi', 'Imágen de Fondo' )->set_width( 20 ),
    Field::create( 'complex', 'bgi_options', '' )->set_width( 20 )->add_fields(array(
		Field::create( 'select', 'size', 'Tamaño')->add_options( array(
		    'cover' => 'Cubrir Todo',
		    'auto' => 'Automático',
		)),
		Field::create( 'select', 'repeat', 'Repetir')->add_options( array(
		    'no-repeat' => 'No Repetir',
		    'repeat' => 'Ambas direcciones',
		    'repeat-x' => 'Solo horizontal',
		    'repeat-y' => 'Solo en vertical',
		)),
		Field::create( 'select', 'position_x', 'Posición Eje Horizontal')->add_options( array(
		    'center' => 'Centro',
		    'left' => 'Izquierda',
		    'right' => 'Derecha',
		)),
		Field::create( 'select', 'position_y', 'Posición Eje Vertical')->add_options( array(
		    'center' => 'Centro',
		    'top' => 'Arriba',
		    'bottom' => 'Abajo',
		)),
	))->add_dependency('bgi','0','>'),
));