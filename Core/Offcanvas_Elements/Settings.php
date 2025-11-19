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
			Field::create( 'ultimate_builder', 'page_content', __('Content','mv23theme') )
				->add_groups( Builder_Core::getInstance()->get_groups_for_builder() )
		);
	}
}
