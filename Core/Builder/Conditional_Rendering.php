<?php
namespace Core\Builder;

use Ultimate_Fields\Field;

/**
 * Handles visibility rules repeater and its groups.
 */
class Conditional_Rendering {
	/**
	 * Holds the repeater field instance.
	 * @var Field
	 */
	protected $repeater_field;

	/**
	 * Creates an instance of the class.
	 *
	 * @return Conditional_Rendering
	 */
	public static function instance() {
		static $instance;

		if( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}

	/**
	 * Adds the neccessary hooks.
	 */
	protected function __construct() {
        $visibility_rules_repeater = Field::create( 'repeater', 'rules' )
			->set_chooser_type( 'tags' )
			->set_add_text( __('Add rule','mv23theme') );

		# Generate all groups 
		$rules_classes = $this->get_visibility_rules_classes();
		foreach( $rules_classes as $class_name ) {
			$group = $class_name::settings();
            $group->set_layout('table');
            $group->set_description_position('label');
			$visibility_rules_repeater->add_group( $group );
		}

        $this->repeater_field = $visibility_rules_repeater;
	}

	private function get_visibility_rules_classes(){
		return array(
			\Core\Builder\Visibility_Rule\Page::class,
			\Core\Builder\Visibility_Rule\User::class,
			\Core\Builder\Visibility_Rule\Device::class,
			\Core\Builder\Visibility_Rule\Plugin::class,
			\Core\Builder\Visibility_Rule\Post_Meta::class,
			\Core\Builder\Visibility_Rule\Post_Condition::class,
			// \Core\Builder\Visibility_Rule\Browser::class,
		);
	}

    public static function get_repeater_field() {
        return self::instance()->repeater_field;
    }

	public function check_the_visibility_rules( $visibility_rules = array() ){
        $is_restricted = false;

        $all_restrictions = array();

        if( is_array($visibility_rules) && count($visibility_rules) > 0 ){

            $restrictions_classes_map = array();
            $rules_classes = $this->get_visibility_rules_classes();
		    foreach( $rules_classes as $class_name ) {
                $type = $class_name::get_type();
			    $restrictions_classes_map[$type] = $class_name;
                $all_restrictions[$type] = array();
		    }

            foreach ($visibility_rules as $rule) {
                $type = $rule['__type'];
                $all_restrictions[$type][] = array(
                    'type' => $type,
                    'is_restricted' => $restrictions_classes_map[$type]::check_rules( $rule )
                );
            }
        } else {
            // there are no restrictions
            $all_restrictions['none'] = array( array( 'type' => 'none', 'is_restricted' => false ) );
        }

        $restrictions_check_in = array();
        foreach ($all_restrictions as $restrictions_by_type) {
            if( !empty($restrictions_by_type) ){
                $is_restricted_in_type = !in_array( false, array_column( $restrictions_by_type, 'is_restricted' ), true );
                $restrictions_check_in[] = $is_restricted_in_type;
            }
        }

        $is_restricted = in_array( true, $restrictions_check_in, true );

        return $is_restricted;
    }
}
