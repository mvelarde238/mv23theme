<?php
namespace Offcanvas_Elements;

use Ultimate_Fields\Container\Repeater_Group;

/**
 * Handles restriction definitions.
 *
 */
abstract class TriggerEvent {
	/**
	 * Indicate that the restriction can only be used once within a container.
	 *
	 * @var bool
	 */
	const LIMIT = false;

	/**
	 * Returns the type of the restriction (e.g. click).
	 *
	 * @return string
	 */
	/* abstract */ public static function get_type() {
		return 'none';
	}

	/**
	 * Returns the name of the restriction.
	 *
	 * @return string
	 */
	/* abstract */ public static function get_name() {
		return '';
	}

	/**
	 * Returns the fields for the restriction.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	/* abstract */ public static function get_fields() {
		return array();
	}

	/**
	 * Exports the settings for the current restriction.
	 *
	 * @return Ultimate_Fields\Container\Repeater_Group
	 */
	public static function settings() {
		$type   = call_user_func( array( get_called_class(), 'get_type' ) );
		$name   = call_user_func( array( get_called_class(), 'get_name' ) );
		$fields = call_user_func( array( get_called_class(), 'get_fields' ) );

		$group = new Repeater_Group( $type );
		$group->set_title( $name )
			// ->set_title_template( '' );
			->add_fields( $fields )
            ->set_edit_mode( 'popup' )
			->set_description_position( 'label' );

		return $group;
	}
}
