<?php
namespace Ultimate_Fields\Common_Settings_Control;

use Ultimate_Fields\Field as Base_Field;
use Ultimate_Fields\Template;
use Ultimate_Fields\Container;
use Ultimate_Fields\Common_Settings_Control\Common_Settings_Control;

/**
 * Handles the display of the field, which will handle the common settings.
 *
 * @since 1.0
 */
class Field extends Base_Field {
	/**
	 * Holds the container name, which will be used to load the fields file.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $container;

	/**
	 * Holds the text, which will be used for the "Save Settings" button.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $save_text;

    /**
	 * Holds the text, which will be used for the "Add Settings" button.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $add_text;

	/**
	 * Holds the icon, which will be used for the button.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $icon;

	/**
	 * Enqueues all scripts and templates, needed for the field.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'uf-flexdatalist' );
		wp_enqueue_style( 'uf-flexdatalist-css' );
		wp_enqueue_script( $this->container );
		wp_enqueue_script( 'uf-field-common-settings-control' );
		// wp_enqueue_style( 'uf-field-common-settings-control' );

		Template::add( 'common-settings-control', 'field/common-settings-control' );
	}

	/**
	 * Exports the settings of the field for usage in JavaScript.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function export_field() {
		$data = parent::export_field();

		$save_text = $this->save_text
			? $this->save_text
			: __( 'Save Settings', 'mv23theme' );

        $add_text = $this->add_text
			? $this->add_text
			: __( 'Add Settings', 'mv23theme' );

		$data[ 'container' ]      = $this->container;
		$data[ 'type' ]           = 'Common_Settings_Control';
		$data[ 'nonce' ]          = wp_create_nonce( $this->get_nonce_action() );
		$data[ 'save_text' ]      = $save_text;
		$data[ 'add_text' ]       = $add_text;
		$data[ 'icon' ]           = $this->get_icon();

		return $data;
	}

	/**
	 * Imports the field.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $data The data for the field.
	 */
	public function import( $data ) {
		parent::import( $data );

		$this->proxy_data_to_setters( $data, array(
			'common_settings_control_save_text'        => 'set_save_text',
			'common_settings_control_add_text'        => 'set_add_text'
		));
	}

	/**
	 * Generates the data for file exports.
	 *
	 * @since 1.0
	 *
	 * @return mixed[]
	 */
	public function export() {
		$settings = parent::export();

		$this->export_properties( $settings, array(
			'save_text'        => array( 'common_settings_control_save_text', null ),
			'add_text'        => array( 'common_settings_control_add_text', null )
		));

		return $settings;
	}

	/**
	 * Returns the internal name of the field type.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'Common_Settings_Control';
	}

	/**
	 * Returns the action for a nonce field.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	protected function get_nonce_action() {
		return 'uf_common_settings_control_' . $this->name;
	}

	/**
	 * String setters and getters.
	 */

	public function set_container( $container_name ) {
		$this->container = $container_name;
		return $this;
	}

	public function get_container() {
		return $this->container;
	}

	public function set_save_text( $text ) {
		$this->save_text = $text;
		return $this;
	}

	public function get_save_text() {
		return $this->save_text;
	}

    public function set_add_text( $text ) {
		$this->add_text = $text;
		return $this;
	}

	public function get_add_text() {
		return $this->add_text;
	}

	public function set_icon( $icon ) {
		$this->icon = $icon;
		return $this;
	}

	public function get_icon() {
		return $this->icon ?? 'dashicons-migrate';
	}
}
