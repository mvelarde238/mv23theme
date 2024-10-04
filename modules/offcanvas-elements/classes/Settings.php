<?php
namespace Offcanvas_Elements;

use Offcanvas_Elements\Core;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Blocks_Layout;

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
					\Offcanvas_Elements\Restriction\Page::class,
					\Offcanvas_Elements\Restriction\User::class,
					\Offcanvas_Elements\Restriction\Device::class,
					\Offcanvas_Elements\Restriction\Plugin::class,
					// \Offcanvas_Elements\Restriction\Browser::class,
				);
				break;
			
			case 'trigger_events':
				return array(
					\Offcanvas_Elements\TriggerEvent\Click::class,
					\Offcanvas_Elements\TriggerEvent\Scroll::class,
					\Offcanvas_Elements\TriggerEvent\CustomEvent::class
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
			->set_add_text( __('Add Restriction','default') )
            ->set_description( __('Add restrictions to show the element based on conditions.','default') );

		# Generate all restrictions
		$restrictions_classes = self::get_classes_for( 'restrictions' );
		foreach( $restrictions_classes as $class_name ) {
			$group = $class_name::settings();
            $group->set_layout('table');
            $group->set_description_position('label');
			$restrictions_field->add_group( $group );
		}

		return array( 
            Field::create('tab', __('Restrictions','default') ),
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
			->set_add_text( __('Add Trigger Event','default') )
            ->set_description( __('Add at least one trigger event to show the element.','default') );

		# Generate all trigger_events
		$trigger_events_classes = self::get_classes_for( 'trigger_events' );
		foreach( $trigger_events_classes as $class_name ) {
			$group = $class_name::settings();
            $group->set_layout('table');
            $group->set_description_position('label');
			$trigger_events_field->add_group( $group );
		}

		return array( 
            Field::create('tab', __('Trigger','default') ),
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
			Field::create('tab', __('Content','default') ),
			Field::create('image_select', $slug.'_type', __('Type','default') )->show_label()->add_options(array(
				'modal' => array(
					'label' => __('Modal','default'),
					'image' => get_template_directory_uri() . '/modules/offcanvas-elements/images/modal.png'
				),
				'sidenav' => array(
					'label' => __('Sidenav','default'),
					'image' => get_template_directory_uri() . '/modules/offcanvas-elements/images/sidenav.png'
				),
				'bottom_sheet' => array(
					'label' => __('Bottom Sheet','default'),
					'image' => get_template_directory_uri() . '/modules/offcanvas-elements/images/bottom_sheet.png'
				)
				// 'tap_target' => array(
				// 	'label' => __('Tap Target','default'),
				// 	'image' => get_template_directory_uri() . '/modules/offcanvas-elements/images/tap_target.png'
				// )
			)),
			Field::create( 'section', '__content', __('Content','default') ),
			Field::create('radio', $slug.'_content_type',__('Content Type','default'))->set_orientation('horizontal')->add_options(array(
				'layout' => __('Layout','default'), 
				'async' => __('Asynchronous Content','default') 
			)),
			Blocks_Layout::the_field(array( 'slug' => $slug.'_content', 'hide_label' => false ))->add_dependency($slug.'_content_type','layout','='),
			Field::create( 'complex', $slug.'_async_settings', __('Asynchronous Content Settings') )->add_dependency($slug.'_content_type','async','=')->set_layout('rows')->hide_label()->add_fields(array(
				Field::create( 'message', '_hint-1','' )->set_description( __('When this setting is active the content will be generated using an asynchronous function','default') ),
				Field::create( 'complex', '_fields_group_1', '' )->merge()->add_fields(array(
					Field::create( 'radio', 'content_source', __('Content Source','default') )->add_options(array(
						'page' => __( 'Page: generate the content from an internal page', 'default' ),
						'url' => __( 'Url: generate the content from an url', 'default' ),
						'link' => __( 'Link: generate the content dinamically from the link clicked', 'default' )
					))->set_width(50),
					Field::create( 'wp_object', 'page_source', __( 'Page item', 'default' ) )->add( 'posts' )->add_dependency('content_source','page','=')->add_dependency('../../'.$slug.'_content_type','async','=')->required()->set_width(50),
					Field::create( 'text', 'url_source', __( 'Enter the url', 'default' ) )->add_dependency('content_source','url','=')->add_dependency('../../'.$slug.'_content_type','async','=')->required(),
					Field::create( 'message', '_url_source_hint' )->set_description(__('Use trigger events tab to add a CSS selector for the link source','default'))->add_dependency('content_source','link','=')->hide_label(),
					Field::create( 'checkbox', 'clear_on_close' )->set_description(__('Clear the content on close','default'))->set_default_value(1)->fancy()->set_width(20),
					Field::create( 'checkbox', 'load_on_iframe' )->set_description(__('Load the content in an iframe','default'))->set_default_value(1)->fancy()->set_width(20),
					Field::create( 'checkbox', 'cherry_pick_sections' )->set_description(__('Let you choose only the sections you want','default'))->fancy()->add_dependency('load_on_iframe','1','!=')->set_width(20),
					Field::create( 'text', 'cherry_picked_sections', __( 'CSS Selector for cherry picked sections', 'default' ) )->set_description( __( 'Please enter the CSS selector that matches the section(s) you want to display in the offcanvas element.', 'default' ) )->add_dependency('cherry_pick_sections')->add_dependency('load_on_iframe','1','!='),
				)),
				Field::create( 'repeater', 'attributes', __('HTML Attributes','default') )->set_layout( 'table' )->set_add_text(__('Add Attribute','default'))
            		->add_group('item', array(
            		    'fields' => array(
            		        Field::create( 'select', 'status', __('Status','default') )->set_width( 30 )->add_options(array(
								'beforeSend' => __('Before Send','default'), 
								'success' => __('Success','default'), 
								'error' => __('Error','default') 
							)),
            		        Field::create( 'text', 'attribute', __('HTML Atribute','default') )->set_width( 30 ),
            		        Field::create( 'text', 'value', __('Value','default') )->set_width( 30 )
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

		$color_schemes = array(
			'' => __('Default','default'),
			'default-scheme' => __('Light','default'),
			'dark-scheme' => __('Dark','default')
		);

		return array(
			Field::create('tab', __('Settings','default') ),

			Field::create('complex',$slug.'_modal_settings' )->set_layout('rows')->hide_label()->add_dependency($slug.'_type','modal','=')->add_fields(array( 
				Field::create( 'checkbox', 'dismissible' )->set_description(__('Allow modal to be dismissed by keyboard or overlay click.','default'))->set_default_value(1)->fancy(),
				Field::create( 'checkbox', 'close_on_click' )->set_description(__('Closes element on <a> clicks.','default'))->fancy(),
				Field::create( 'complex', 'background_color' )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'select', 'color_scheme', __('Color Scheme','default') )->add_options( $color_schemes )->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#ffffff')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 30 )
				)),
				// Field::create( 'complex', 'close_icon_color' )->add_fields(array(
				// 	Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
				// 	Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
				// 	Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 40 )
				// )),
				// Field::create( 'complex', 'padding' )->add_fields(array(
				// 	Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 50 ),
				// 	Field::create( 'text', 'number' )->set_default_value(40)->set_suffix('px')->add_dependency('use')->hide_label()->set_width( 50 )
				// )),
				Field::create( 'number', 'max_width' )->set_default_value(666)->set_suffix('px'),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','default') )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
				))
			)),

			Field::create('complex',$slug.'_bottom_sheet_settings')->set_layout('rows')->hide_label()->add_dependency($slug.'_type','bottom_sheet','=')->add_fields(array( 
				Field::create( 'checkbox', 'dismissible' )->set_description(__('Allow modal to be dismissed by keyboard or overlay click.','default'))->set_default_value(1)->fancy(),
				Field::create( 'checkbox', 'close_on_click' )->set_description(__('Closes element on <a> clicks.','default'))->fancy(),
				Field::create( 'complex', 'background_color' )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'select', 'color_scheme', __('Color Scheme','default') )->add_options( $color_schemes )->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#ffffff')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 30 )
				)),
				Field::create( 'number', 'max_height' )->set_default_value(140)->set_suffix('px'),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','default') )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
				)),
			)),

			Field::create('complex',$slug.'_sidenav_settings')->set_layout('rows')->hide_label()->add_dependency($slug.'_type','sidenav','=')->add_fields(array( 
				Field::create( 'select', 'position' )->add_options(array(
					'left' => __('Left'),
					'right' => __('Right')
				))->set_input_type( 'radio' )->set_orientation( 'horizontal' ),
				Field::create( 'checkbox', 'close_on_click', __('Close on click','default') )->set_description(__('Closes element on link clicks.','default'))->fancy(),
				Field::create( 'complex', 'background_color' )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'select', 'color_scheme', __('Color Scheme','default') )->add_options( $color_schemes )->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#ffffff')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 30 )
				)),
				Field::create( 'number', 'max_width' )->set_default_value(300)->set_suffix('px'),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','default') )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(50)->set_width( 40 )
				)),
				// Field::create( 'complex', '__on_open-wrapper', __('On open callback','default') )->merge()->add_fields(array(
					// Field::create( 'textarea', 'on_open' )->set_description( __('A function to be called when sideNav is opened.','default') )->set_attr(array(
						// 'data-type' => 'html'
					// ))->hide_label()
					// Field::create( 'message', 'Hint_1' )->set_description('Usar < script >...< /script >')->hide_label(),
				// 	// Field::create( 'message', 'Hint_2' )->set_description('Jquery : (function($){ ... })(jQuery)')->hide_label(),
				// ))
			))
		);
	}
}
