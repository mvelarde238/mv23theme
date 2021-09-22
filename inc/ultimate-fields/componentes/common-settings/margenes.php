<?php
use Ultimate_Fields\Field;

$margenes = array(
	Field::create( 'tab', 'Márgenes' ),
	Field::create( 'complex', 'margin', 'Borrar Márgenes' )->set_width( 25 )->add_fields(array(
	    Field::create( 'checkbox', 'top' )->set_width( 25 )->set_text('Superior')->hide_label(),
	    Field::create( 'checkbox', 'bottom' )->set_width( 25 )->set_text('Inferior')->hide_label(),
	    Field::create( 'checkbox', 'right' )->set_width( 25 )->set_text('Derecho')->hide_label(),
	    Field::create( 'checkbox', 'left' )->set_width( 25 )->set_text('Izquierdo')->hide_label()
	)),
);