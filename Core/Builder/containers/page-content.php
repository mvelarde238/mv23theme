<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Component\Page_Module;

Container::create( 'page_content' )
    ->set_title(__('Page Content','mv23theme'))
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'section', 'page_content_section', __('Page Content','mv23theme') )->set_color( 'blue' ),
        Field::create( 'repeater', 'page_modules' )
            ->hide_label()
            ->set_add_text(__('Add Module','mv23theme'))
            ->set_chooser_type( 'dropdown' )
            ->add_group( Page_Module::the_group() )
    ));