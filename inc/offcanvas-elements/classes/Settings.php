<?php
namespace Offcanvas_Elements;

use Offcanvas_Elements\Core;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

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

		// TODO: transform type selector into image_select .... with text

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
	 *
	 * @return string[]
	 */
	public static function get_classes_for( $key ) {
		switch ($key) {
			case 'restrictions':
				return array(
					\Offcanvas_Elements\Restriction\Page::class,
					\Offcanvas_Elements\Restriction\User::class,
					\Offcanvas_Elements\Restriction\Device::class,
					\Offcanvas_Elements\Restriction\Browser::class,
				);
				break;
			
			case 'trigger_events':
				return array(
					\Offcanvas_Elements\TriggerEvent\Click::class
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

		// TODO: ->set_minimum( 1 ) ERROR MESSAGE TRIGGERINS ALWAYS UNTIL THE TAB IS CLICKED

		$trigger_events_field = Field::create( 'repeater', $slug.'_trigger_events' )
			->set_chooser_type( 'tags' )
			->set_add_text( __('Add Trigger Event','default') )
            ->set_description( __('Add at least one trigger event to show the element.','default') );
			// ->set_minimum( 1 );

		# Generate all trigger_events
		$trigger_events_classes = self::get_classes_for( 'trigger_events' );
		foreach( $trigger_events_classes as $class_name ) {
			$group = $class_name::settings();
            $group->set_layout('table');
            $group->set_description_position('label');
			$trigger_events_field->add_group( $group );
		}

		return array( 
            Field::create('tab', __('Trigger Events','default') ),
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
				'popup' => array(
					'label' => __('Pop Up','default'),
					'image' => get_template_directory_uri() . '/inc/offcanvas-elements/images/popup.png'
				),
				'sidenav' => array(
					'label' => __('Sidenav','default'),
					'image' => get_template_directory_uri() . '/inc/offcanvas-elements/images/sidenav.png'
				),
				'bottomsheet' => array(
					'label' => __('Bottom Sheet','default'),
					'image' => get_template_directory_uri() . '/inc/offcanvas-elements/images/bottomsheet.png'
				),
				'taptarget' => array(
					'label' => __('Tap Target','default'),
					'image' => get_template_directory_uri() . '/inc/offcanvas-elements/images/taptarget.png'
				)
			)),
			\Content_Layout::the_field(array( 'slug' => $slug.'_content' ))
		);
	}

	/**
	 * Generates the fields for settings tab.
	 * @return Field[]
	 */
	public function generate_settings_fields() {
		$slug = Core::getInstance()->get_slug();

		// TODO: ADD COLOR SCHEME

		$opacity_field = Field::create( 'number', 'alpha', __('Opacity','default') )->add_dependency('use')->set_placeholder('0')->enable_slider(0,100,1)->set_default_value(100)->set_width( 40 );

		return array(
			Field::create('tab', __('Settings','default') ),
			Field::create('complex',$slug.'_sidenav_settings', __('Sidenav Settings','default'))->set_layout('rows')->hide_label()->add_dependency($slug.'_type','sidenav','=')->add_fields(array( 
				Field::create( 'select', 'position' )->add_options(array(
					'left' => __('Left'),
					'right' => __('Right')
				))->set_input_type( 'radio' )->set_orientation( 'horizontal' ),
				// Field::create( 'checkbox', 'dismisible' )->set_description(__('Allow modal to be dismissed by keyboard or overlay click.','default'))->set_default_value(1)->fancy(),
				Field::create( 'complex', 'background_color' )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#ffffff')->add_dependency('use')->set_width( 30 ),
					$opacity_field
				)),
				// Field::create( 'complex', 'close_icon_color' )->add_fields(array(
				// 	Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
				// 	Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
				// 	$opacity_field
				// )),
				// Field::create( 'complex', 'padding' )->add_fields(array(
				// 	Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 50 ),
				// 	Field::create( 'text', 'number' )->set_default_value(40)->set_suffix('px')->add_dependency('use')->hide_label()->set_width( 50 )
				// )),
				Field::create( 'text', 'max_width' )->set_default_value(370)->set_suffix('px'),
				Field::create( 'complex', 'overlay_color', __('Overlay Color','default') )->add_fields(array(
					Field::create( 'checkbox', 'use' )->set_default_value(1)->fancy()->hide_label()->set_width( 20 ),
					Field::create( 'color', 'color' )->set_default_value('#000000')->add_dependency('use')->set_width( 30 ),
					$opacity_field
				)),
				Field::create( 'complex', '__on_open-wrapper', __('On open callback','default') )->merge()->add_fields(array(
					Field::create( 'textarea', 'on_open' )->set_description( __('A function to be called when sideNav is opened.','default') )->set_attr(array(
						'data-type' => 'html'
					))->hide_label(),
					Field::create( 'message', 'Hint_1' )->set_description('Usar < script >...< /script >')->hide_label(),
					Field::create( 'message', 'Hint_2' )->set_description('Jquery : (function($){ ... })(jQuery)')->hide_label(),
				))
			))
		);
	}
}
