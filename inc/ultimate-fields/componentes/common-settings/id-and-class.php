<?php
use Ultimate_Fields\Field;

$id_and_class = array(
	Field::create( 'text', 'module_id', 'ID' )->set_width( 50 )->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
        ->set_description( 'Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )' ),
    Field::create( 'text', 'class', 'Clases' )->set_width( 50 )
        ->set_description( 'Clases de la sección, usar solo minúsculas y guiones ( -/_ )' ),
    Field::create( 'multiselect', 'theme_clases', 'Helpers' )->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options(array(
        'hide-br' => 'Ocultar saltos de línea en tablet y móviles',
        'hide-br-tablet' => 'Ocultar saltos de línea en tablet',
        'hide-br-mobile' => 'Ocultar saltos de línea en móviles',
    )),
);