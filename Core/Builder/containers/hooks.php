<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Conditional_Rendering;

Container::create( 'hooks_container' ) 
    ->set_layout( 'rows' )
    ->set_fields_callback( function() {
        $position_field = function() {
            return Field::create( 'radio', 'hook_position', __('Position','mv23theme') )
                ->add_options(array(
                    'start' => __('Start of component','mv23theme'),
                    'end'   => __('End of component','mv23theme')
                ))
                ->set_orientation('horizontal')
                ->set_default_value('start');
        };

        $fields = array(
            Field::create( 'repeater', 'hooks', __('Add and manage hooks that will be triggered when this component is rendered.','mv23theme') )
                ->set_add_text(__('Add Hook','mv23theme'))
                ->set_chooser_type('tags')
                ->set_layout('table')
                ->add_group('hook', array(
                    'fields' => array(
                        Field::create( 'text', 'hook_name', __('Hook Name','mv23theme') ),
                        $position_field()
                    )
                ))
                ->add_group('woocommerce_hook', array(
                    'fields' => array(
                        Field::create( 'text', 'hook_name', __('Hook Name','mv23theme') )
                            ->add_suggestions(array(
                                'woocommerce_before_single_product',
                                'woocommerce_after_single_product',
                                'woocommerce_before_main_content',
                                'woocommerce_after_main_content'
                            )),
                        $position_field()
                    )
                ))
        );

        return $fields;
    });