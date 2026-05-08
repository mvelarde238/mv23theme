<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the device rule
 */
class Device extends Rule {
	/**
	 * Returns the type of the rule
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'device';
	}

	/**
	 * Returns the name of the rule.
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
	 * Returns the fields for the rule.
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
	 * Evaluates whether the rule's conditions are met for the current context.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool True if conditions are met (element visible), false otherwise.
	 */
	public static function matches( $rule_data ) {
		$visibility_check = array();

		if( $rule_data['restriction_type'] === 'devices' ){
			$devices = $rule_data['devices'];
			$current_device = ( wp_is_mobile() ) ? 'mobile' : 'desktop';
			$visibility_check[] = in_array( $current_device, $devices );
		}

		// true = visible: all checks must pass
		$matches = ( !empty($visibility_check) ) ? !in_array(false, $visibility_check, true) : true;
		return $matches;
	}
}
