<?php
namespace Ultimate_Fields\Common_Settings_Control;

use Ultimate_Fields\Template;

/**
 * A base class for the extension, which adds and overwrites all necessary classes.
 *
 * @since 1.0
 */
class Common_Settings_Control {
	/**
	 * Holds the path of the plugin file for the layout control in order to load assets properly.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * The version of the plugin, used for assets.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $version;

	/**
	 * Class constructor, instantiates all necessary functionality.
	 *
	 * @since 1.0
	 * @param string $plugin_file The path to the main plugin file.
	 * @param string $version     A version to be used for assets and etc.
	 */
	public function __construct( $plugin_file, $version ) {
		$this->plugin_file = $plugin_file;
		$this->version     = $version;

		Template::instance()->add_path( dirname( $plugin_file ) . '/templates/' );

		add_filter( 'uf.field.class', array( $this, 'generate_field_class' ), 10, 2 );
		add_action( 'uf.register_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Allows the class that should be used for a field to be generated.
	 *
	 * @since 1.0
	 *
	 * @param string $class_name The class name that would be used for the field.
	 * @param string $type       The expected field type (ex. `text`).
	 * @return string
	 */
	public function generate_field_class( $class_name, $type ) {
		if( 'common_settings_control' === strtolower( $type ) ) {
			return Field::class;
		} else {
			return $class_name;
		}
	}

	/**
	 * Registers the necessary assets.
	 *
	 * @since 1.0
	 */
	public function register_scripts() {
		// $assets = plugins_url( 'assets/', $this->plugin_file );
        $assets = THEME_CUSTOM_FIELDS_PATH . '/common-settings-control/assets/';
		$v      = $this->version;
		
		// CONTAINERS JSON
		wp_register_script( 'common_settings_container', $assets . 'containers/common_settings_container.js', array( 'uf-field' ), $v );
		wp_register_script( 'scroll_animations_container', $assets . 'containers/scroll_animations_container.js', array( 'uf-field' ), $v );
		wp_register_script( 'actions_container', $assets . 'containers/actions_container.js', array( 'uf-field' ), $v );
		wp_register_script( 'blocks_layout_settings_container', $assets . 'containers/blocks_layout_settings_container.js', array( 'uf-field' ), $v );
		wp_register_script( 'blocks_layout_container', $assets . 'containers/blocks_layout_container.js', array( 'uf-field' ), $v );
		
		// FIELD SCRIPT
		wp_register_script( 'uf-field-common-settings-control', $assets . 'field-common-settings-control.js', array( 'uf-field' ), $v );

		// wp_register_style( 'uf-field-common-settings-control', $assets . 'common-settings-control.css', array( 'ultimate-fields-css' ), $v );
	}
}
