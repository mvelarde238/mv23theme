<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class OCE_Dynamic_Content extends Component {

    public function __construct() {
		parent::__construct(
			'oce_dynamic_content',
			__( 'OCE Dynamic Content', 'mv23theme' ),
            array( 'add_common_settings' => false )
		);
	}

	public static function get_icon() {
        return 'dashicons-update';
    }

	public static function get_builder_data() {
        return array(
			'connected_gjs_type' => 'oce-dynamic-content',
			'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array(
			Field::create( 'tab', 'general_tab', __('General') ),
            Field::create( 'complex', 'async_settings', __('Asynchronous Content Settings') )->set_layout('rows')->hide_label()->add_fields(array(
				Field::create( 'message', '_hint-1','' )->set_description( __('The content will be generated using an asynchronous function','mv23theme') ),
				Field::create( 'complex', '_fields_group_1', '' )->merge()->add_fields(array(
					Field::create( 'radio', 'content_source', __('Content Source','mv23theme') )->add_options(array(
						'page' => __( 'Page: generate the content from an internal page', 'mv23theme' ),
						'url' => __( 'Url: generate the content from an url', 'mv23theme' ),
						'link' => __( 'Link: generate the content dinamically from the link clicked', 'mv23theme' )
					))->set_width(50),
					Field::create( 'wp_object', 'page_source', __( 'Page item', 'mv23theme' ) )->add( 'posts' )->add_dependency('content_source','page','=')->required()->set_width(50),
					Field::create( 'text', 'url_source', __( 'Enter the url', 'mv23theme' ) )->add_dependency('content_source','url','=')->required(),
					Field::create( 'message', '_url_source_hint' )->set_description(__('Use trigger events tab to add a CSS selector for the link source','mv23theme'))->add_dependency('content_source','link','=')->hide_label(),
					Field::create( 'checkbox', 'clear_on_close', __('Clear on close','mv23theme') )->set_description(__('Clear the content on close','mv23theme'))->set_default_value(1)->fancy()->set_width(20),
					Field::create( 'checkbox', 'load_on_iframe', __('Load on iframe','mv23theme') )->set_description(__('Load the content in an iframe','mv23theme'))->set_default_value(1)->fancy()->set_width(20),
					Field::create( 'checkbox', 'cherry_pick_sections' )->set_description(__('Let you choose only the sections you want','mv23theme'))->fancy()->add_dependency('load_on_iframe','1','!=')->set_width(20),
					Field::create( 'text', 'cherry_picked_sections', __( 'CSS Selector for cherry picked sections', 'mv23theme' ) )->set_description( __( 'Please enter the CSS selector that matches the section(s) you want to display in the offcanvas element.', 'mv23theme' ) )->add_dependency('cherry_pick_sections')->add_dependency('load_on_iframe','1','!='),
				)),
				Field::create( 'repeater', 'attributes', __('HTML Attributes','mv23theme') )->set_layout( 'table' )->set_add_text(__('Add Attribute','mv23theme'))
            		->add_group('item', array(
            		    'fields' => array(
            		        Field::create( 'select', 'status', __('Status','mv23theme') )->set_width( 30 )->add_options(array(
								'beforeSend' => __('Before Send','mv23theme'), 
								'success' => __('Success','mv23theme'), 
								'error' => __('Error','mv23theme') 
							)),
            		        Field::create( 'text', 'attribute', __('HTML Atribute','mv23theme') )->set_width( 30 ),
            		        Field::create( 'text', 'value', __('Value','mv23theme') )->set_width( 30 )
            		    )
					)
				)
			)),
			Field::create( 'tab', 'style_tab', __('Appearance') ),
			Field::create( 'image_select', 'aspect_ratio', __('Placeholder Aspect Ratio') )->add_options(array(
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
                'custom'  => array(
                    'label' => 'custom',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-custom.png'
                ),
            )),
			Field::create( 'text', 'custom_aspect_ratio' )
                ->set_validation_rule('^(\d+(\.\d+)?)(\s*\/\s*(\d+(\.\d+)?))?$')
				->set_description( __('Enter a custom aspect ratio in the format "width/height", e.g., "3/2" or "1.5/1".', 'mv23theme') )
				->add_dependency( 'aspect_ratio', 'custom' ),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;

		$args['additional_classes'][] = 'component';
		$args['additional_attributes'] = array( "data-settings='".json_encode($args['async_settings'])."'" );
		$args['additional_styles']['aspect-ratio'] = isset( $args['aspect_ratio'] ) ? $args['aspect_ratio'] : '1/1';
	
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new OCE_Dynamic_Content();