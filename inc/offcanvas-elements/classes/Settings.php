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

		Container::create( $slug.'_settings' )
            ->add_location( 'post_type', $slug )
			->set_description_position('label')
			->set_fields_callback( array( $this, 'generate_restriction_fields' ) );
	}

	/**
	 * Returns the classes for restrictions.
	 *
	 * @return string[]
	 */
	public static function get_restriction_classes() {
		return array(
			\Offcanvas_Elements\Restriction\Page::class,
            \Offcanvas_Elements\Restriction\User::class,
            \Offcanvas_Elements\Restriction\Device::class,
            \Offcanvas_Elements\Restriction\Browser::class,
		);
	}

	/**
	 * Generates the fields for restriction settings.
	 * @return Field[]
	 */
	public function generate_restriction_fields() {
        $slug = Core::getInstance()->get_slug();

		$restrictions_field = Field::create( 'repeater', $slug.'_restrictions' )
			->set_chooser_type( 'tags' )
			->set_add_text( __('Add Restriction','default') )
            ->set_description( __('Add restrictions to show the element based on conditions.','default') );

		# Generate all restrictions
		$restriction_classes = self::get_restriction_classes();

		foreach( $restriction_classes as $class_name ) {
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
}
