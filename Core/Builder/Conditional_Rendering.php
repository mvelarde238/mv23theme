<?php
namespace Core\Builder;

use Ultimate_Fields\Field;

/**
 * TODO:
 * 
 * - Cookie rule:	Mostrar si existe cookie X con valor Y	Nueva rule Cookie — $_COOKIE
 * - Archive context rule:	is_category('noticias'), is_tag, is_tax con término específico	Extensión de rule Page o nueva Archive
 * - WooCommerce rule:	Cart no vacío, usuario compró producto X, en página de producto, stock	Nueva rule WooCommerce (activa solo si WC activo — como Plugin rule)
 * - Language rule:	Idioma actual via Polylang/WPML	Nueva rule Language
 */

/**
 * Handles visibility rules repeater and its rule groups.
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
			\Core\Builder\Visibility_Rule\Date_Time::class,
			\Core\Builder\Visibility_Rule\URL_Parameter::class,
		);
	}

    public static function get_repeater_field() {
        return self::instance()->repeater_field;
    }

	public function should_hide_element( $visibility_settings ) {
		$visibility_rules    = $visibility_settings['rules'] ?? array();
        $rule_operator       = $visibility_settings['rule_operator'] ?? 'all';

        $all_rule_results = array();

        if( is_array($visibility_rules) && count($visibility_rules) > 0 ){

            $rule_class_map = array();
            $rules_classes = $this->get_visibility_rules_classes();
		    foreach( $rules_classes as $class_name ) {
                $type = $class_name::get_type();
			    $rule_class_map[$type] = $class_name;
                $all_rule_results[$type] = array();
		    }

            foreach ($visibility_rules as $rule) {
                $type = $rule['__type'];
                $all_rule_results[$type][] = array(
                    'type'    => $type,
                    'matches' => $rule_class_map[$type]::matches( $rule )
                );
            }
        } else {
            // No rules configured — always visible
            return false;
        }

        // Collapse each type-group to a single bool using the selected operator
        $type_group_results = array();
        foreach ($all_rule_results as $type => $type_rules) {
            if( !empty($type_rules) ){
                $match_column = array_column( $type_rules, 'matches' );
                if ( $rule_operator === 'any' ) {
                    // ANY: type-group passes if at least one rule in it matches
                    $type_group_results[] = in_array( true, $match_column, true );
                } else {
                    // ALL: type-group passes only if every rule in it matches
                    $type_group_results[] = !in_array( false, $match_column, true );
                }
            }
        }

        if ( $rule_operator === 'any' ) {
            // ANY inter-type: visible if at least one type-group passes → hide only if none pass
            $should_show = in_array( true, $type_group_results, true );
        } else {
            // ALL inter-type: visible only if every type-group passes → hide if any fails
            $should_show = !in_array( false, $type_group_results, true );
        }

        return !$should_show;
    }
}
