<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core;

$builder_posttypes = get_option('builder_posttypes');

Container::create( 'page_content_container' )
    ->set_title(__('Builder','mv23theme'))
    ->add_location( 'post_type', $builder_posttypes )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->set_fields_callback( function() {
        return array(
            Field::create( 'ultimate_builder', 'page_content' )
                ->add_groups( Core::getInstance()->get_groups_for_builder() )
                ->hide_label()
        );
    });