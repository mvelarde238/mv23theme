<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$components_wrapper = Repeater_Group::create( 'Components Wrapper', array(
    'title' => 'Wrapper',
    'edit_mode' => 'popup',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Content_Layout::the_field(array(
			'slug' => 'content_layout', 
		))
    )
))
->add_fields($settings_fields_container->get_fields());