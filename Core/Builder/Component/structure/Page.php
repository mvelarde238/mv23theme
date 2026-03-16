<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Theme_Options\Theme_Options;

class Page extends Component {

    public function __construct() {
		parent::__construct(
			'wrapper',
			__( 'Page', 'mv23theme' ),
            array(
				'common_settings' => array(
					'settings',
                    'scroll_animations_settings'
				),
			)
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false,
            'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', '_static_header_tab', __('Customize Static Header','mv23theme') ),
            Field::create( 'checkbox', 'place_content_under_header' )
                ->hide_label()->fancy()
                ->set_text(__('Place content under header','mv23theme')),
            Field::create( 'checkbox', 'hide_static_header')
                ->hide_label()->fancy()
                ->set_text(__('Hide static header','mv23theme')),
            Field::create( 'checkbox', 'hide_static_header_logo')
                ->hide_label()->fancy()
                ->set_text(__('Hide the header logo', 'mv23theme')),
            
            Field::create( 'tab', '_sticky_header_tab', __('Customize Sticky Header','mv23theme') ),
            Field::create( 'checkbox', 'hide_sticky_header')
                ->hide_label()->fancy()
                ->set_text(__('Hide sticky header','mv23theme')),
            Field::create( 'checkbox', 'hide_sticky_header_logo')
                ->hide_label()->fancy()
                ->set_text(__('Hide the header logo', 'mv23theme')),
            Field::create( 'checkbox', 'hide_sticky_header_on_builder')
                ->hide_label()->fancy()->set_default_value(true)
                ->set_text(__('Hide the sticky header on builder','mv23theme')),

            Field::create( 'tab', '_custom_header_tab', __('Select Custom Header','mv23theme') ),
            Field::create( 'wp_object', 'custom_header_post')->add( 'posts', 'post_type=header' )->hide_label(),

            Field::create( 'tab', '_customize_footer_tab', __('Customize Footer','mv23theme') ),
            Field::create( 'checkbox', 'hide_footer')
                ->hide_label()->fancy()
                ->set_text(__('Hide footer','mv23theme')),
        );
		return $fields;
	}
}

new Page();