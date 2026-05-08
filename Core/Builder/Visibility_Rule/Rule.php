<?php
namespace Core\Builder\Visibility_Rule;

use Ultimate_Fields\Container\Repeater_Group;

/**
 * Handles rule definitions.
 *
 */
abstract class Rule {
	/**
	 * Indicate that the rule can only be used once within a container.
	 *
	 * @var bool
	 */
	const LIMIT = false;

	/**
	 * Returns the type of the rule (e.g. page).
	 *
	 * @return string
	 */
	/* abstract */ public static function get_type() {
		return 'none';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	/* abstract */ public static function get_name() {
		return '';
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	/* abstract */ public static function get_fields() {
		return array();
	}

	/**
	 * Evaluates whether the current rule's conditions are met for the current context.
	 *
	 * Return true  → conditions ARE met → element should be visible.
	 * Return false → conditions are NOT met → element should be hidden.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool
	 */
	/* abstract */ public static function matches( $rule_data ) {
		return true;
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
	 * Exports the settings for the current rule.
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
