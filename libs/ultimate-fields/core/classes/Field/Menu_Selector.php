<?php
namespace Ultimate_Fields\Field;

use Ultimate_Fields\Template;

/**
 * Handles Wp nav items based on objects
 *
 * @since 3.0
 */
class Menu_Selector extends WP_Object {

	/**
	 * Enqueues the scripts for the field.
	 *
	 * @since 3.0
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();

		wp_enqueue_script( 'uf-field-menu-selector' );

		Template::add( 'menu-selector', 'field/menu-selector');
	}

	/**
	 * Adds additional data for JavaScript.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export_field() {
		$data = parent::export_field();
		return $data;
	}

	/**
	 * Formats the data, which will be exported for the field.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export_data() {
		$value = $this->get_value( $this->name );

		return array(
			$this->name => $value
		);
	}
}
