<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'common_settings_container' ) 
    ->set_layout( 'rows' )
    ->set_fields_callback(function(){
        $fields = array(
            Field::create( 'text', 'id', __('ID','mv23theme') )
                ->set_validation_rule('^[a-z][a-z0-9_-]+$')
                ->set_description( __('Section identifier ID, use only lowercase letters and hyphens (-)', 'mv23theme') )
                ->set_width( 50 ),
            Field::create( 'text', 'classes', __('Classes','mv23theme') )
                ->set_description( __('Section class attribute, use only lowercase letters and hyphens (-)', 'mv23theme' ) )
                ->set_width( 50 ),
            Field::create( 'multiselect', 'utility_classes', __('Utility classes','mv23theme') )
                ->set_input_type( 'checkbox' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    // 'full-width' => __('Full Width','mv23theme'),
                    'full-height' => __('Full Height','mv23theme'),
                    'sticky' => __('Sticky','mv23theme'),
                    'overflow-scroll' => __('Overflow scroll','mv23theme'),
                    'overflow-hidden' => __('Overflow hidden','mv23theme'),
                    'hide-br' => __('Hide line breaks on tablets and mobile devices','mv23theme'),
                    'hide-br-tablet' => __('Hide line breaks on tablets only','mv23theme'),
                    'hide-br-mobile' => __('Hide line breaks on mobile devices only','mv23theme'),
                    'extend-bg-to-left' => __('Extend background to left','mv23theme'),
                    'extend-bg-to-right' => __('Extend background to right','mv23theme'),
            )),
            Field::create( 'complex', 'video_background', __('Video background', 'mv23theme') )->add_fields(array(
                Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
    
                Field::create( 'radio', 'video_source', __('Source','mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                    'selfhosted' => __('Media','mv23theme'),
                    'external' => __('External','mv23theme')
                    ))->add_dependency('use')->set_width(30),
    
                Field::create( 'video', 'video' )->add_dependency('use')->add_dependency('video_source','selfhosted','=')->set_width(30),
                Field::create( 'embed', 'external_url', 'URL')->add_dependency('use')->add_dependency('video_source','external','=')->set_width(30),
    
                Field::create( 'complex', 'video_settings', __('Video Settings','mv23theme') )->add_fields(array(
                    Field::create( 'color', 'bgc', __('Background color','mv23theme') )->set_width(10),
                    Field::create( 'checkbox', 'controls', __('Controls','mv23theme') )->set_text( __('Activate','mv23theme') )->set_width(10),
                    Field::create( 'checkbox', 'autoplay', __('AutoPlay','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                    Field::create( 'checkbox', 'muted', __('Muted','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                    Field::create( 'checkbox', 'loop', __('Loop','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                    Field::create( 'number', 'opacity', __('Opacity','mv23theme') )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width(10)
                ))->add_dependency('use')
            )),
            Field::create( 'complex', 'slider_background', __('Slider background', 'mv23theme') )->add_fields(array(
                Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
                Field::create( 'text', 'shortcode')->add_dependency('use')->set_width( 50 )
            )),
            Field::create( 'complex', 'color_scheme' )->set_attr( 'style', 'flex-wrap: nowrap;' )->add_fields(array(
                Field::create( 'checkbox', 'use', __('Activate','mv23theme') )
                    ->fancy()
                    ->set_attr( 'style', 'flex-grow: initial;min-width: auto;' ),
                Field::create( 'select', 'key', __('Color Scheme','mv23theme'))->add_options( array(
                    'light-mode' => __('Light Mode','mv23theme'),
                    'dark-mode' => __('Dark Mode','mv23theme'),
                ))->add_dependency('use')->set_attr( 'style', 'flex-grow: 1;' )
            )),
            Field::create( 'complex', 'hide_on', __('Hide on:','mv23theme') )->add_fields(array(
                Field::create( 'checkbox', 'mobile', __('Mobile','mv23theme') )->fancy()->set_attr( 'style', 'flex-grow: initial;min-width: auto;' ),
                Field::create( 'checkbox', 'tablet', __('Tablet','mv23theme') )->fancy()->set_attr( 'style', 'flex-grow: initial;min-width: auto;' ),
                Field::create( 'checkbox', 'desktop', __('Desktop','mv23theme') )->fancy()->set_attr( 'style', 'flex-grow: initial;min-width: auto;' )
            )),
        );

        $position_field = function() {
            return Field::create( 'radio', 'hook_position', __('Position','mv23theme') )
                ->add_options(array(
                    'start' => __('Start of component','mv23theme'),
                    'end'   => __('End of component','mv23theme')
                ))
                ->set_orientation('horizontal')
                ->set_default_value('start');
        };

        $fields[] = Field::create( 'repeater', 'hooks', __('Add and manage hooks that will be triggered when this component is rendered.','mv23theme') )
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
                            '--General Hooks--',
                            'woocommerce_before_main_content',
                            'woocommerce_after_main_content',

                            '--Single Product Hooks--',
                            'woocommerce_before_single_product',
                            'woocommerce_before_single_product_summary',
                            'woocommerce_single_product_summary',
                            'woocommerce_after_single_product_summary',
                            'woocommerce_after_single_product',

                            '--Archive Hooks--',
                            'woocommerce_shop_loop_header',
                            'woocommerce_before_shop_loop',
                            'woocommerce_after_shop_loop',
                            'woocommerce_sidebar'
                        )),
                    $position_field()
                )
            ));

        return $fields;
    });
    // ->add_fields();  