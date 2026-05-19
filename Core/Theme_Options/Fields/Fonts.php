<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Fonts {
    private static function get_google_api_key(){
        // get api key for google fonts, we can check for a specific option, or a constant defined in wp-config.php
        
        $key = get_option( 'uf_google_maps_api_key', '' );

        if ( ! $key && defined('MV23_GOOGLE_API_KEY') ) {
            $key = MV23_GOOGLE_API_KEY;
        }

        return $key;
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

    public static function get_fields(){
        $fonts_default = get_option( 'fonts', array() );

        $fields = array(
            Field::create( 'tab', 'fonts_tab', __('Fonts','mv23theme') ),
            Field::create( 'repeater', 'fonts', __('Fonts','mv23theme') )
                ->set_default_value( $fonts_default )
                ->set_add_text(__('Add font','mv23theme'))
                ->set_chooser_type( 'tags' )
                ->add_group( 'google_font', self::get_google_font_group() )
                ->add_group( 'custom_font', self::get_custom_font_group() ),
        );
        return $fields;
    }
}