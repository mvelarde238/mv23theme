<?php
namespace Core\Offcanvas_Elements;

use Ultimate_Fields\Container\Repeater_Group;

/**
 * Handles restriction definitions.
 *
 */
abstract class Restriction {
	/**
	 * Indicate that the restriction can only be used once within a container.
	 *
	 * @var bool
	 */
	const LIMIT = false;

	/**
	 * Returns the type of the restriction (e.g. page).
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
	 * Returns the result of restrictions checking.
	 *
	 * @return bool
	 */
	/* abstract */ public static function check_restrictions( $restriction_data ) {
		return false;
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return mixed bool if not implemented || backbone template
	 */
	/* abstract */ public static function get_title_template() {
		return false;
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
			->add_fields( $fields )
            ->set_edit_mode( 'popup' )
			->set_description_position( 'label' );

		$title_template = static::get_title_template();
		if( $title_template ) $group->set_title_template( $title_template );

		return $group;
	}
}
