<?php
namespace Core\Offcanvas_Elements\TriggerEvent;

use Core\Offcanvas_Elements\TriggerEvent;
use Ultimate_Fields\Field;

/**
 * Handles the URL hash trigger event.
 */
class HashEvent extends TriggerEvent {
	/**
	 * Returns the type of the event.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'hash_event';
	}

	/**
	 * Returns the name of the event.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'URL Hash', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group.
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		return 'The element will show when URL hash is: #<%= hash_value %>';
	}

	/**
	 * Returns the fields for the trigger event.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		return array(
			Field::create( 'text', 'hash_value', __( 'Hash Value', 'mv23theme' ) )
				->set_description( __( 'Enter the hash without #, e.g. open-modal', 'mv23theme' ) )
				->required()
		);
	}
}
