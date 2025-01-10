<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Typography {

    public static function get_fields(){
        $font_size_rule = '^(?:(\d+(\.\d+)?(px|em|rem|vw|vh|%)|xx-small|x-small|small|medium|large|x-large|xx-large|smaller|larger))$';
        $line_height_rule = '^(?:(\d+(\.\d+)?(px|em|rem|vw|vh|%)?|normal))$';
        $font_weight_rule = '^(?:(normal|bold|bolder|lighter|[1-9]00))$';
        $google_api_key = ( defined('MV23_GOOGLE_API_KEY') ) ? MV23_GOOGLE_API_KEY : '';

        $fields = array(
            Field::create( 'tab', 'typography', __('Typography','mv23theme') ),

            Field::create( 'repeater', 'fonts', __('Fonts','mv23theme') )
                ->set_add_text(__('Add font','mv23theme'))
                ->set_chooser_type( 'tags' )
                ->add_group( 'google_font', array(
                    'title' => __('Google Font','mv23theme'),
                    'layout' => 'rows',
                    'edit_mode' => 'popup',
                    'title_template' => '<%= (scope != "custom") ? scope : selector %> font: <%= google_font.family %> <%= google_font.variants.join(",") %>',
                    'fields' => array(
                        Field::create( 'font', 'google_font', __('Google Font','mv23theme') )->set_api_key( $google_api_key ),
                        Field::create( 'select', 'scope', __('Scope','mv23theme') )->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options(array(
                            'any' => __('Any, just load the font','mv23theme'),
                            'global' => __('Global (body)','mv23theme'),
                            'headings' => __('Headings (h1, h2, h3, h4, h5, h6, b, strong)','mv23theme'),
                            'custom' => __('Custom CSS selector','mv23theme')
                        )),
                        Field::create( 'text', 'selector' )->add_dependency('scope','custom')
                    )
                ))
                ->add_group( 'custom_font', array(
                    'title' => __('Custom Font','mv23theme'),
                    'layout' => 'rows',
                    'edit_mode' => 'popup',
                    'title_template' => '<%= (scope != "custom") ? scope : selector %> font: <%= custom_font_data.name %>',
                    'fields' => array(
                        Field::create( 'complex', 'custom_font_data', __('Custom font data','mv23theme') )->merge()->add_fields(array(
                            Field::create( 'text', 'name', __('Name','mv23theme') )->required()->set_width(20),
                            Field::create( 'select', 'variant', __('Variant','mv23theme') )->add_options(array(
                                'normal' => 'Normal',
                                'bold' => 'Bold',
                                'bolder' => 'Bolder',
                                'lighter' => 'Lighter',
                                '100' => '100',
                                '200' => '200',
                                '300' => '300',
                                '400' => '400',
                                '500' => '500',
                                '600' => '600',
                                '700' => '700',
                                '800' => '800',
                                '900' => '900'
                            ))->set_width(20),
                            Field::create( 'select', 'type', __('Type','mv23theme') )->set_input_type( 'radio' )->add_options(array(
                                'file' => __('File','mv23theme'),
                                'url' => __('Url','mv23theme')
                            ))->set_width(20),
                            Field::create( 'gallery', 'files', __('@font-face files ( woff2, woff )','mv23theme') )
                                ->set_file_type('font/woff, font/woff2')
                                ->set_attr( 'class', 'hide-gallery-order' )
                                ->add_dependency( 'type', 'file' )
                                ->set_width(40),
                            Field::create( 'repeater', 'urls', __('Urls for @font-face css declaration ( woff2, woff )','mv23theme') )
                                ->set_add_text(__('Add a url','mv23theme'))
                                ->set_layout( 'table' )
                                ->add_dependency( 'type', 'url' )
                                ->add_group('item', array(
                                    'fields' => array(
                                        Field::create( 'text', 'url' )
                                    )
                                ))->set_width(100)
                        )),
                        Field::create( 'select', 'scope', __('Scope','mv23theme') )->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options(array(
                            'any' => __('Any, just load the font','mv23theme'),
                            'global' => __('Global (body)','mv23theme'),
                            'headings' => __('Headings (h1, h2, h3, h4, h5, h6)','mv23theme'),
                            'custom' => __('Custom CSS selector','mv23theme')
                        )),
                        Field::create( 'text', 'selector' )->add_dependency('scope','custom')
                    )
            )),
            
            Field::create( 'complex', 'paragraph', __('Paragraphs','mv23theme') )->add_fields(array(
                Field::create( 'text', 'font_size', __('Font size','mv23theme') )->set_validation_rule($font_size_rule)->set_placeholder('16px')->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'line_height', __('Line height','mv23theme') )->set_validation_rule($line_height_rule)->set_placeholder('180%')->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'font_weight', __('Font weight','mv23theme') )->set_validation_rule($font_weight_rule)->set_placeholder('normal')->set_attr('style','width:30%; min-width:50px;')
            ))
        );

        $heading_fields = array();
        $headings = array('h1','h2','h3','h4','h5','h6');
        $heading_default_sizes = array('28px', '26px', '24px', '22px', '20px', '18px');
        $count = 0;
        foreach ($headings as $heading) {
            $placeholder = $heading_default_sizes[$count];
            $heading_fields[] = Field::create( 'complex', $heading.'_heading' )->add_fields(array(
                Field::create( 'text', 'font_size', __('Font size','mv23theme') )->set_validation_rule($font_size_rule)->set_placeholder($placeholder)->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'line_height', __('Line height','mv23theme') )->set_validation_rule($line_height_rule)->set_placeholder('140%')->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'font_weight', __('Font weight','mv23theme') )->set_validation_rule($font_weight_rule)->set_placeholder('bold')->set_attr('style','width:30%; min-width:50px;')
            ));
            $count++;
        }

        $bold_fields = array(Field::create( 'complex', 'bold', __('Bold, Strong','mv23theme') )->add_fields(array(
            Field::create( 'text', 'font_weight', __('Font weight','mv23theme') )->set_validation_rule($font_weight_rule)->set_placeholder(700)->set_attr('style','width:30%; min-width:50px;')
        )));

        // $hints_field = array(Field::create( 'complex', 'hints' )->add_fields(array(
        //     Field::create( 'message', 'font_size_hint' )->set_description('Valid values for font size: 12px, 1.5em, 2rem, 120%, small, medium, etc','mv23theme')->hide_label(),
        //     Field::create( 'message', 'line_height_hint' )->set_description('Valid values for line_height: 1.5, 2, ..., 20px, 2em, 150%, normal, etc','mv23theme')->hide_label(), 
        //     Field::create( 'message', 'font_weight_hint' )->set_description('Valid values for font weight: normal, bold, bolder, lighter, 100, 200, ..., 900','mv23theme')->hide_label()
        // )));

        // return array_merge( $fields, $heading_fields, $hints_field );
        return array_merge( $fields, $heading_fields, $bold_fields );
    }
}