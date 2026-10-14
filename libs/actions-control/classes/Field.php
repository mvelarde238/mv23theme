<?php
namespace Ultimate_Fields\Actions_Control;

use Ultimate_Fields\Field as Base_Field;
use Ultimate_Fields\Template;

/**
 * Handles the display of the field, which will handle the common settings.
 *
 * @since 1.0
 */
class Field extends Base_Field {

	protected $actions = array();

	/**
	 * Enqueues all scripts and templates, needed for the field.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'uf-field-actions-control' );
		wp_enqueue_script( 'uf-field-actions-control' );

		Template::add( 'actions-control', 'field/actions-control' );
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

		$data[ 'type' ] = 'Actions_Control';
		$data[ 'actions' ] = $this->actions;

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

		$this->proxy_data_to_setters( $data, array());
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

		$this->export_properties( $settings, array());

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
		return 'Actions_Control';
	}

	public function get_actions() {
		return $this->actions;
	}

	public function set_actions( $actions ) {
		if( ! is_array( $actions ) ) return;

		$this->actions = $actions;
		return $this;
	}

	/**
	 * Ensures that unlike normal fields, no values are saved for actions control.
	 * @param mixed[] $source The source which the value of the field would be available in.
	 */
	public function save( $source ) {
		// Nothing to do here really...
	}
}
