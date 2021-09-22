<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'text', 'content', 'Ruta del archivo dentro del theme' )->set_prefix( get_stylesheet_directory_uri().'/' )->set_suffix('.php'),
);

$template_part_args = array(
    'title' => 'Template Part',
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields)
);

$template_part = Repeater_Group::create( 'Template Part', $template_part_args );