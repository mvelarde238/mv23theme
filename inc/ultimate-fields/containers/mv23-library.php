<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$fields = array(
    Field::create( 'textarea', 'library_item_data' ),
);

Container::create( 'Library Item Data' )->add_location( 'post_type', array('mv23_library') )->add_fields( $fields );