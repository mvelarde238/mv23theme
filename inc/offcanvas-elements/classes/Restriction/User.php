<?php
namespace Offcanvas_Elements\Restriction;

use Offcanvas_Elements\Restriction;
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
		return __( 'User', 'default' );
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

		$fields = array(
			Field::create( 'text', '_title' )
		);

		$fields[] = Field::create( 'multiselect', 'roles', __( 'Roles', 'default' ) )
            ->set_input_type( 'checkbox' )
            ->add_options( $roles )
            ->set_description( __( 'Select the roles, which should have access to this element or leave blank for all roles.', 'default' ) );

		return $fields;
	}
}
