<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Global_Styles {

    private static function get_css_properties($type){
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

        $link_decoration_options = [
            'none' => 'none',
            'underline' => 'underline',
            'overline' => 'overline',
            'line-through' => 'line-through',
            'underline overline' => 'underline overline'
        ];

        $all_properties = [
            'typography' => [
                // ['type' => 'tab', 'label' => 'General' ],
                ['key' => 'base_font_size', 'label' => 'Base Font Size', 'type' => 'text', 'placeholder' => '16px'],
                ['key' => '--global-line-height', 'label' => 'Global Line Height', 'type' => 'text', 'placeholder' => '1.6' ],
                ['key' => '--normal-font-weight', 'label' => 'Normal Font Weight', 'type' => 'select', 'placeholder' => '400', 'options' => $font_weight_options ],
                ['key' => '--bold-font-weight', 'label' => 'Bold Font Weight', 'type' => 'select', 'placeholder' => '700', 'options' => $font_weight_options ],
                ['key' => '--components-spacing', 'label' => 'Blocks Spacing', 'type' => 'text', 'placeholder' => '24px' ],
            ],
            'headings' => [
                ['key' => '--headings-font-weight', 'label' => 'Headings Font Weight', 'type' => 'select', 'placeholder' => 'var(--bold-font-weight)', 'options' => $font_weight_options ],
                ['key' => '--headings-line-height', 'label' => 'Headings Line Height', 'type' => 'text', 'placeholder' => '1.3' ],
                // ['type' => 'section', 'label' => 'Headings' ],
                ['key' => 'heading-h1', 'label' => 'Heading H1', 'type' => 'complex', 'fields' => [
                    ['key' => '--heading-h1', 'label' => 'H1 Font Size', 'type' => 'text', 'placeholder' => '2.33em'],
                    ['key' => '--heading-h1-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)'],
                ]],
                ['key' => 'heading-h2', 'label' => 'Heading H2', 'type' => 'complex', 'fields' => [
                    ['key' => '--heading-h2', 'label' => 'H2 Font Size', 'type' => 'text', 'placeholder' => '1.94em'],
                    ['key' => '--heading-h2-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
                ]],
                ['key' => 'heading-h3', 'label' => 'Heading H3', 'type' => 'complex', 'fields' => [
                    ['key' => '--heading-h3', 'label' => 'H3 Font Size', 'type' => 'text', 'placeholder' => '1.62em'],
                    ['key' => '--heading-h3-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
                ]],
                ['key' => 'heading-h4', 'label' => 'Heading H4', 'type' => 'complex', 'fields' => [
                    ['key' => '--heading-h4', 'label' => 'H4 Font Size', 'type' => 'text', 'placeholder' => '1.35em'],
                    ['key' => '--heading-h4-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
                ]],
                ['key' => 'heading-h5', 'label' => 'Heading H5', 'type' => 'complex', 'fields' => [
                    ['key' => '--heading-h5', 'label' => 'H5 Font Size', 'type' => 'text', 'placeholder' => '1.13em'],
                    ['key' => '--heading-h5-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
                ]],
                ['key' => 'heading-h6', 'label' => 'Heading H6', 'type' => 'complex', 'fields' => [
                    ['key' => '--heading-h6', 'label' => 'H6 Font Size', 'type' => 'text', 'placeholder' => '0.94em'],
                    ['key' => '--heading-h6-line-height', 'label' => 'Line Height', 'type' => 'text', 'placeholder' => 'var(--headings-line-height)']
                ]],
            ],
            'links' => [
                // ['type' => 'section', 'label' => 'Links' ],
                ['key' => '--links-decoration', 'label' => 'Link Decoration', 'type' => 'select', 'placeholder' => 'none', 'options' => $link_decoration_options ],
                ['key' => '--links-hover-decoration', 'label' => 'Link Hover Decoration', 'type' => 'select', 'placeholder' => 'underline', 'options' => $link_decoration_options ],
                ['key' => '--links-decoration-thickness', 'label' => 'Link Decoration Thickness', 'type' => 'text', 'placeholder' => '1px' ],
                ['key' => '--links-decoration-offset', 'label' => 'Link Decoration Offset', 'type' => 'text', 'placeholder' => '6px' ]
            ],
            'custom_css' => [
                ['key' => 'custom_global_css', 'label' => 'Custom CSS', 'type' => 'textarea', 'placeholder' => 'e.g. body { background-color: white; }', 'codemirror' => 'text/css' ]
            ]
        ];

        return $all_properties[$type] ?? array();
    }

    private static function get_css_properties_fields( $type, $default_values ){
        $fields = [];

        foreach (self::get_css_properties($type) as $property) {
            $key = $property['key'] ?? '';
            $label = $property['label'];
            $field_label = str_starts_with($key, '--') ? $key : $label;
            $type = $property['type'];

            if ($type === 'tab') {
                $field = Field::create('tab', $label );

            } elseif ($type === 'section') {
                $field = Field::create('section', $label );

            } elseif ($type === 'text') {
                $field = Field::create('text', $key, $field_label)
                    ->set_default_value( $default_values[$key] ?? '' )
                    ->set_placeholder( $property['placeholder'] ?? '' );

            } elseif ($type === 'textarea') {
                $field = Field::create('textarea', $key, $field_label)
                    ->set_default_value( $default_values[$key] ?? '' )
                    ->set_placeholder( $property['placeholder'] ?? '' );
                if ( !empty( $property['codemirror'] ) ) {
                    $field->set_codemirror( $property['codemirror'] );
                }

            } elseif ($type === 'select') {
                $property_options = $property['options'];
                if($property['placeholder']) $property_options[''] = $property['placeholder'];

                $field = Field::create('text', $key, $field_label)
                    ->set_default_value( $default_values[$key] ?? '' )
                    ->set_placeholder( $property['placeholder'] ?? '' )
                    ->add_suggestions( array_keys($property_options) );

            } elseif ($type === 'complex') {
                $complex_fields = array();

                foreach ($property['fields'] as $f) {
                    $complex_fields[] = Field::create($f['type'], $f['key'], $f['label'] )
                        ->set_default_value( $default_values[$f['key']] ?? '' )
                        ->set_width(20)
                        ->set_placeholder( $f['placeholder'] ?? '' );
                }

                $field = Field::create('complex', $key, $field_label)
                    ->set_prefix(__($label, '_mv23theme'))
                    ->add_fields( $complex_fields )
                    ->merge();

                if( isset($_GET['action']) && $_GET['action'] === 'ultimate-builder') {
                    $field->hide_label();
                }

            } else {
                // Handle other types if needed
                continue;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    public static function generate_complex_field($type, $is_breakpoint = false, $breakpoint = 'desktop'){
        $field_name = $type . '_settings';
        if($is_breakpoint) {
            $field_name = '_breakpoint_' . $breakpoint . '_' . $field_name;
        }
        $defaults = get_option( $field_name, array() );

        // translators: %s is replaced with the type of settings, e.g. "Typography", "Headings" or "Links"
        $field_label = sprintf( __('%s Settings','mv23theme'), __(ucfirst($type), 'mv23theme') );

        $complex_field = Field::create( 'complex', $field_name, $field_label )
            ->set_default_value( $defaults )
            ->add_fields( self::get_css_properties_fields( $type, $defaults ) )
            ->add_dependency('device_switch', $breakpoint)
            ->rows_layout();

        if( isset($_GET['action']) && $_GET['action'] === 'ultimate-builder') {
            $complex_field->hide_label();
        }

        return $complex_field;
    }

    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', 'typography_tab', __('Typography','mv23theme') ),
            self::generate_complex_field('typography'),
            self::generate_complex_field('typography', true, 'tablet'),
            self::generate_complex_field('typography', true, 'mobileLandscape'),
            self::generate_complex_field('typography', true, 'mobilePortrait'),

            Field::create( 'tab', 'headings_tab', __('Headings','mv23theme') ),
            self::generate_complex_field('headings'),
            self::generate_complex_field('headings', true, 'tablet'),
            self::generate_complex_field('headings', true, 'mobileLandscape'),
            self::generate_complex_field('headings', true, 'mobilePortrait'),

            Field::create( 'tab', 'links_tab', __('Links','mv23theme') ),
            self::generate_complex_field('links'),
            self::generate_complex_field('links', true, 'tablet'),
            self::generate_complex_field('links', true, 'mobileLandscape'),
            self::generate_complex_field('links', true, 'mobilePortrait'),

            Field::create( 'tab', 'custom_css_tab', __('Custom CSS','mv23theme') ),
            self::generate_complex_field('custom_css'),
            self::generate_complex_field('custom_css', true, 'tablet'),
            self::generate_complex_field('custom_css', true, 'mobileLandscape'),
            self::generate_complex_field('custom_css', true, 'mobilePortrait')
        );

        return $fields;
    }
}