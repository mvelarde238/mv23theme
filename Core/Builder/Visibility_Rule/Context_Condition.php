<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

/**
 * Visibility rule that evaluates a Handlebars context expression.
 *
 * The user supplies a single expression string that is evaluated against the
 * Handlebars context (the same context available in templates).
 *
 * Supported expression forms:
 *   - Truthy check:       post.meta.my_field
 *   - Post type check:    post.posttype == 'post'
 *   - Numeric compare:    post.meta.price > 100
 *   - Meta exists:        post.meta.post_views_count
 *   - Taxonomy term:      post.taxonomies.category
 *   - Site data:          site.title == 'My Site'
 *   - Any operator:       post.meta.stock != 0
 *
 * Operators supported: >, >=, <, <=, ==, !=
 * Right-hand side may be a quoted string literal, a numeric literal,
 * or another dot-notation path.
 */
class Context_Condition extends Rule {
	/**
	 * Returns the type of the rule.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'context_condition';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Context Expression', 'mv23theme' );
	}

	/**
	 * Returns the Backbone title template for the repeater group row.
	 *
	 * @return string
	 */
	public static function get_title_template() {
		return "Visible if: '<%= expression %>'";
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'text', 'expression', __( 'Expression', 'mv23theme' ) )
			->required()
			->set_description(
				__( "Handlebars context expression. Examples:\n• post.posttype == 'post'\n• post.meta.post_views_count > 23\n• post.meta.my_field  (truthy check)\n• post.main_terms  (has any terms)\n• site.url == 'https://example.com'", 'mv23theme' )
			);

		return $fields;
	}

	/**
	 * Evaluates whether the rule's conditions are met for the current context.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool True if the expression evaluates to true (element visible).
	 */
	public static function matches( $rule_data ) {
		$expression = isset( $rule_data['expression'] ) ? trim( $rule_data['expression'] ) : '';

		if ( empty( $expression ) ) {
			return true;
		}

		return Handlebars::evaluate_expression( $expression );
	}
}
