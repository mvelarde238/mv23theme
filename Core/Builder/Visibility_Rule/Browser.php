<?php
namespace Core\Offcanvas_Elements\Restriction;

use Offcanvas_Elements\Restriction;
use Ultimate_Fields\Field;

/**
 * Handles the page restriction
 */
class Browser extends Restriction {
	/**
	 * Returns the type of the restriction
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'browser';
	}

	/**
	 * Returns the name of the restriction.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Browser', 'mv23theme' );
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

		// $fields[] = Field::create( 'radio', 'restriction_type', __( 'Browser type', 'mv23theme' ) )
        //     ->add_options(array(
        //         'device' => __( 'Show the element based on device type', 'mv23theme' ),
        //         // 'rules'     => __( 'Show the element based on rules', 'mv23theme' )
        //     ));

        // $fields[] = Field::create( 'multiselect', 'device', __( 'Browser', 'mv23theme' ) )
        //     ->required()
        //     ->add_options( array(
        //         'desktop' => __('Desktop','mv23theme'),
        //         'mobile' => __('Mobile','mv23theme')
        //     ))
        //     ->set_input_type( 'checkbox' )
        //     ->set_description( __( 'The element will be displayed on all of the checked devices.', 'mv23theme' ) )
        //     ->add_dependency( 'restriction_type', 'device' );

		return $fields;
	}
}
