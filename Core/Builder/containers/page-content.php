<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Component\Page_Module;

use Core\Builder\Core;
$ultimate_builder = Field::create( 'ultimate_builder', 'page_content_section_2' )->hide_label();
$components = Core::getInstance()->get_components();
if(is_array($components) && count($components) > 0){
    foreach ($components as $component) {
		$exclude = array();
		if( in_array($component->get_id(), $exclude) ) continue;
        $options = array(
            'min_width' => 1,
            'title' => $component->get_title(),
            'icon' => $component->get_icon(),
            'title_template' => $component->get_title_template(),
            'fields' => $component->get_fields(),
            'edit_mode' => $component->get_edit_mode(),
            'layout' => $component->get_layout()
        );
		$ultimate_builder->add_group( $component->get_id(), $options);
    }
}

Container::create( 'test_builder' )
    ->set_title(__('Builder','mv23theme'))
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        $ultimate_builder
    ));

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