<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Theme_Options\Theme_Options;

class Page extends Component {

    public function __construct() {
		parent::__construct(
			'page',
			__( 'Page', 'mv23theme' ),
            array(
				'common_settings' => array(
					'settings'
				),
			)
		);
	}

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('General','mv23theme') ),
            Field::create( 'checkbox', 'remove_padding_top', __('Remove padding top', 'mv23theme') )->fancy(),

            Field::create( 'tab', 'Static Header', __('Static Header','mv23theme') ),
            Field::create( 'checkbox', 'hide_static_header')
                ->hide_label()->fancy()
                ->set_text(__('Hide static header','mv23theme')),
            Field::create( 'checkbox', 'hide_static_header_logo')
                ->hide_label()->fancy()
                ->set_text(__('Hide the header logo', 'mv23theme')),
            Field::create( 'checkbox', 'custom_static_header')
                ->hide_label()->fancy()
                ->set_text(__('Customize static header','mv23theme')),
            Field::create( 'complex', 'static_header_bgc', __('Background Color','mv23theme') )->add_fields(array(
				Field::create( 'checkbox', 'add_bgc', __('Customize','mv23theme') )
					->set_attr( 'style', 'min-width:initial;' )
					->fancy(),
				Field::create( 'color', 'bgc', 'Color' )
					// ->set_default_value('#ffffff')
					->set_attr( 'style', 'min-width:initial;' )
					->add_dependency('add_bgc'),
				Field::create( 'number', 'alpha', __('Opacity','mv23theme') )
					->set_placeholder('0')
					->enable_slider(0,100,1)->set_default_value(100)->set_width( 40 )
					->add_dependency('add_bgc')
            ))->add_dependency('custom_static_header'),
            Field::create( 'select', 'static_header_logo', __('Logo Version','mv23theme'))
                ->add_options( Theme_Options::getInstance()->get_logos_field_names() )
                ->add_dependency('custom_static_header')->set_width( 50 ),
            Field::create( 'image', 'custom_static_header_logo', __('Select the logo','mv23theme') )
                ->add_dependency('static_header_logo','custom','=')
                ->add_dependency('custom_static_header')->set_width( 50 ),
            Field::create( 'select', 'static_header_color_scheme', __('Color Scheme','mv23theme') )->add_options( array(
                '' => 'Default',
                'text-color-1' => __('Light Mode','mv23theme'),
                'text-color-2' => __('Dark Mode','mv23theme')
            ))->set_default_value( DEFAULT_TEXT_COLOR )->add_dependency('custom_static_header')->set_width( 50 ),
            
            Field::create( 'tab', 'Sticky Header' ),
            Field::create( 'checkbox', 'hide_sticky_header')
                ->hide_label()->fancy()
                ->set_text(__('Hide sticky header','mv23theme')),
            Field::create( 'checkbox', 'hide_sticky_header_logo')
                ->hide_label()->fancy()
                ->set_text(__('Hide the header logo', 'mv23theme')),
            Field::create( 'checkbox', 'custom_sticky_header')
                ->hide_label()->fancy()
                ->set_text(__('Customize sticky header','mv23theme')),
            Field::create( 'complex', 'sticky_header_bgc', __('Background Color','mv23theme') )->add_fields(array(
				Field::create( 'checkbox', 'add_bgc', __('Customize','mv23theme') )
					->set_attr( 'style', 'min-width:initial;' )
					->fancy(),
				Field::create( 'color', 'bgc', 'Color' )
					->set_default_value('#ffffff')
					->set_attr( 'style', 'min-width:initial;' )
					->add_dependency('add_bgc'),
				Field::create( 'number', 'alpha', __('Opacity','mv23theme') )
					->set_placeholder('0')
					->enable_slider(0,100,1)->set_default_value(100)->set_width( 40 )
					->add_dependency('add_bgc')
            ))->add_dependency('custom_sticky_header'),
            Field::create( 'select', 'sticky_header_logo', __('Logo Version','mv23theme'))
                ->add_options( Theme_Options::getInstance()->get_logos_field_names() )
                ->add_dependency('custom_sticky_header')->set_width( 50 ),
            Field::create( 'image', 'custom_sticky_header_logo', __('Select the logo','mv23theme') )
                ->add_dependency('sticky_header_logo','custom','=')
                ->add_dependency('custom_sticky_header')->set_width( 50 ),
            Field::create( 'select', 'sticky_header_color_scheme', __('Color Scheme','mv23theme') )->add_options( array(
                '' => 'Default',
                'text-color-1' => __('Light Mode','mv23theme'),
                'text-color-2' => __('Dark Mode','mv23theme')
            ))->set_default_value( DEFAULT_TEXT_COLOR )->add_dependency('custom_sticky_header')->set_width( 50 )
        );
		return $fields;
	}
}

new Page();