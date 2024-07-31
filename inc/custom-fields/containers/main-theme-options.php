<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Core;

$_main_options_fields = array();
$_count = 0;

foreach ( Core::getInstance()->get_logos_field_names() as $key => $value) {
    if( $_count < LOGOS_QUANTITY ) $_main_options_fields[] = Field::create( 'image', $key, $value )->set_width(25);
    $_count++;
}

Container::create( 'main_theme_options' ) 
    ->add_location( 'options', 'theme-options' )
    ->set_layout( 'grid' )
    ->add_fields($_main_options_fields);
