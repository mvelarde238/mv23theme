<?php
namespace Core\Builder;

use Ultimate_Fields\Container\Repeater_Group;
use Core\Builder\Core;
use Core\Builder\Common_Settings;
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
		$component->set_title( $name )
			->add_fields( static::get_fields() )
			->set_description_position( 'label' );

		$title_template = static::get_title_template();
		if( $title_template ) $component->set_title_template( $title_template );
		
		if( $args['add_common_settings'] ){
			$common_settings = static::get_common_settings();
			if( $common_settings ) self::add_common_settings( $component, $common_settings );
		}

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
	 * Returns the common settings fields slugs
	 *
	 * @return array
	 */
	/* abstract */ public static function get_common_settings() {
		return array( 'all' );
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
	protected static function add_common_settings( $component, $args = array() ) {

		foreach ($args as $setting_name) {
			if( $setting_name == 'all' ){
				$component->add_fields(array(
					Field::create( 'tab', __('Settings','mv23theme') ),
					Field::create( 'common_settings_control', 'settings' )
						->set_container( 'common_settings_container' )
						->set_width(30),
					Field::create( 'common_settings_control', 'scroll_animations_settings' )
						->set_container( 'scroll_animations_container' )
						->set_add_text( __('Add Scroll Animations', 'mv23theme') )
						->set_width(30),
					Field::create( 'common_settings_control', 'actions_settings' )
						->set_container( 'actions_container' )
						->set_add_text( __('Add Actions', 'mv23theme') )
						->set_width(30)
				));
		// 		$all = array('main','video-background','margins','borders','box-shadow','animation','scroll-animations');
		// 		foreach ($all as $a) {
		// 			$component->add_fields( Common_Settings::get_fields( $a ) );
		// 		}
			} else {
				$component->add_fields( Common_Settings::get_fields( $setting_name ) );
			}
		}
		 
		return $component;
	}
}