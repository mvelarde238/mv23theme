<?php
namespace Core\Offcanvas_Elements;

use Core\Offcanvas_Elements\Core;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core as Builder_Core;

/**
 * Handles settings on the post type edit screen.
 */
class Settings {
	/**
	 * Holds the container with the settings.
	 * @var Container
	 */
	protected $container;

	/**
	 * Creates an instance of the class.
	 *
	 * @return Container_Settings
	 */
	public static function instance() {
		static $instance;

		if( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}

	/**
	 * Adds the neccessary hooks.
	 */
	protected function __construct() {
        $slug = Core::getInstance()->get_slug();

		Container::create( $slug.'_settings' )
            ->add_location( 'post_type', $slug )
			->set_description_position('label')
			->add_fields( $this->generate_content_fields() )
			->add_fields( $this->generate_settings_fields() )
			->add_fields( $this->generate_trigger_events_fields() )
			->add_fields( $this->generate_restrictions_fields() );
	}

	/**
	 * Returns the needed classes
	 */
	public static function get_classes_for( $key ) {
		switch ($key) {
			case 'restrictions':
				return array(
					\Core\Offcanvas_Elements\Restriction\Page::class,
					\Core\Offcanvas_Elements\Restriction\User::class,
					\Core\Offcanvas_Elements\Restriction\Device::class,
					\Core\Offcanvas_Elements\Restriction\Plugin::class,
					// \Core\Offcanvas_Elements\Restriction\Browser::class,
				);
				break;
			
			case 'trigger_events':
				return array(
					\Core\Offcanvas_Elements\TriggerEvent\Click::class,
					\Core\Offcanvas_Elements\TriggerEvent\Scroll::class,
					\Core\Offcanvas_Elements\TriggerEvent\CustomEvent::class
				);

			default:
				return array();
				break;	
		}
	}

	/**
	 * Generates the fields for restriction settings.
	 * @return Field[]
	 */
	public function generate_restrictions_fields() {
        $slug = Core::getInstance()->get_slug();

		$restrictions_field = Field::create( 'repeater', $slug.'_restrictions' )
			->set_chooser_type( 'tags' )
			->set_add_text( __('Add Restriction','mv23theme') )
            ->set_description( __('Add restrictions to show the element based on conditions.','mv23theme') );

		# Generate all restrictions
		$restrictions_classes = self::get_classes_for( 'restrictions' );
		foreach( $restrictions_classes as $class_name ) {
			$group = $class_name::settings();
            $group->set_layout('table');
            $group->set_description_position('label');
			$restrictions_field->add_group( $group );
		}

		return array( 
            Field::create('tab', __('Restrictions','mv23theme') ),
            $restrictions_field 
        );
	}

	/**
	 * Generates the fields for trigger event settings.
	 * @return Field[]
	 */
	public function generate_trigger_events_fields() {
        $slug = Core::getInstance()->get_slug();

		$trigger_events_field = Field::create( 'repeater', $slug.'_trigger_events' )
			->set_chooser_type( 'tags' )
			->set_add_text( __('Add Trigger Event','mv23theme') )
            ->set_description( __('Add at least one trigger event to show the element.','mv23theme') );

		# Generate all trigger_events
		$trigger_events_classes = self::get_classes_for( 'trigger_events' );
		foreach( $trigger_events_classes as $class_name ) {
			$group = $class_name::settings();
            $group->set_layout('table');
            $group->set_description_position('label');
			$trigger_events_field->add_group( $group );
		}

		return array( 
            Field::create('tab', __('Trigger','mv23theme') ),
            $trigger_events_field 
        );
	}

	/**
	 * Generates the fields for content tab.
	 * @return Field[]
	 */
	public function generate_content_fields() {
		$slug = Core::getInstance()->get_slug();

		return array(
			Field::create('tab', __('Content','mv23theme') ),
			Field::create('image_select', $slug.'_type', __('Type','mv23theme') )->show_label()->add_options(array(
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
			Field::create('radio', $slug.'_content_type',__('Content Type','mv23theme'))->set_orientation('horizontal')->add_options(array(
				'layout' => __('Layout','mv23theme'), 
				'async' => __('Asynchronous Content','mv23theme') 
			)),
			Field::create( 'ultimate_builder', 'page_content' )
				->add_groups( Builder_Core::getInstance()->get_groups_for_builder() )
				->add_dependency($slug.'_content_type','layout','='),
			Field::create( 'complex', $slug.'_async_settings', __('Asynchronous Content Settings') )->add_dependency($slug.'_content_type','async','=')->set_layout('rows')->hide_label()->add_fields(array(
				Field::create( 'message', '_hint-1','' )->set_description( __('When this setting is active the content will be generated using an asynchronous function','mv23theme') ),
				Field::create( 'complex', '_fields_group_1', '' )->merge()->add_fields(array(
					Field::create( 'radio', 'content_source', __('Content Source','mv23theme') )->add_options(array(
						'page' => __( 'Page: generate the content from an internal page', 'mv23theme' ),
						'url' => __( 'Url: generate the content from an url', 'mv23theme' ),
						'link' => __( 'Link: generate the content dinamically from the link clicked', 'mv23theme' )
					))->set_width(50),
					Field::create( 'wp_object', 'page_source', __( 'Page item', 'mv23theme' ) )->add( 'posts' )->add_dependency('content_source','page','=')->add_dependency('../../'.$slug.'_content_type','async','=')->required()->set_width(50),
					Field::create( 'text', 'url_source', __( 'Enter the url', 'mv23theme' ) )->add_dependency('content_source','url','=')->add_dependency('../../'.$slug.'_content_type','async','=')->required(),
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
			))
		);
	}

	/**
	 * Generates the fields for settings tab.
	 * @return Field[]
	 */
	public function generate_settings_fields() {
		$slug = Core::getInstance()->get_slug();

		return array(
			Field::create('tab', __('Settings','mv23theme') ),

			Field::create( 'complex', '_common_settings_wrapper' )
				->merge()->hide_label()
				->set_attr( 'class', 'components-settings-complex-styles' )
				->add_fields(array(
					Field::create( 'common_settings_control', $slug.'_settings' )
						->set_container( 'common_settings_container' )
						->set_hidden_fields( array('layout', 'helpers', 'visibility', 'responsive') )
						->set_add_text( __('Settings', 'mv23theme') )
						->hide_label()
				)),

			Field::create('complex',$slug.'_modal_settings' )->set_layout('rows')->hide_label()->add_dependency($slug.'_type','modal','=')->add_fields(array( 
				Field::create( 'checkbox', 'dismissible' )->set_description(__('Allow modal to be dismissed by keyboard or overlay click.','mv23theme'))->set_default_value(1)->fancy(),
				Field::create( 'checkbox', 'close_on_click' )->set_description(__('Closes element on link clicks.','mv23theme'))->fancy(),
				// Field::create( 'complex', 'close_icon_color' )->add_fields(array(
				// 	Field::create( 'checkbox', 'use', __('Customize','mv23theme') )->fancy()->hide_label()->set_width( 20 ),
				// 	Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
				// 	Field::create( 'number', 'alpha', __('Opacity','mv23theme') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 40 )
				// )),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','mv23theme') )->add_fields(array(
					Field::create( 'checkbox', 'use', __('Customize','mv23theme') )->fancy()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','mv23theme') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
				)),
				Field::create( 'number', 'max_width' )->set_default_value(666)->set_suffix('px'),
			)),

			Field::create('complex',$slug.'_bottom_sheet_settings')->set_layout('rows')->hide_label()->add_dependency($slug.'_type','bottom_sheet','=')->add_fields(array( 
				Field::create( 'checkbox', 'dismissible' )->set_description(__('Allow modal to be dismissed by keyboard or overlay click.','mv23theme'))->set_default_value(1)->fancy(),
				Field::create( 'checkbox', 'close_on_click' )->set_description(__('Closes element on link clicks.','mv23theme'))->fancy(),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','mv23theme') )->add_fields(array(
					Field::create( 'checkbox', 'use', __('Customize','mv23theme') )->fancy()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','mv23theme') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
				)),
				Field::create( 'number', 'max_height' )->set_default_value(140)->set_suffix('px')
			)),

			Field::create('complex',$slug.'_sidenav_settings')->set_layout('rows')->hide_label()->add_dependency($slug.'_type','sidenav','=')->add_fields(array( 
				Field::create( 'select', 'position', __('Position','mv23theme') )->add_options(array(
					'left' => __('Left'),
					'right' => __('Right')
				))->set_input_type( 'radio' )->set_orientation( 'horizontal' ),
				Field::create( 'checkbox', 'close_on_click', __('Close on click','mv23theme') )->set_description(__('Closes element on link clicks.','mv23theme'))->fancy(),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','mv23theme') )->add_fields(array(
					Field::create( 'checkbox', 'use', __('Customize','mv23theme') )->fancy()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','mv23theme') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
				)),
				Field::create( 'number', 'max_width', __('Max Width','mv23theme') )->set_default_value(360)->set_suffix('px'),
				// Field::create( 'complex', '__on_open-wrapper', __('On open callback','mv23theme') )->merge()->add_fields(array(
					// Field::create( 'textarea', 'on_open' )->set_description( __('A function to be called when sideNav is opened.','mv23theme') )->set_attr(array(
						// 'data-type' => 'html'
					// ))->hide_label()
					// Field::create( 'message', 'Hint_1' )->set_description('Usar < script >...< /script >')->hide_label(),
				// 	// Field::create( 'message', 'Hint_2' )->set_description('Jquery : (function($){ ... })(jQuery)')->hide_label(),
				// ))
			))
		);
	}
}
