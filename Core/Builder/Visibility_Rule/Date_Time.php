<?php
namespace Core\Builder\Visibility_Rule;

use Core\Builder\Visibility_Rule\Rule;
use Ultimate_Fields\Field;

/**
 * Handles the date/time visibility rule.
 *
 * Supports three restriction types:
 *  - date_range  : visible between two dates (inclusive)
 *  - weekdays    : visible on specific days of the week
 *  - time_range  : visible during a daily time window
 */
class Date_Time extends Rule {
	/**
	 * Returns the type of the rule.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'date_time';
	}

	/**
	 * Returns the name of the rule.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Date / Time', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group.
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = '<% if (restriction_type == "date_range") { %>
			Visible from <%= date_range_group.date_start || "\u2026" %> to <%= date_range_group.date_end || "\u2026" %>
		<% } else if (restriction_type == "weekdays") { %>
			<% var _days = {"0":"Sunday","1":"Monday","2":"Tuesday","3":"Wednesday","4":"Thursday","5":"Friday","6":"Saturday"};
			   var _named = weekdays.map(function(d){ return _days[d]; }); %>
			Visible on: <%= _named.join(", ") %>
		<% } else { %>
			Visible between <%= time_range_group.time_start || "\u2026" %> and <%= time_range_group.time_end || "\u2026" %>
		<% } %>';

		return $template;
	}

	/**
	 * Returns the fields for the rule.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Restriction type', 'mv23theme' ) )
			->add_options( array(
				'date_range' => __( 'Date range', 'mv23theme' ),
				'weekdays'   => __( 'Days of the week', 'mv23theme' ),
				'time_range' => __( 'Time window (daily)', 'mv23theme' ),
			) );

		// --- Date range ---
		$fields[] = Field::create( 'complex', 'date_range_group', __( 'Date range', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'date_range' )
			->set_description( __( 'Both dates are inclusive. Leave empty to use only one boundary.', 'mv23theme' ) )
			->add_fields( array(
				Field::create( 'date', 'date_start', __( 'From', 'mv23theme' ) )
					->set_width( 50 ),
				Field::create( 'date', 'date_end', __( 'To', 'mv23theme' ) )
					->set_width( 50 ),
			) );

		// --- Weekdays ---
		$fields[] = Field::create( 'multiselect', 'weekdays', __( 'Weekdays', 'mv23theme' ) )
			->required()
			->set_input_type( 'checkbox' )
			->add_dependency( 'restriction_type', 'weekdays' )
			->add_options( array(
				'1' => __( 'Monday', 'mv23theme' ),
				'2' => __( 'Tuesday', 'mv23theme' ),
				'3' => __( 'Wednesday', 'mv23theme' ),
				'4' => __( 'Thursday', 'mv23theme' ),
				'5' => __( 'Friday', 'mv23theme' ),
				'6' => __( 'Saturday', 'mv23theme' ),
				'0' => __( 'Sunday', 'mv23theme' ),
			) )
			->set_description( __( 'The element will be visible on the selected days.', 'mv23theme' ) );

		// --- Time range ---
		$fields[] = Field::create( 'complex', 'time_range_group', __( 'Time window', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'time_range' )
			->set_description( __( 'Uses the site\'s timezone. Leave empty to use only one boundary.', 'mv23theme' ) )
			->add_fields( array(
				Field::create( 'time', 'time_start', __( 'From', 'mv23theme' ) )
					->set_width( 50 ),
				Field::create( 'time', 'time_end', __( 'To', 'mv23theme' ) )
					->set_width( 50 ),
			) );

		return $fields;
	}

	/**
	 * Evaluates whether the rule's conditions are met for the current context.
	 *
	 * @param  array $rule_data The saved field values for this rule instance.
	 * @return bool True if conditions are met (element visible), false otherwise.
	 */
	public static function matches( $rule_data ) {
		$restriction_type = isset( $rule_data['restriction_type'] ) ? $rule_data['restriction_type'] : '';

		// Use WP site timezone for all comparisons
		$timezone  = wp_timezone();
		$now       = new \DateTime( 'now', $timezone );

		switch ( $restriction_type ) {

			case 'date_range':
				$group      = isset( $rule_data['date_range_group'] ) ? $rule_data['date_range_group'] : array();
				$date_start = isset( $group['date_start'] ) ? trim( $group['date_start'] ) : '';
				$date_end   = isset( $group['date_end'] ) ? trim( $group['date_end'] ) : '';

				$today = new \DateTime( $now->format( 'Y-m-d' ), $timezone );

				if ( $date_start !== '' ) {
					$start = \DateTime::createFromFormat( 'Y-m-d', $date_start, $timezone );
					if ( $start && $today < $start ) {
						return false;
					}
				}

				if ( $date_end !== '' ) {
					$end = \DateTime::createFromFormat( 'Y-m-d', $date_end, $timezone );
					if ( $end && $today > $end ) {
						return false;
					}
				}

				return true;

			case 'weekdays':
				$weekdays = isset( $rule_data['weekdays'] ) ? $rule_data['weekdays'] : array();
				if ( empty( $weekdays ) ) {
					return true;
				}
				// date('w') returns 0=Sunday … 6=Saturday, matching our stored values
				$current_weekday = $now->format( 'w' );
				return in_array( (string) $current_weekday, $weekdays, true );

			case 'time_range':
				$group      = isset( $rule_data['time_range_group'] ) ? $rule_data['time_range_group'] : array();
				$time_start = isset( $group['time_start'] ) ? trim( $group['time_start'] ) : '';
				$time_end   = isset( $group['time_end'] ) ? trim( $group['time_end'] ) : '';

				$current_time = $now->format( 'H:i' );

				if ( $time_start !== '' && $current_time < $time_start ) {
					return false;
				}

				if ( $time_end !== '' && $current_time > $time_end ) {
					return false;
				}

				return true;
		}

		// Unknown restriction type — show element
		return true;
	}
}
