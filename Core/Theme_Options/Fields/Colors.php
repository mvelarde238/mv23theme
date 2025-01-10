<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Colors {
    public static function get_fields(){
        $colors_width = ( is_customize_preview() ) ? 100 : 25;

        $fields = array(
            Field::create( 'tab', 'Colors', __('Colors','mv23theme') ),
            
            Field::create( 'complex', 'colors_wrapper', __('Main colors','mv23theme') )->add_fields(array(
                Field::create( 'color', 'primary_color', __('Primary color','mv23theme') )->set_default_value('#ff7a00')->set_width($colors_width),
                Field::create( 'color', 'secondary_color', __('Secondary color','mv23theme') )->set_default_value('#071a36')->set_width($colors_width),
                Field::create( 'color', 'font_color', __('Font color','mv23theme') )->set_width($colors_width),
                Field::create( 'color', 'headings_color', __('Headings color','mv23theme') )->set_width($colors_width)
            ))->merge(),

            Field::create( 'repeater', 'colorpicker_palette', __('Colorpicker palette','mv23theme') )
                ->set_add_text(__('Add color','mv23theme'))
                ->set_layout( 'table' )
                ->add_group( 'item', array(
                    'fields' => array(
                        Field::create( 'color', 'color' )
                    )
            )),
                
            Field::create( 'complex', 'primary_color_variations', __('Primary color variations','mv23theme') )->add_fields(array(
                Field::create( 'number', 'light_primary_color_percentage', __('Light', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value(50)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_primary_color_percentage', __('Lighter', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value(85)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_primary_color_percentage', __('Dark', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value(15)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge(),

            Field::create( 'complex', 'secondary_color_variations', __('Secondary color variations','mv23theme') )->add_fields(array(
                Field::create( 'number', 'light_secondary_color_percentage', __('Light', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value(50)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_secondary_color_percentage', __('Lighter', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value(85)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_secondary_color_percentage', __('Dark', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value(15)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge()
        );
        return $fields;
    }
}