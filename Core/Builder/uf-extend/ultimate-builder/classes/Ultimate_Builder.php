<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Ultimate_Fields\Template;

/**
 * A base class for the extension, which adds and overwrites all necessary classes.
 *
 * @since 1.0
 */
class Ultimate_Builder {
	/**
	 * Holds the path of the plugin file in order to load assets properly.
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
		if( 'ultimate_builder' === strtolower( $type ) ) {
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
        $assets = BUILDER_PATH . '/uf-extend/ultimate-builder/assets/';
		$v      = $this->version;
		
		// GRAPES JS - Registrar sin dependencias de WordPress para evitar conflictos
		wp_register_script( 'grapes-js', $assets . 'grapes.min.js', array(), $v );
		wp_register_style( 'grapes-js-styles', 'https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.22.12/css/grapes.min.css', array(), $v );

		// Script para resolver conflictos con Backbone de WordPress
		wp_register_script( 'grapes-js-noconflict', $assets . 'grapes-noconflict.js', array( 'grapes-js', 'backbone', 'underscore' ), $v );

		// FIELD SCRIPT - ahora dependen del noconflict
		wp_register_script( 'gjs-row-and-cols', $assets . 'gjs-row-and-cols.js', array( 'uf-field', 'grapes-js-noconflict' ), $v );
		wp_register_script( 'builder', $assets . 'builder.js', array( 'uf-field','gjs-row-and-cols' ), $v );
		wp_register_script( 'uf-field-ultimate-builder', $assets . 'field-ultimate-builder.js', array( 'builder' ), $v );
		wp_register_style( 'uf-field-ultimate-builder', $assets . 'ultimate-builder.css', array( 'ultimate-fields-css' ), $v );
	}
}
