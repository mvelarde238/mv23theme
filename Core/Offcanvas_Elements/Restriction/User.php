<?php
namespace Core\Offcanvas_Elements\Restriction;

use Core\Offcanvas_Elements\Restriction;
use Ultimate_Fields\Field;

/**
 * Handles the page restriction
 */
class User extends Restriction {
	/**
	 * Returns the type of the restriction
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'user';
	}

	/**
	 * Returns the name of the restriction.
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
		$template = 'Visible to users whose role is<%= (roles.length > 1) ? " either" : "" %>: <%= roles.join(" or ") %>';
		
		return $template;
	}

	/**
	 * Returns the fields for the restriction.
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

		$fields[] = Field::create( 'multiselect', 'roles', __( 'Roles', 'mv23theme' ) )
			->required()
            ->set_input_type( 'checkbox' )
            ->add_options( $roles )
            ->set_description( __( 'Select the roles, which should have access to this element or leave blank for all roles.', 'mv23theme' ) );

		return $fields;
	}

	/**
	 * Returns the result of restrictions checking.
	 *
	 * @return bool
	 */
	public static function check_restrictions( $restriction_data ) {
		$roles = $restriction_data['roles'];

		if( is_user_logged_in() ){
			global $current_user;
			$user_roles = $current_user->roles;
			$user_role = array_shift($user_roles);
		} else {
			$user_role = 'visitor';
		}

        $restrictions_check_in[] = !in_array( $user_role, $roles );

		// if all items in $restrictions_check_in are true [true, true, ...] is restricted
        $is_restricted = ( !empty($restrictions_check_in) ) ? !in_array(false, $restrictions_check_in, true) : false;
		return $is_restricted;
	}
}
