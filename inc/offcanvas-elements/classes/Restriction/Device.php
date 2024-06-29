<?php
namespace Offcanvas_Elements\Restriction;

use Offcanvas_Elements\Restriction;
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
		return __( 'Device', 'default' );
	}

	/**
	 * Returns the fields for the restriction.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array(
			Field::create( 'text', '_title' )
		);

		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Browser type', 'default' ) )
            ->add_options(array(
                'device' => __( 'Show the element based on device type', 'default' ),
                // 'rules'     => __( 'Show the element based on rules', 'default' )
            ));

        $fields[] = Field::create( 'multiselect', 'device', __( 'Device', 'default' ) )
            ->required()
            ->add_options( array(
                'desktop' => __('Desktop','default'),
                'mobile' => __('Mobile','default')
            ))
            ->set_input_type( 'checkbox' )
            ->set_description( __( 'The element will be displayed on all of the checked devices.', 'default' ) )
            ->add_dependency( 'restriction_type', 'device' );

		return $fields;
	}
}
