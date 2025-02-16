<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$alignment = array(
    'flex-start'   => __('Start','mv23theme'),
    'center' => __('Center','mv23theme'),
    'space-between' => __('Space Between','mv23theme'),
    'space-around' => __('Space Around','mv23theme'),
    'flex-end' => __('End','mv23theme')
);

Container::create( 'blocks_layout_settings_container' ) 
    // ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create('select','layout')->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options(array(
            'grid'   => 'Grid',
            'flex' => 'Flex'
        )),
        Field::create('message','hint_1')->set_description(__('The width of the components will be as configured','mv23theme'))->add_dependency('layout','grid')->hide_label(),
        Field::create('message','hint_2')->set_description(__('The width of the components will be determined by their content.','mv23theme'))->add_dependency('layout','flex')->hide_label(),
        Field::create('select','justify_content')->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options($alignment),
        Field::create('select','align_items')->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options($alignment)
    ));