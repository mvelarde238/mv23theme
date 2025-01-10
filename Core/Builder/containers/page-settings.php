<?php
use Core\Theme_Options\Theme_Options;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'page_settings' )
    ->set_title(__('Page Settings','mv23theme'))
    ->add_location( 'post_type', PAGE_SETTINGS_POSTTYPES )
    ->add_fields(array(
        Field::create( 'tab', __('Page','mv23theme') ),
        Field::create( 'complex', 'page_bgc', __('Background Color','mv23theme') )->set_width( 20 )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', __('Activate','mv23theme') )->set_width( 25 )->fancy()->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc')->hide_label(),
        )),
        Field::create( 'select', 'page_color_scheme', __('Text Color','mv23theme') )->set_width( 20 )->add_options( array(
            '' => __('Select','mv23theme'),
            'default-scheme' => __('Dark','mv23theme'),
            'dark-scheme' => __('Light','mv23theme'),
        ))->set_default_value(DEFAULT_COLOR_SCHEME),
        Field::create( 'checkbox', 'remove_body_padding_top', __('Remove body padding top', 'mv23theme') )->fancy(),

        Field::create( 'tab', 'Static Header', __('Static Header','mv23theme') ),
        Field::create( 'checkbox', 'hide_static_header', __('Hide','mv23theme'))->set_text(__('Hide static header','mv23theme')),
        Field::create( 'checkbox', 'hide_static_header_logo', __('Hide logo','mv23theme'))->set_text(__('Hide the header logo', 'mv23theme')),
        Field::create( 'checkbox', 'custom_static_header', __('Customize','mv23theme'))->set_text(__('Customize static header','mv23theme')),
        Field::create( 'select', 'static_header_logo', __('Logo Version','mv23theme'))
            ->add_options( Theme_Options::getInstance()->get_logos_field_names() )
            ->add_dependency('custom_static_header'),
        Field::create( 'image', 'custom_static_header_logo', __('Select the logo','mv23theme') )->add_dependency('static_header_logo','custom','=')->add_dependency('custom_static_header'),
        Field::create( 'complex', 'static_header_bgc', __('Background Color','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', __('Activate','mv23theme') )->set_width( 25 )->set_text(__('Activate','mv23theme'))->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc'),
            Field::create( 'text', 'alpha', __('Alpha','mv23theme') )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description(__('Use a number from 1 to 100','mv23theme')),
        ))->add_dependency('custom_static_header'),
        Field::create( 'select', 'static_header_color_scheme', __('Font Color','mv23theme') )->add_options( array(
            '' => 'Default',
            'text-color-1' => __('Dark','mv23theme'),
            'text-color-2' => __('Light','mv23theme'),
        ))->set_default_value( DEFAULT_TEXT_COLOR )->add_dependency('custom_static_header'),
    
        Field::create( 'tab', 'Sticky Header' ),
        Field::create( 'checkbox', 'hide_sticky_header', 'Ocultar')->set_text('Ocultar el sticky header'),
        Field::create( 'checkbox', 'hide_sticky_header_logo', 'Ocultar el logo')->set_text('Ocultar el logo'),
        Field::create( 'checkbox', 'custom_sticky_header', 'Personalizar')->set_text('Personalizar el sticky header'),
        Field::create( 'select', 'sticky_header_logo', __('Logo Version','mv23theme'))
            ->add_options( Theme_Options::getInstance()->get_logos_field_names() )
            ->add_dependency('custom_sticky_header'),
        Field::create( 'image', 'custom_sticky_header_logo', __('Select the logo','mv23theme') )->add_dependency('sticky_header_logo','custom','=')->add_dependency('custom_sticky_header'),
        Field::create( 'complex', 'sticky_header_bgc', 'Color de fondo' )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc', __('Activate','mv23theme') )->set_width( 25 )->set_text(__('Activate','mv23theme'))->set_default_value(1),
            Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc')->set_default_value('#ffffff'),
            Field::create( 'text', 'alpha', __('Alpha','mv23theme') )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description(__('Use a number from 1 to 100','mv23theme')),
        ))->add_dependency('custom_sticky_header'),
        Field::create( 'select', 'sticky_header_color_scheme', __('Font Color','mv23theme') )->add_options( array(
            '' => 'Default',
            'text-color-1' => __('Dark','mv23theme'),
            'text-color-2' => __('Light','mv23theme'),
        ))->set_default_value( DEFAULT_TEXT_COLOR )->add_dependency('custom_sticky_header'),
    ));