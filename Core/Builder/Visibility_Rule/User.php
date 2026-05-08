<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the user rule
 */
class User extends Rule {
	/**
	 * Returns the type of the rule
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'user';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'User', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = '<% if (restriction_type == "role") { %>
			Visible to users whose role is<%= (roles.length > 1) ? " either" : "" %>: <%= roles.join(" or ") %>
		<% } else { %>
			Visible to <%= status == "logged_in" ? "logged in" : "logged out" %> users
		<% } %>';
		
		return $template;
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
        $roles = array();
        if( isset( $GLOBALS[ 'wp_roles' ] ) ) foreach( $GLOBALS[ 'wp_roles' ]->roles as $slug => $role ) {
			$roles[ $slug ] = translate_user_role( $role[ 'name' ] );
		}

		$roles['visitor'] = __('Visitor','mv23theme');

		$fields = array();

		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Restriction type', 'mv23theme' ) )
			->add_options(array(
				'status' => __( 'Show the element based on user status', 'mv23theme' ),
			    'role'     => __( 'Show the element based on a particular role', 'mv23theme' ),
			))
			->set_default_value( 'status' );

		$fields[] = Field::create( 'multiselect', 'roles', __( 'Roles', 'mv23theme' ) )
			->required()
            ->set_input_type( 'checkbox' )
            ->add_options( $roles )
            ->set_description( __( 'Select the roles, which should have access to this element or leave blank for all roles.', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'role' );

		$fields[] = Field::create( 'select', 'status', __( 'User status', 'mv23theme' ) )
			->required()
			->add_options( array(
				'logged_in' => __( 'Logged in', 'mv23theme' ),
				'logged_out' => __( 'Logged out', 'mv23theme' ),
			) )
			->set_description( __( 'Select the user status, which should have access to this element.', 'mv23theme' ) )
			->set_default_value( 'logged_in' )
			->add_dependency( 'restriction_type', 'status' );

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

		if( $rule_data['restriction_type'] === 'role' ){
			$roles = $rule_data['roles'];

			if( is_user_logged_in() ){
				global $current_user;
				$user_roles = $current_user->roles;
				$user_role = array_shift($user_roles);
			} else {
				$user_role = 'visitor';
			}

			$visibility_check[] = in_array( $user_role, $roles );
		}

		if( $rule_data['restriction_type'] === 'status' ){
			$status = $rule_data['status'];
			$is_logged_in = is_user_logged_in();

			if( $status === 'logged_in' ){
				$visibility_check[] = $is_logged_in;
			} elseif( $status === 'logged_out' ){
				$visibility_check[] = !$is_logged_in;
			}
		}

		// true = visible: all checks must pass
		$matches = ( !empty($visibility_check) ) ? !in_array(false, $visibility_check, true) : true;
		return $matches;
	}
}
