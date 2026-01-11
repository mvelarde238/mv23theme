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
    ->add_fields(array(
        Field::create( 'ultimate_builder', 'page_content' )
            ->add_groups( Core::getInstance()->get_groups_for_builder() )
            ->hide_label(),
        // FAKE OCE SELECTOR FOR AJAX CALLS INSIDE POP UP
        // Field::create( 'wp_object', 'id' )->add( 'posts','post_type=offcanvas_element' )->add_dependency( 'fake_input', '_ALWAYS_HIDDEN', '=' )
    ));