<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Typography {

    private static function get_google_api_key(){
        return defined('MV23_GOOGLE_API_KEY') ? MV23_GOOGLE_API_KEY : '';
    }

    private static function get_google_font_group(){
        return array(
            'title' => __('Google Font','mv23theme'),
            'layout' => 'rows',
            'edit_mode' => 'popup',
            'title_template' => '<%= (scope != "custom") ? scope : selector %> font: <%= google_font.family %> <%= google_font.variants.join(",") %>',
            'fields' => array(
                Field::create( 'font', 'google_font', __('Google Font','mv23theme') )->set_api_key( self::get_google_api_key() ),
                Field::create( 'select', 'scope', __('Scope','mv23theme') )->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options(array(
                    'any' => __('Any, just load the font','mv23theme'),
                    'global' => __('Global (body)','mv23theme'),
                    'headings' => __('Headings (h1, h2, h3, h4, h5, h6, b, strong)','mv23theme'),
                    'custom' => __('Custom CSS selector','mv23theme')
                )),
                Field::create( 'text', 'selector' )->add_dependency('scope','custom')
            )
        );
    }

    private static function get_custom_font_group(){
        return array(
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
        );
    }

    private static function get_css_properties(){
        $font_weight_options = [
            '100' => '100 - Ultra Light',
            '200' => '200 - Extra Light',
            '300' => '300 - Light',
            '400' => '400 - Normal',
            '500' => '500 - Medium',
            '600' => '600 - Semi Bold',
            '700' => '700 - Bold',
            '800' => '800 - Extra Bold',
            '900' => '900 - Black',
            'var(--bold-font-weight)' => 'var(--bold-font-weight)'
        ];

        return [
            ['type' => 'tab', 'label' => 'General' ],
            ['key' => 'base_font_size', 'label' => 'Base Font Size', 'type' => 'text', 'placeholder' => '16px'],
            ['key' => '--global-line-height', 'label' => 'Global Line Height', 'type' => 'text', 'placeholder' => '1.6' ],
            ['key' => '--text-blocks-spacing', 'label' => 'Blocks Spacing', 'type' => 'text', 'placeholder' => '24px' ],
            ['key' => '--normal-font-weight', 'label' => 'Normal Font Weight', 'type' => 'select', 'placeholder' => '400', 'options' => $font_weight_options ],
            ['key' => '--bold-font-weight', 'label' => 'Bold Font Weight', 'type' => 'select', 'placeholder' => '700', 'options' => $font_weight_options ],
            ['key' => '--headings-font-weight', 'label' => 'Headings Font Weight', 'type' => 'select', 'placeholder' => 'var(--bold-font-weight)', 'options' => $font_weight_options ],
            ['key' => '--headings-line-height', 'label' => 'Headings Line Height', 'type' => 'text', 'placeholder' => '1.3' ],
            ['key' => 'columns-gap', 'label' => 'Columns Gap', 'type' => 'complex', 'fields' => [
                ['key' => '--l-columns-gap', 'label' => 'Desktop', 'type' => 'text', 'placeholder' => '20px'],
                ['key' => '--t-columns-gap', 'label' => 'Tablet', 'type' => 'text', 'placeholder' => '20px'],
                ['key' => '--m-columns-gap', 'label' => 'Mobile', 'type' => 'text', 'placeholder' => '20px']
            ]],
            ['type' => 'tab', 'label' => 'Headings' ],
            ['key' => 'heading-h1', 'label' => 'Heading H1', 'type' => 'complex', 'fields' => [
                ['key' => '--heading-h1', 'label' => 'Font Size', 'type' => 'text', 'placeholder' => '2.33em'],
                ['key' => '--heading-h1-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
            ]],
            ['key' => 'heading-h2', 'label' => 'Heading H2', 'type' => 'complex', 'fields' => [
                ['key' => '--heading-h2', 'label' => 'Font Size', 'type' => 'text', 'placeholder' => '1.94em'],
                ['key' => '--heading-h2-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
            ]],
            ['key' => 'heading-h3', 'label' => 'Heading H3', 'type' => 'complex', 'fields' => [
                ['key' => '--heading-h3', 'label' => 'Font Size', 'type' => 'text', 'placeholder' => '1.62em'],
                ['key' => '--heading-h3-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
            ]],
            ['key' => 'heading-h4', 'label' => 'Heading H4', 'type' => 'complex', 'fields' => [
                ['key' => '--heading-h4', 'label' => 'Font Size', 'type' => 'text', 'placeholder' => '1.35em'],
                ['key' => '--heading-h4-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
            ]],
            ['key' => 'heading-h5', 'label' => 'Heading H5', 'type' => 'complex', 'fields' => [
                ['key' => '--heading-h5', 'label' => 'Font Size', 'type' => 'text', 'placeholder' => '1.13em'],
                ['key' => '--heading-h5-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
            ]],
            ['key' => 'heading-h6', 'label' => 'Heading H6', 'type' => 'complex', 'fields' => [
                ['key' => '--heading-h6', 'label' => 'Font Size', 'type' => 'text', 'placeholder' => '0.94em'],
                ['key' => '--heading-h6-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
            ]]
        ];
    }

    private static function get_css_properties_fields(){
        $fields = [];

        foreach (self::get_css_properties() as $property) {
            $key = $property['key'] ?? '';
            $label = $property['label'];
            $field_label = str_starts_with($key, '--') ? $key : $label;
            $type = $property['type'];

            if ($type === 'tab') {
                $field = Field::create('tab', $label );

            } elseif ($type === 'text') {
                $field = Field::create('text', $key, $field_label)->set_placeholder( $property['placeholder'] ?? '' );

            } elseif ($type === 'select') {
                $property_options = $property['options'];
                if($property['placeholder']) $property_options[''] = $property['placeholder'];

                $field = Field::create('text', $key, $field_label)
                    ->set_placeholder( $property['placeholder'] ?? '' )
                    ->add_suggestions( array_keys($property_options) );

            } elseif ($type === 'complex') {
                $complex_fields = array();

                foreach ($property['fields'] as $f) {
                    $complex_fields[] = Field::create($f['type'], $f['key'])
                        ->hide_label()
                        ->set_width(20)
                        ->set_prefix($f['label'])
                        ->set_placeholder( $f['placeholder'] ?? '' );
                }

                $field = Field::create('complex', $key, $field_label)
                    ->set_prefix(__($label, '_mv23theme'))
                    ->add_fields( $complex_fields )
                    ->merge();

            } else {
                // Handle other types if needed
                continue;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    public static function get_css_properties_complex(){
        $complex = Field::create( 'complex', 'typography_css_vars', __('Typography CSS Vars','mv23theme') )
            ->add_fields( self::get_css_properties_fields() )
            ->rows_layout();

        return $complex;
    }

    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', 'typography', __('Typography','mv23theme') ),

            Field::create( 'repeater', 'fonts', __('Fonts','mv23theme') )
                ->set_add_text(__('Add font','mv23theme'))
                ->set_chooser_type( 'tags' )
                ->add_group( 'google_font', self::get_google_font_group() )
                ->add_group( 'custom_font', self::get_custom_font_group() ),

            self::get_css_properties_complex()
        );

        return $fields;
    }
}