<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Conditional_Rendering;

Container::create( 'visibility_container' ) 
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'section', 'visibility_title_section', __('Conditional Rendering','mv23theme') )
            ->set_description( __('Control the visibility of this component based on various conditions.','mv23theme') ),
        Conditional_Rendering::get_repeater_field()
    ));