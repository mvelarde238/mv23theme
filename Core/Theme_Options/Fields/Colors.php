<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Colors {
    public static function get_fields(){
        $colors_width = ( is_customize_preview() ) ? 100 : 25;

        $fields = array(
            Field::create( 'tab', 'Colors' ),
            
            Field::create( 'complex', 'colors_wrapper', __('Main colors','default') )->add_fields(array(
                Field::create( 'color', 'primary_color' )->set_default_value('#ff7a00')->set_width($colors_width),
                Field::create( 'color', 'secondary_color' )->set_default_value('#071a36')->set_width($colors_width),
                Field::create( 'color', 'font_color' )->set_width($colors_width),
                Field::create( 'color', 'headings_color' )->set_width($colors_width)
            ))->merge(),

            Field::create( 'repeater', 'colorpicker_palette' )
                ->set_add_text(__('Add color','default'))
                ->set_layout( 'table' )
                ->add_group( 'item', array(
                    'fields' => array(
                        Field::create( 'color', 'color' )
                    )
            )),
                
            Field::create( 'complex', 'primary_color_variations' )->add_fields(array(
                Field::create( 'number', 'light_primary_color_percentage', __('Light', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(50)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_primary_color_percentage', __('Lighter', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(85)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_primary_color_percentage', __('Dark', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(15)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge(),

            Field::create( 'complex', 'secondary_color_variations' )->add_fields(array(
                Field::create( 'number', 'light_secondary_color_percentage', __('Light', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(50)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_secondary_color_percentage', __('Lighter', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(85)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_secondary_color_percentage', __('Dark', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(15)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge()
        );
        return $fields;
    }
}