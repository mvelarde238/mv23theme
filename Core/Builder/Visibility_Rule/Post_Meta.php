<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the post meta rule
 */
class Post_Meta extends Rule {
	/**
	 * Returns the type of the rule.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'post_meta';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Post Meta', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = '<% if (operator == "exists" || operator == "not_exists") { %>
			Visible if meta \'<%= meta_key %>\' <%= operator %>
		<% } else { %>
			Visible if meta \'<%= meta_key %>\' <%= operator %> \'<%= meta_value %>\'
		<% } %>';

		return $template;
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'text', 'meta_key', __( 'Meta Key', 'mv23theme' ) )
			->required()
			->set_description( __( 'The meta key to evaluate.', 'mv23theme' ) );

		$fields[] = Field::create( 'select', 'operator', __( 'Operator', 'mv23theme' ) )
			->required()
			->set_input_type( 'radio' )
			->add_options( array(
				'exists'       => __( 'Exists', 'mv23theme' ),
				'not_exists'   => __( 'Does not exist', 'mv23theme' ),
				'equals'       => __( 'Equals', 'mv23theme' ),
				'not_equals'   => __( 'Does not equal', 'mv23theme' ),
				'contains'     => __( 'Contains', 'mv23theme' ),
				'greater_than' => __( 'Greater than', 'mv23theme' ),
				'less_than'    => __( 'Less than', 'mv23theme' ),
			) );

		$fields[] = Field::create( 'text', 'meta_value', __( 'Meta Value', 'mv23theme' ) )
			->set_description( __( 'The value to compare against.', 'mv23theme' ) )
			->add_dependency( 'operator', 'exists', '!=' )
			->add_dependency( 'operator', 'not_exists', '!=' );

		return $fields;
	}

	/**
	 * Evaluates whether the rule's conditions are met for the current context.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool True if conditions are met (element visible), false otherwise.
	 */
	public static function matches( $rule_data ) {
		$restrictions_check_in = array();

		$meta_key   = isset( $rule_data['meta_key'] ) ? $rule_data['meta_key'] : '';
		$operator   = isset( $rule_data['operator'] ) ? $rule_data['operator'] : 'exists';
		$meta_value = isset( $rule_data['meta_value'] ) ? $rule_data['meta_value'] : '';

		if ( empty( $meta_key ) ) {
			return true;
		}

		$current_post_id = get_the_ID();
		$meta            = get_post_meta( $current_post_id, $meta_key, true );
		$visibility_check = array();

		switch ( $operator ) {
			case 'exists':
				$visibility_check[] = ( $meta !== '' && $meta !== false );
				break;
			case 'not_exists':
				$visibility_check[] = ( $meta === '' || $meta === false );
				break;
			case 'equals':
				$visibility_check[] = ( (string) $meta === (string) $meta_value );
				break;
			case 'not_equals':
				$visibility_check[] = ( (string) $meta !== (string) $meta_value );
				break;
			case 'contains':
				$visibility_check[] = ( strpos( (string) $meta, $meta_value ) !== false );
				break;
			case 'greater_than':
				$visibility_check[] = ( floatval( $meta ) > floatval( $meta_value ) );
				break;
			case 'less_than':
				$visibility_check[] = ( floatval( $meta ) < floatval( $meta_value ) );
				break;
		}

		// true = visible: all checks must pass
		$matches = ( ! empty( $visibility_check ) ) ? ! in_array( false, $visibility_check, true ) : true;
		return $matches;
	}
}
