<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the URL / query-parameter visibility rule.
 *
 * Evaluates $_GET values so elements can be shown or hidden based on
 * whether a specific query parameter exists — and optionally whether its
 * value matches a given string.
 */
class URL_Parameter extends Rule {
	/**
	 * Returns the type identifier of the rule.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'url_parameter';
	}

	/**
	 * Returns the human-readable name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'URL Parameter', 'mv23theme' );
	}

	/**
	 * Returns the Backbone title template for the repeater group row.
	 *
	 * @return string
	 */
	public static function get_title_template() {
		$template = '<% if (operator == "exists" || operator == "not_exists") { %>
			Visible if param \'<%= param_key %>\' <%= operator %>
		<% } else { %>
			Visible if param \'<%= param_key %>\' <%= operator %> \'<%= param_value %>\'
		<% } %>';

		return $template;
	}

	/**
	 * Returns the fields for the rule group.
	 *
	 * @return \Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'text', 'param_key', __( 'Parameter Name', 'mv23theme' ) )
			->required()
			->set_description( __( 'The URL query parameter to evaluate (e.g. "promo").', 'mv23theme' ) );

		$fields[] = Field::create( 'select', 'operator', __( 'Operator', 'mv23theme' ) )
			->required()
			->set_input_type( 'radio' )
			->add_options( array(
				'exists'     => __( 'Exists', 'mv23theme' ),
				'not_exists' => __( 'Does not exist', 'mv23theme' ),
				'equals'     => __( 'Equals', 'mv23theme' ),
				'not_equals' => __( 'Does not equal', 'mv23theme' ),
				'contains'   => __( 'Contains', 'mv23theme' ),
			) );

		$fields[] = Field::create( 'text', 'param_value', __( 'Parameter Value', 'mv23theme' ) )
			->set_description( __( 'The value to compare against (e.g. "black-friday").', 'mv23theme' ) )
			->add_dependency( 'operator', 'exists', '!=' )
			->add_dependency( 'operator', 'not_exists', '!=' );

		return $fields;
	}

	/**
	 * Evaluates whether the rule's conditions are met for the current request.
	 *
	 * Reads from $_GET so it works on both front-end and AJAX requests.
	 * Returns true (visible) when the condition is satisfied.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool
	 */
	public static function matches( $rule_data ) {
		$param_key   = isset( $rule_data['param_key'] ) ? sanitize_key( $rule_data['param_key'] ) : '';
		$operator    = isset( $rule_data['operator'] ) ? $rule_data['operator'] : 'exists';
		$param_value = isset( $rule_data['param_value'] ) ? $rule_data['param_value'] : '';

		if ( empty( $param_key ) ) {
			return true;
		}

		$param_exists        = isset( $_GET[ $param_key ] ); // phpcs:ignore WordPress.Security.NonceVerification
		$current_param_value = $param_exists ? $_GET[ $param_key ] : null; // phpcs:ignore WordPress.Security.NonceVerification

		$visibility_check = array();

		switch ( $operator ) {
			case 'exists':
				$visibility_check[] = $param_exists;
				break;
			case 'not_exists':
				$visibility_check[] = ! $param_exists;
				break;
			case 'equals':
				$visibility_check[] = $param_exists && ( (string) $current_param_value === (string) $param_value );
				break;
			case 'not_equals':
				$visibility_check[] = ! $param_exists || ( (string) $current_param_value !== (string) $param_value );
				break;
			case 'contains':
				$visibility_check[] = $param_exists && ( strpos( (string) $current_param_value, $param_value ) !== false );
				break;
		}

		return ! in_array( false, $visibility_check, true );
	}
}
