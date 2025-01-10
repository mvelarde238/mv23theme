<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;
use Core\Theme_Options\Theme_Options;

class Header {

    public static function get_fields($key){
        $fields = array(
            Field::create('tab', $key.'_header'),
            Field::create('complex', $key.'_header_logo_wrapper', __('Logo','mv23theme'))->merge()->add_fields(array(
                Field::create('select', $key.'_header_logo', __('Select','mv23theme'))->add_options( Theme_Options::getInstance()->get_logos_field_names() )->set_width(50),
                Field::create('image', 'custom_'.$key.'_header_logo', __('Select image','mv23theme'))->add_dependency($key.'_header_logo', 'custom', '=')->set_width(50),
            )),
            Field::create('number', $key.'_header_logo_height', __('Logo Max Height','mv23theme'))->set_placeholder(60)->set_suffix('px'),
            Field::create('complex', $key.'_header_bgc', __('Background Color','mv23theme'))->add_fields(array(
                Field::create('checkbox', 'add_bgc', __('Use','mv23theme'))->fancy()->set_width(25),
                Field::create('color', 'bgc', 'Color')->add_dependency('add_bgc')->set_width(50),
                Field::create( 'number', 'alpha', __('Opacity','mv23theme') )
                    ->add_dependency('add_bgc')
                    ->set_placeholder('0')
                    ->enable_slider(0,100,1)
                    ->set_default_value(100)
                    ->set_width( 25 )
            )),
            Field::create('select', $key.'_header_color_scheme', __('Text Color','mv23theme'))->add_options(array(
                '' => __('Select','mv23theme'),
                'text-color-1' => 'Default Scheme',
                'text-color-2' => 'Dark Scheme'
            ))->set_default_value(DEFAULT_TEXT_COLOR),
        );

        return $fields;
    }
}