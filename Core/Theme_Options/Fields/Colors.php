<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Colors {
    public static function get_fields(){
        $is_uf_builder_editor = ( isset( $_GET['action'] ) && $_GET['action'] == 'ultimate-builder' );
        $color_fields_width = ( is_customize_preview() || $is_uf_builder_editor ) ? 100 : 50;

        $colors_default = get_option( 'theme_colors', array());

        $css_vars = array(
            '--primary-color', 
            '--secondary-color', 
            '--font-color', 
            '--headings-color', 
            '--links-color', 
            '--blog-color'
        );

        $fields = array(
            Field::create( 'tab', 'colors_tab', __('Colors','mv23theme') ),

            Field::create( 'repeater', 'theme_colors' )
                ->set_default_value( $colors_default )
                ->set_chooser_type( 'tags' )
                ->set_add_text(__('Add Color','mv23theme'))
                ->add_group( 'color', array(
                    'icon'   => 'dashicons dashicons-art',
                    'title_template' => '<% if ( color ) { %><span style="color:<%= color %>">&#9632;</span> <%= css_property %><% } %>',
                    'fields' => array(
                        Field::create( 'color', 'color' )
                            ->required()
                            ->set_width( $color_fields_width ),
                        Field::create( 'text', 'css_property', __('CSS Variable','mv23theme') )
                            ->add_suggestions( $css_vars )
                            ->set_width( $color_fields_width ),
                        Field::create( 'checkbox', 'customize_variations' )
                            ->hide_label()
                            ->add_dependency( 'css_property', '', '!=' )
                            ->set_text(__('Customize lighter variations of this color','mv23theme')),
                        Field::create( 'number', 'light', __('Light', 'mv23theme') )
                            ->set_placeholder('70')
                            ->set_suffix('%')
                            ->add_dependency( 'customize_variations', true )
                            ->set_attr('style','width:30%; min-width:50px;'),
                        Field::create( 'number', 'lighter', __('Lighter', 'mv23theme') )
                            ->set_placeholder('94')
                            ->set_suffix('%')
                            ->add_dependency( 'customize_variations', true )
                            ->set_attr('style','width:30%; min-width:50px;'),
                        Field::create( 'number', 'dark', __('Dark', 'mv23theme') )
                            ->set_placeholder('15')
                            ->set_suffix('%')
                            ->add_dependency( 'customize_variations', true )
                            ->set_attr('style','width:30%; min-width:50px;'),
                    )))
        );
        return $fields;
    }
}