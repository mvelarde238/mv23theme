<?php
use Ultimate_Fields\Field;

$id_and_class = array(
	Field::create( 'text', 'module_id', 'ID' )->set_width( 50 )->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
        ->set_description( 'Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )' ),
    Field::create( 'text', 'class', 'Clases' )->set_width( 50 )
        ->add_datalist( array( 
            'disable-link-to-embed-conversion',
            'overflow-scroll',
            'overflow-hidden',
            ))
        ->set_description( 'Clases de la sección, usar solo minúsculas y guiones ( -/_ )' )
);