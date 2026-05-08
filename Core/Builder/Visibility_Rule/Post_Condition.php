<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the post condition rule.
 *
 * Evaluates native WordPress conditional functions (e.g. has_post_thumbnail,
 * is_front_page) against the current post or query context.
 */
class Post_Condition extends Rule {
	/**
	 * Returns the type of the rule.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'post_condition';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Post Condition', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group.
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = 'Visible when <%= condition %> is <%= operator == "is_true" ? "true" : "false" %>';

		return $template;
	}

	/**
	 * Returns the default conditions map.
	 *
	 * Extensible via the `filter_post_condition_rules` filter.
	 *
	 * @return array<string, string> slug => label
	 */
	public static function get_conditions() {
		$conditions = array(
			// Post state
			'has_featured_image'  => __( 'Has featured image', 'mv23theme' ),
			'is_sticky'           => __( 'Is sticky', 'mv23theme' ),
			'has_excerpt'         => __( 'Has excerpt', 'mv23theme' ),
			'comments_open'       => __( 'Comments are open', 'mv23theme' ),
			'has_comments'        => __( 'Has comments', 'mv23theme' ),
			'has_next_post'       => __( 'Has next post', 'mv23theme' ),
			'has_previous_post'   => __( 'Has previous post', 'mv23theme' ),
			'password_required'   => __( 'Password required', 'mv23theme' ),
			// Page/query context
			'is_front_page'       => __( 'Is front page', 'mv23theme' ),
			'is_home'             => __( 'Is blog home', 'mv23theme' ),
			'is_search'           => __( 'Is search results page', 'mv23theme' ),
			'is_404'              => __( 'Is 404 page', 'mv23theme' ),
			'is_paged'            => __( 'Is paginated (page 2+)', 'mv23theme' ),
		);

		/**
		 * Allows adding custom conditions to the Post Condition visibility rule.
		 *
		 * Each entry must be a slug => label pair. The slug must match a key handled
		 * inside a `filter_post_condition_check_{slug}` filter or it will be ignored
		 * during check_rules() evaluation.
		 *
		 * Example:
		 *   add_filter( 'filter_post_condition_rules', function( $conditions ) {
		 *       $conditions['my_custom_condition'] = __( 'My custom condition', 'textdomain' );
		 *       return $conditions;
		 *   });
		 *
		 *   add_filter( 'filter_post_condition_check_my_custom_condition', function( $result, $post_id ) {
		 *       return (bool) get_post_meta( $post_id, '_my_flag', true );
		 *   }, 10, 2 );
		 *
		 * @param array<string, string> $conditions Slug => label pairs.
		 * @return array<string, string>
		 */
		return apply_filters( 'filter_post_condition_rules', $conditions );
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'select', 'condition', __( 'Condition', 'mv23theme' ) )
			->required()
			->set_input_type( 'radio' )
			->add_options( static::get_conditions() )
			->set_description( __( 'Note: conditions related to post navigation (Has next/previous post) only work reliably on singular post views.', 'mv23theme' ) );

		$fields[] = Field::create( 'select', 'operator', __( 'Expected result', 'mv23theme' ) )
			->required()
			->set_input_type( 'radio' )
			->add_options( array(
				'is_true'  => __( 'Is true', 'mv23theme' ),
				'is_false' => __( 'Is false', 'mv23theme' ),
			) );

		return $fields;
	}

	/**
	 * Evaluates a built-in condition against the current post/query context.
	 *
	 * @param string $condition The condition slug.
	 * @param int    $post_id   The current post ID.
	 * @return bool|null Returns null if the condition slug is unknown.
	 */
	protected static function evaluate_condition( $condition, $post_id ) {
		switch ( $condition ) {
			case 'has_featured_image':
				return has_post_thumbnail( $post_id );
			case 'is_sticky':
				return is_sticky( $post_id );
			case 'has_excerpt':
				return has_excerpt( $post_id );
			case 'comments_open':
				return comments_open( $post_id );
			case 'has_comments':
				return get_comments_number( $post_id ) > 0;
			case 'has_next_post':
				return (bool) get_next_post();
			case 'has_previous_post':
				return (bool) get_previous_post();
			case 'password_required':
				return post_password_required( $post_id );
			case 'is_front_page':
				return is_front_page();
			case 'is_home':
				return is_home();
			case 'is_search':
				return is_search();
			case 'is_404':
				return is_404();
			case 'is_paged':
				return is_paged();
			default:
				/**
				 * Allows evaluating custom conditions added via `filter_post_condition_rules`.
				 *
				 * Return a boolean from this filter to make the condition evaluable.
				 * If no filter is hooked for this slug, the condition is skipped (returns null).
				 *
				 * @param null   $result  Default null (unknown condition).
				 * @param int    $post_id The current post ID.
				 * @return bool|null
				 */
				return apply_filters( "filter_post_condition_check_{$condition}", null, $post_id );
		}
	}

	/**
	 * Returns the result of rule checking.
	 *
	 * @param array $rule_data
	 * @return bool
	 */
	public static function check_rules( $rule_data ) {
		$restrictions_check_in = array();

		$condition = isset( $rule_data['condition'] ) ? $rule_data['condition'] : '';
		$operator  = isset( $rule_data['operator'] ) ? $rule_data['operator'] : 'is_true';

		if ( empty( $condition ) ) {
			return false;
		}

		$post_id = get_the_ID();
		$result  = static::evaluate_condition( $condition, $post_id );

		// Unknown condition (no filter hooked for custom slug) — don't restrict.
		if ( is_null( $result ) ) {
			return false;
		}

		if ( $operator === 'is_true' ) {
			$restrictions_check_in[] = ! $result;
		} else {
			$restrictions_check_in[] = $result;
		}

		// if all items in $restrictions_check_in are true [true, true, ...] is restricted
		$is_restricted = ( ! empty( $restrictions_check_in ) ) ? ! in_array( false, $restrictions_check_in, true ) : false;
		return $is_restricted;
	}
}
