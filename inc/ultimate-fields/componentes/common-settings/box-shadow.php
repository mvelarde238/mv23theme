<?php
use Ultimate_Fields\Field;

$box_shadow = array(
	// Field::create( 'tab', 'Sombra' ),
	Field::create( 'checkbox', 'add_box_shadow' )->set_text( 'Agregar sombras' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
	Field::create( 'repeater', 'box_shadow', '' )->set_add_text('Agregar')->add_group('Shadow', array(
        'fields' => array(
        	Field::create( 'tab', 'Básico' ),
			Field::create( 'text', 'h-offset', 'Distancia Horizontal' )->set_width(15)->set_suffix( 'px' )->set_default_value('0'),
			Field::create( 'text', 'v-offset', 'Distancia Vertical' )->set_width(15)->set_suffix( 'px' )->set_default_value('0'),
			Field::create( 'text', 'blur', 'Desenfoque' )->set_width(15)->set_suffix( 'px' )->set_default_value('15'),
			Field::create( 'color', 'color' )->set_width(30)->set_default_value('#232323'),
        	Field::create( 'tab', 'Avanzado' ),
			Field::create( 'number', 'alpha', 'Porcentaje de Intensidad' )->set_width(50)->enable_slider( 0, 100 )->set_default_value(15)->set_step( 5 ),
			Field::create( 'select', 'position', 'Posición' )->set_width(25)->add_options(array(
				'outset' => 'Exterior',
				'inset' => 'Interior',
			)),
			Field::create( 'text', 'spread', 'Spread' )->set_width(25)->set_suffix( 'px' )->set_default_value('0')->set_description('A positive value increases the size of the shadow, a negative value decreases the size of the shadow'),
        )
    ))->add_dependency('add_box_shadow'),
);