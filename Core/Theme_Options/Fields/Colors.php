<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Colors {
    public static function get_fields(){
        $is_uf_builder_editor = ( isset( $_GET['action'] ) && $_GET['action'] == 'ultimate-builder' );
        $colors_width = ( is_customize_preview() || $is_uf_builder_editor ) ? 100 : 20;

        $primary_color_default = get_option( 'primary_color', '#ff7a00' );
        $secondary_color_default = get_option( 'secondary_color', '#071a36' );
        $font_color_default = get_option( 'font_color', '' );
        $headings_color_default = get_option( 'headings_color', '' );
        $link_color_default = get_option( 'link_color', '' );
        $colorpicker_palette_default = get_option( 'colorpicker_palette', array() );
        $light_primary_color_percentage_default = get_option( 'light_primary_color_percentage', 50 );
        $lighter_primary_color_percentage_default = get_option( 'lighter_primary_color_percentage', 85 );
        $dark_primary_color_percentage_default = get_option( 'dark_primary_color_percentage', 15 );
        $light_secondary_color_percentage_default = get_option( 'light_secondary_color_percentage', 50 );
        $lighter_secondary_color_percentage_default = get_option( 'lighter_secondary_color_percentage', 85 );
        $dark_secondary_color_percentage_default = get_option( 'dark_secondary_color_percentage', 15 );

        $fields = array(
            Field::create( 'tab', 'Colors', __('Colors','mv23theme') ),
            
            Field::create( 'complex', 'colors_wrapper', __('Main colors','mv23theme') )->add_fields(array(
                Field::create( 'color', 'primary_color', __('Primary color','mv23theme') )->set_default_value($primary_color_default)->set_width($colors_width),
                Field::create( 'color', 'secondary_color', __('Secondary color','mv23theme') )->set_default_value($secondary_color_default)->set_width($colors_width),
                Field::create( 'color', 'font_color', __('Font color','mv23theme') )->set_default_value($font_color_default)->set_width($colors_width),
                Field::create( 'color', 'headings_color', __('Headings color','mv23theme') )->set_default_value($headings_color_default)->set_width($colors_width),
                Field::create( 'color', 'link_color', __('Link color','mv23theme') )->set_default_value($link_color_default)->set_width($colors_width)
            ))->merge(),

            Field::create( 'repeater', 'colorpicker_palette', __('Colorpicker palette','mv23theme') )
                ->set_default_value( $colorpicker_palette_default )
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
                    ->set_default_value($light_primary_color_percentage_default)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_primary_color_percentage', __('Lighter', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value($lighter_primary_color_percentage_default)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_primary_color_percentage', __('Dark', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value($dark_primary_color_percentage_default)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge(),

            Field::create( 'complex', 'secondary_color_variations', __('Secondary color variations','mv23theme') )->add_fields(array(
                Field::create( 'number', 'light_secondary_color_percentage', __('Light', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value($light_secondary_color_percentage_default)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_secondary_color_percentage', __('Lighter', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value($lighter_secondary_color_percentage_default)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_secondary_color_percentage', __('Dark', 'mv23theme') )
                    ->set_placeholder('0')
                    ->set_default_value($dark_secondary_color_percentage_default)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge()
        );
        return $fields;
    }
}