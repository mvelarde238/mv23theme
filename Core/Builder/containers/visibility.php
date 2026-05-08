<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Conditional_Rendering;

Container::create( 'visibility_container' ) 
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'section', 'visibility_title_section', __('Conditional Rendering','mv23theme') )
            ->set_description( __('Control the visibility of this component based on various conditions.','mv23theme') ),
        Field::create( 'select', 'rule_operator', __( 'Match', 'mv23theme' ) )
            ->set_input_type( 'radio' )
            ->add_options( array(
                'all' => __( 'ALL rules (AND). The element is visible only when every condition is met.', 'mv23theme' ),
                'any' => __( 'ANY rule (OR). The element is visible when at least one condition is met.', 'mv23theme' ),
            ) )
            ->set_default_value( 'all' ),
        Conditional_Rendering::get_repeater_field()
    ));