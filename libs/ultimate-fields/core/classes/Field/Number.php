<?php
namespace Ultimate_Fields\Field;

use Ultimate_Fields\Field;

/**
 * Allows the input of numbers (eventually with an UI slider.)
 *
 * @since 3.0
 */
class Number extends Field {
	/**
	 * Holds the minimum of the input.
	 *
	 * @since 3.0
	 * @var boolean|numberic.
	 */
	protected $minimum = false;

	/**
	 * Holds the maximum of the input.
	 *
	 * @since 3.0
	 * @var boolean|numberic.
	 */
	protected $maximum = false;

	/**
	 * Holds the step of the slider, if enabled.
	 *
	 * @since 3.0
	 * @var numberic.
	 */
	protected $step = 1;

	/**
	 * Controls if the fields hould be displayed through a slider or not.
	 *
	 * @since 3.0
	 * @var boolean
	 */
	protected $slider_enabled = false;

	/**
	 * The prefix is a value, which gets displayed before the field.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * The suffix is a value, which gets displayed after the field.
	 *
	 * @var string
	 */
	protected $suffix;

	/**
	 * This is the value, which would be displayed as a placeholder within the field.
	 *
	 * @var string
	 */
	protected $placeholder;

	/**
	 * Enqueues the script(s) that is needed for the field.
	 *
	 * @since 3.0
	 */
	public function enqueue_scripts() {
		if( $this->slider_enabled ) {
			wp_enqueue_script( 'jquery-ui-slider' );
		}

		wp_enqueue_script( 'uf-field-number' );
	}

	/**
	 * Sanitizes a value before it's saved in the database.
	 *
	 * @since 3.0
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed
	 */
	protected function sanitize_value( $value ) {
		return intval( $value );
	}

	/**
	 * Sets the minimum value for the field.
	 *
	 * @since 3.0
	 *
	 * @param float|int $minimum The minimum for the input.
	 * @return Ultimate_Fields\Field\Number THe instance of the field, useful for chaining.
	 */
	public function set_minimum( $minimum ) {
		$this->minimum = floatval( $minimum );

		return $this;
	}

	/**
	 * Retrieves the minimum value of the field.
	 *
	 * @since 3.0
	 *
	 * @return numberic.
	 */
	public function get_minimum() {
		return $this->minimum;
	}

	/**
	 * Sets the maximum value for the field.
	 *
	 * @since 3.0
	 *
	 * @param float|int $maximum The maximum for the input.
	 * @return Ultimate_Fields\Field\Number THe instance of the field, useful for chaining.
	 */
	public function set_maximum( $maximum ) {
		$this->maximum = floatval( $maximum );

		return $this;
	}

	/**
	 * Retrieves the minimum value of the field.
	 *
	 * @since 3.0
	 *
	 * @return numberic.
	 */
	public function get_maximum() {
		return $this->maximum;
	}

	/**
	 * Sets the step for the field.
	 *
	 * @since 3.0
	 *
	 * @param float|int $step The step for the input.
	 * @return Ultimate_Fields\Field\Number THe instance of the field, useful for chaining.
	 */
	public function set_step( $step ) {
		$this->step = floatval( $step );

		return $this;
	}

	/**
	 * Retrieves the step.
	 *
	 * @since 3.0
	 *
	 * @return numberic.
	 */
	public function get_step() {
		return $this->step;
	}

	/**
	 * Enables the slider functionality of the field.
	 *
	 * Doing this requires a minum and maximum value to be set,
	 * so they should be included in this method's parameters.
	 *
	 * @see http://api.jqueryui.com/slider/
	 *
	 * @param numberic $minimum The starting value of the slider.
	 * @param numberic $maximum The ending value of the slider.
	 * @param numberic $step The step of the slider.
	 * @return Ultimate_Fields\Field\Number The instance of the field.
	 */
	public function enable_slider( $minimum, $maximum, $step = 1 ) {
		$this->slider_enabled = true;

		$this->set_minimum( $minimum );
		$this->set_maximum( $maximum );
		$this->set_step( $step );

		return $this;
	}

