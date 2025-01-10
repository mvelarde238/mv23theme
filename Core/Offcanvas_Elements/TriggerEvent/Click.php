<?php
namespace Core\Offcanvas_Elements\TriggerEvent;

use Core\Offcanvas_Elements\TriggerEvent;
use Ultimate_Fields\Field;

/**
 * Handles the click event
 */
class Click extends TriggerEvent {
	/**
	 * Returns the type of the event
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'click';
	}

	/**
	 * Returns the name of the event.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Click', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = 'The element will show on click: <%= selector %>';
		
		return $template;
	}

	/**
	 * Returns the fields for the trigger event.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'text', 'selector', __( 'Selector', 'mv23theme' ) )
            ->set_description( __( 'Please enter the CSS selector that matches the element(s) which will act as a trigger to display the offcanvas element. For example, if clicking a button should be used, enter the CSS selector for that button.', 'mv23theme' ) )
            ->required();

		$fields[] = Field::create( 'checkbox', 'delegate_to_body', __('Delegate event to Body','mv23theme') )->set_description(__('Delegate event to document.body. Useful for async content pages.','mv23theme'))->fancy();

		return $fields;
	}
}
