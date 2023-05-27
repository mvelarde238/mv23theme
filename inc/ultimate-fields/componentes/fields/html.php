<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$htmlCmp = Repeater_Group::create( 'HTML', array(
    'edit_mode' => 'popup',
    'title_template' => '<%= content %>',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'textarea', 'content' )->hide_label()->set_rows( 20 )->set_attr(array(
            'data-type' => 'html'
        )),
    ),
))
->add_fields($settings_fields_container->get_fields());