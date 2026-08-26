<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the language rule (Polylang).
 */
class Language extends Rule {
	/**
	 * Returns the type of the rule
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'language';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Language', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = 'Visible on the following <%= (languages.length > 1) ? "languages" : "language" %>: <%= languages.join(" and ") %>';

		return $template;
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$language_options = array();
		if ( IS_MULTILANGUAGE ) {
			$langs = pll_the_languages( array( 'raw' => 1 ) );
			if ( ! empty( $langs ) ) {
				foreach ( $langs as $lang ) {
					$language_options[ $lang['slug'] ] = $lang['name'];
				}
			}
		}

		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Language type', 'mv23theme' ) )
			->add_options( array(
				'languages' => __( 'Show the element based on the current language', 'mv23theme' ),
			) );

		$fields[] = Field::create( 'multiselect', 'languages', __( 'Languages', 'mv23theme' ) )
			->required()
			->add_options( $language_options )
			->set_input_type( 'checkbox' )
			->set_description( __( 'The element will be displayed when the current language matches one of the checked languages.', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'languages' );

		return $fields;
	}

	/**
	 * Evaluates whether the rule's conditions are met for the current context.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool True if conditions are met (element visible), false otherwise.
	 */
	public static function matches( $rule_data ) {
		$visibility_check = array();

		if ( $rule_data['restriction_type'] === 'languages' ) {
			$languages       = $rule_data['languages'];
			$current_language = ( IS_MULTILANGUAGE && function_exists( 'pll_current_language' ) ) ? pll_current_language() : 'es';
			$visibility_check[] = in_array( $current_language, $languages, true );
		}

		// true = visible: all checks must pass
		$matches = ( ! empty( $visibility_check ) ) ? ! in_array( false, $visibility_check, true ) : true;
		return $matches;
	}
}
