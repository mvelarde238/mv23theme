<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'post_format' )
    ->add_location( 'post_type', 'post', array(
        'context' => 'side'
    ))
    ->add_fields(array(
        Field::create( 'select', 'post_format' )
	        ->set_input_type( 'radio' )
            ->hide_label()
	        ->add_options(array(
	        	''   => __('Standard','default'),
	        	'link'   => __('Link','default')
            )),
        Field::create( 'text', 'post_link' )->add_dependency('post_format','link','=')
    ));