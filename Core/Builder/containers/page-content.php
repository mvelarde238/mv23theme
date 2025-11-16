<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core;

Container::create( 'page_content_container' )
    ->set_title(__('Builder','mv23theme'))
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'ultimate_builder', 'page_content' )
            ->add_groups( Core::getInstance()->get_groups_for_builder() )
            ->hide_label()
    ));