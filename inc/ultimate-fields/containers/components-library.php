<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$fields = array(
    Field::create( 'textarea', 'component_data' ),
);

Container::create( 'Component Data' )->add_location( 'post_type', array('component_library') )
    // ->set_layout( 'grid' )
    ->add_fields( $fields );