	/**
	 * Disables the slider functionality.
	 *
	 * @since 3.0
	 *
	 * @return Ultimate_Fields\Field\Number The instance of the field.
	 */
	public function disable_slider() {
		$this->slider_enabled = false;

		return $this;
	}

	/**
	 * Sets the prefix is of the field, which gets displayed before it.
	 *
	 * @since 3.0
	 *
	 * @param string $prefix The prefix.
	 * @return Ultimate_Fields\Field\Number The field.
	 */
	public function set_prefix( $prefix  ) {
		$this->prefix = $prefix;

		return $this;
	}

	/**
	 * Returns the prefix of the field.
	 *
	 * @return string
	 */
	public function get_prefix() {
		return $this->prefix;
	}

	/**
	 * Sets the suffix is of the field, which gets displayed after it.
	 *
	 * @param string $suffix The suffix.
	 * @return Ultimate_Fields\Field\Number The field.
	 */
	public function set_suffix( $suffix  ) {
		$this->suffix = $suffix;

		return $this;
	}

	/**
	 * Returns the suffix of the field.
	 *
	 * @return string
	 */
	public function get_suffix() {
		return $this->suffix;
	}

	/**
	 * Allow a custom placeholder to be used for the fields' input.
	 *
	 * @param string $placeholder The placeholder to use.
	 * @return Ultimate_Fields\Field\Number The instance of the field.
	 */
	public function set_placeholder( $text ) {
		$this->placeholder = $text;

		return $this;
	}

	/**
	 * Adds settings for the field in JS.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export_field() {
		return array_merge( parent::export_field(), array(
			'minimum'        => $this->get_minimum(),
			'maximum'        => $this->get_maximum(),
			'step'           => $this->get_step(),
			'slider_enabled' => $this->slider_enabled,
			'prefix' => $this->prefix,
			'suffix' => $this->suffix,
			'placeholder' => $this->placeholder
		));
	}

	/**
	 * Imports the field.
	 *
	 * @since 3.0
	 *
	 * @param mixed[] $data The data for the field.
	 */
	public function import( $data ) {
		parent::import( $data );

		if(
			isset( $data[ 'number_slider' ] ) && $data[ 'number_slider' ]
			&& isset( $data[ 'number_minimum' ] ) && $data[ 'number_minimum' ]
			&& isset( $data[ 'number_maximum' ] ) && $data[ 'number_maximum' ]
		) {
			$step = isset( $data[ 'number_step' ] )
				? intval( $data[ 'number_step' ] )
				: 1;

			$this->enable_slider( $data[ 'number_minimum' ], $data[ 'number_maximum' ], $step );
		} else {
			$this->proxy_data_to_setters( $data, array(
				'number_minimum' => 'set_minimum',
				'number_maximum' => 'set_maximum',
				'number_step'    => 'set_step',
				'prefix'        => 'set_prefix',
				'suffix'        => 'set_suffix',
				'placeholder'   => 'set_placeholder'
			));
		}
	}

	/**
	 * Generates the data for file exports.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export() {
		$settings = parent::export();

		$this->export_properties( $settings, array(
			'minimum'        => array( 'number_minimum', false ),
			'maximum'        => array( 'number_maximum', false ),
			'step'           => array( 'number_step', 1 ),
			'slider_enabled' => array( 'number_slider', false ),
			'prefix'        => array( 'prefix', null ),
			'suffix'        => array( 'suffix', null ),
			'placeholder'   => array( 'placeholder', null )
		));

		return $settings;
	}

	/**
	 * Handles the value by converting it to a proper number.
	 *
	 * @since 3.0
	 *
	 * @param mixed                  $value  The value to handle.
	 * @param Ultimate_Fields\Helper\Data_Source $source The source the value is coming from.
	 * @return mixed
	 */
	public function handle( $value, $source = null ) {
		$value = parent::handle( $value, $source );

		return is_int( $value ) || ( is_string( $value ) && strlen( $value ) )
			? intval( $value )
			: null;
	}
}
