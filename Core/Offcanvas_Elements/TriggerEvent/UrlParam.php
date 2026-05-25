<?php
namespace Core\Offcanvas_Elements\TriggerEvent;

use Core\Offcanvas_Elements\TriggerEvent;
use Ultimate_Fields\Field;

/**
 * Handles the URL query parameter trigger event.
 */
class UrlParam extends TriggerEvent {
	/**
	 * Returns the type of the event.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'url_param';
	}

	/**
	 * Returns the name of the event.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'URL Parameter', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group.
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		return 'The element will show when URL has: ?<%= param_name %>=<%= param_value %>';
	}

	/**
	 * Returns the fields for the trigger event.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		return array(
			Field::create( 'text', 'param_name', __( 'Parameter Name', 'mv23theme' ) )
				->set_description( __( 'Parameter name, e.g. modal', 'mv23theme' ) )
				->required(),
			Field::create( 'text', 'param_value', __( 'Parameter Value', 'mv23theme' ) )
				->set_description( __( 'Parameter value, e.g. contact', 'mv23theme' ) )
				->required()
		);
	}
}
