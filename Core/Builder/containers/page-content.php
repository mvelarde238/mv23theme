<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Component\Page_Module;

Container::create( 'page_content' )
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'section', 'page_content_section', 'Page Content' )->set_color( 'blue' ),
        Field::create( 'repeater', 'page_modules' )
            ->hide_label()
            ->set_add_text('Agregar Módulo')
            ->set_chooser_type( 'dropdown' )
            ->add_group( Page_Module::the_group() )
    ));