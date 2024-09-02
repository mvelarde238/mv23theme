<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

return Container::create( '_margins-settings' )->add_fields( array(
	Field::create( 'checkbox', 'delete_margins' )->set_text( 'Quitar Márgenes' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
	Field::create( 'complex', 'margin', 'Borrar Márgenes' )->hide_label()->add_fields(array(
	    Field::create( 'checkbox', 'top' )->set_width( 25 )->set_text('Superior')->hide_label(),
	    Field::create( 'checkbox', 'bottom' )->set_width( 25 )->set_text('Inferior')->hide_label(),
	    Field::create( 'checkbox', 'right' )->set_width( 25 )->set_text('Derecho')->hide_label(),
	    Field::create( 'checkbox', 'left' )->set_width( 25 )->set_text('Izquierdo')->hide_label()
	))->add_dependency('delete_margins'),
));