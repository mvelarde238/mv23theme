<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'page_content' )
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'section', 'page_content_section', 'Page Content' )->set_color( 'blue' ),
        Field::create( 'repeater', 'v23_modulos' )->hide_label()->set_add_text('Agregar Módulo')->set_chooser_type( 'dropdown' )
        ->add_group($modulos)->add_group($modulo_reusable),
    ));