<?php
namespace Core\Offcanvas_Elements\Restriction;

use Core\Offcanvas_Elements\Restriction;
use Ultimate_Fields\Field;

/**
 * Handles the page restriction
 */
class Device extends Restriction {
	/**
	 * Returns the type of the restriction
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'device';
	}

	/**
	 * Returns the name of the restriction.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Device', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = 'Visible on the following <%= (devices.length > 1) ? "devices" : "device" %>: <%= devices.join(" and ") %>';
		
		return $template;
	}

	/**
	 * Returns the fields for the restriction.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Device type', 'mv23theme' ) )
            ->add_options(array(
                'devices' => __( 'Show the element based on devices', 'mv23theme' ),
                // 'rules'     => __( 'Show the element based on rules', 'mv23theme' )
            ));

        $fields[] = Field::create( 'multiselect', 'devices', __( 'Devices', 'mv23theme' ) )
            ->required()
            ->add_options( array(
                'desktop' => __('Desktop','mv23theme'),
                'mobile' => __('Mobile','mv23theme')
            ))
            ->set_input_type( 'checkbox' )
            ->set_description( __( 'The element will be displayed on all of the checked devices.', 'mv23theme' ) )
            ->add_dependency( 'restriction_type', 'devices' );

		return $fields;
	}

	/**
	 * Returns the result of restrictions checking.
	 *
	 * @return bool
	 */
	public static function check_restrictions( $restriction_data ) {
		if( $restriction_data['restriction_type'] === 'devices' ){

			$devices = $restriction_data['devices'];
			$current_device = ( wp_is_mobile() ) ? 'mobile' : 'desktop';
			$restrictions_check_in[] = !in_array( $current_device, $devices );
		}

		// if all items in $restrictions_check_in are true [true, true, ...] is restricted
        $is_restricted = ( !empty($restrictions_check_in) ) ? !in_array(false, $restrictions_check_in, true) : false;
		return $is_restricted;
	}
}
