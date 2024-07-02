<?php
namespace Offcanvas_Elements\TriggerEvent;

use Offcanvas_Elements\TriggerEvent;
use Ultimate_Fields\Field;

/**
 * Handles the click event
 */
class Click extends TriggerEvent {
	/**
	 * Returns the type of the restriction
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'click';
	}

	/**
	 * Returns the name of the restriction.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Click', 'default' );
	}

	/**
	 * Returns the fields for the trigger event.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array(
			Field::create( 'text', '_title' )
		);

		$fields[] = Field::create( 'text', 'selector', __( 'Selector', 'default' ) )
            ->set_description( __( 'Please enter the CSS selector that matches the element(s) which will act as a trigger to display the offcanvas element. For example, if clicking a button should be used, enter the CSS selector for that button.', 'default' ) )
            ->required();

		return $fields;
	}
}
