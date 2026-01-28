<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Offcanvas_Element extends Component {

    public function __construct() {
		parent::__construct(
			'oce-element',
			__( 'Offcanvas Element', 'mv23theme' ),
			array(
				'common_settings' => array(
					'settings'
				),
			)
		);
	}

	public static function get_icon() {
        return 'dashicons-feedback';
    }

	public static function get_builder_data() {
        return array(
			'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array(
            Field::create('image_select', 'oce_type', __('Type','mv23theme') )->set_attr( 'class', 'image-select-3-cols' )->show_label()->add_options(array(
				'modal' => array(
					'label' => __('Modal','mv23theme'),
					'image' => OFFCANVAS_ELEMENTS_PATH . '/images/modal.png'
				),
				'sidenav' => array(
					'label' => __('Sidenav','mv23theme'),
					'image' => OFFCANVAS_ELEMENTS_PATH . '/images/sidenav.png'
				),
				'bottom_sheet' => array(
					'label' => __('Bottom Sheet','mv23theme'),
					'image' => OFFCANVAS_ELEMENTS_PATH . '/images/bottom_sheet.png'
				)
				// 'tap_target' => array(
				// 	'label' => __('Tap Target','mv23theme'),
				// 	'image' => OFFCANVAS_ELEMENTS_PATH . '/images/tap_target.png'
				// )
			)),
            Field::create( 'select', 'position' )
				->hide_label()
				->set_prefix( __('Position','mv23theme') )
				->add_options(array(
					'left' => __('Left'),
					'right' => __('Right')
				))->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_dependency('oce_type','sidenav','='),
			Field::create( 'number', 'max_width' )
				->hide_label()
				->set_prefix( __('Max Width','mv23theme') )
                ->set_default_value(400)->set_suffix('px')
                ->add_dependency('oce_type','bottom_sheet','!='),
            Field::create( 'checkbox', 'dismissible' )
				->hide_label()
                ->set_text(__('Allow modal to be dismissed by keyboard or overlay click.','mv23theme'))
				->set_default_value(1)
				->fancy()->add_dependency('oce_type','sidenav','!='),
            Field::create( 'checkbox', 'close_on_click' )
				->hide_label()->fancy()
				->set_text( __('Close element on link clicks.','mv23theme') ),
			Field::create( 'checkbox', 'remove_modal_content_padding' )
				->hide_label()->fancy()
				->set_text( __('Remove space around modal components.','mv23theme') ),
            Field::create( 'number', 'max_height', __('Max Height','mv23theme') )->set_default_value(140)->set_suffix('px')
                ->add_dependency('oce_type','bottom_sheet','='),
            Field::create( 'complex', 'overlay_color', __('Overlay Color','mv23theme') )->add_fields(array(
				Field::create( 'checkbox', 'use', __('Customize','mv23theme') )
					->set_attr( 'style', 'min-width:initial;' )
					->fancy(),
				Field::create( 'color', 'color' )
					->set_default_value('#000000')
					->set_attr( 'style', 'min-width:initial;' )
					->add_dependency('use'),
				Field::create( 'number', 'alpha', __('Opacity','mv23theme') )
					->set_placeholder('0')
					->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
					->add_dependency('use')
			)),
        );

		return $fields;
	}
}

new Offcanvas_Element();