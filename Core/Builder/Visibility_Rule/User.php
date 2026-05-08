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
			    'role'     => __( 'Show the element based on a particular role', 'mv23theme' ),
				'status' => __( 'Show the element based on user status', 'mv23theme' )
			));

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
			->add_dependency( 'restriction_type', 'status' );

		return $fields;
	}

	/**
	 * Returns the result of rule checking.
	 *
	 * @return bool
	 */
	public static function check_rules( $rule_data ) {
		$restrictions_check_in = array();

		if( $rule_data['restriction_type'] === 'role' ){
			$roles = $rule_data['roles'];

			if( is_user_logged_in() ){
				global $current_user;
				$user_roles = $current_user->roles;
				$user_role = array_shift($user_roles);
			} else {
				$user_role = 'visitor';
			}

			$restrictions_check_in[] = !in_array( $user_role, $roles );
		}

		if( $rule_data['restriction_type'] === 'status' ){
			$status = $rule_data['status'];
			$is_logged_in = is_user_logged_in();

			if( $status === 'logged_in' ){
				$restrictions_check_in[] = !$is_logged_in;
			} elseif( $status === 'logged_out' ){
				$restrictions_check_in[] = $is_logged_in;
			}
		}

		// if all items in $restrictions_check_in are true [true, true, ...] is restricted
        $is_restricted = ( !empty($restrictions_check_in) ) ? !in_array(false, $restrictions_check_in, true) : false;
		return $is_restricted;
	}
}
