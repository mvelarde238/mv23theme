<?php
namespace Core\Builder;

use Ultimate_Fields\Container\Repeater_Group;
use Core\Builder\Core;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

/**
 * Handles component definitions.
 */
abstract class Component {
    public function __construct( $slug, $name, $args = array() ) {
		$defaults = array(
			'add_common_settings' => true
		);
		$args = wp_parse_args( $args, $defaults );

		$component = new Repeater_Group( $slug );
		$component->set_title( $name );
		if( $args['add_common_settings'] ){
			self::add_common_settings( $component );
		}
		$component->add_fields( static::get_fields() );
		$component->set_description_position( 'label' );

		$title_template = static::get_title_template();
		if( $title_template ) $component->set_title_template( $title_template );

		$view_template = static::get_view_template();
		if( $view_template ) $component->set_view_template( $view_template );

		$layout = static::get_layout();
		if( !empty($layout) ) $component->set_layout( $layout );

		$icon = static::get_icon();
		if( !empty($icon) ) $component->set_icon( $icon );

		$edit_mode = static::get_edit_mode();
		if( !empty($edit_mode) ) $component->set_edit_mode( $edit_mode );

		Core::getInstance()->register_component( $component, get_called_class() );
		Template_Engine::getInstance()->register_component( $component->get_id(), get_called_class() );

		return $component;
    }

	/**
	 * Returns the fields for the component.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	abstract public static function get_fields();

	/**
	 * Returns the title template for the group
	 *
	 * @return mixed bool if not implemented || backbone template
	 */
	/* abstract */ public static function get_title_template() {
		return false;
	}

	/**
	 * Returns the view template for the group
	 *
	 * @return mixed bool if not implemented || backbone template
	 */
	/* abstract */ public static function get_view_template() {
		return false;
	}

	/**
	 * Returns the layout of the component.
	 *
	 * @return string
	 */
	/* abstract */ public static function get_layout() {
		return 'table';
	}

	/**
	 * Returns the icon of the component.
	 *
	 * @return string
	 */
	/* abstract */ public static function get_icon() {
		return '';
	}

	/**
	 * Returns the edit mode of the component.
	 *
	 * @return string
	 */
	/* abstract */ public static function get_edit_mode() {
		return 'popup';
	}

	/**
	 * Returns the component's html output
	 *
	 * @return string html
	 */
	/* abstract */ public static function display( $args ) {
		return '<div class="component">'.$args['__type'].'</div>';
	}

	/**
	 * Adds common setting fields to component
	 *
	 * @return Ultimate_Fields\Container\Repeater_Group instance
	 */
	protected static function add_common_settings( $component ) {
		$common_settings_control = Field::create( 'common_settings_control', 'settings' )
			->set_container( 'common_settings_container' )
			->set_add_text( __('Settings', 'mv23theme') )
			// ->set_icon( 'dashicons-admin-appearance' )
			->hide_label();

		$components_that_use_layout = array('components_wrapper', 'section');
		if( !in_array($component->get_id(), $components_that_use_layout) ){
			$common_settings_control->set_hidden_fields( array('layout') );
		}

		$component->add_fields(array(
			Field::create( 'complex', 'common_settings_wrapper' )
				->merge()->hide_label()
				->set_attr( 'class', 'components-settings-complex-styles' )
				->add_fields(array(
					$common_settings_control,
					Field::create( 'common_settings_control', 'scroll_animations_settings' )
						->set_container( 'scroll_animations_container' )
						->set_add_text( __('Scroll Animations', 'mv23theme') )
						->hide_label(),
					Field::create( 'common_settings_control', 'actions_settings' )
					->set_container( 'actions_container' )
						->set_add_text( __('Actions', 'mv23theme') )
						->hide_label(),
				))
		));
		 
		return $component;
	}
}