<?php
namespace Ultimate_Fields\Ultimate_Builder;

/**
 * Extends WP_Screen with custom properties for Ultimate Fields Builder detection.
 *
 * This class adds helper properties and methods to detect when the current screen
 * is the Ultimate Fields Builder editor, making it easier to conditionally load
 * scripts and functionality specific to the builder.
 *
 * @since 1.0
 */
class Screen_Helper {
	/**
	 * Initialize the screen helper functionality.
	 *
	 * Hooks into WordPress to extend the WP_Screen object with custom properties.
	 *
	 * @since 1.0
	 */
	public static function init() {
		add_action( 'current_screen', array( __CLASS__, 'extend_screen' ), 5 );
	}

	/**
	 * Extend WP_Screen object with custom builder-related properties.
	 *
	 * Adds the following properties to the WP_Screen object:
	 * - is_uf_builder_editor: Boolean indicating if current screen is the builder editor
	 * - builder_meta_field: The meta field being edited (if in builder)
	 * - builder_post_id: The post ID being edited (if in builder)
	 *
	 * @since 1.0
	 * @param \WP_Screen $screen The current screen object.
	 * @return \WP_Screen The modified screen object.
	 */
	public static function extend_screen( $screen ) {
		// Detect if we're in the builder editor
		$screen->is_uf_builder_editor = self::is_builder_editor( $screen );

		// Add additional builder-related properties
		if ( $screen->is_uf_builder_editor ) {
			$screen->builder_meta_field = isset( $_GET['meta'] ) 
				? sanitize_text_field( $_GET['meta'] ) 
				: '';
			
			$screen->builder_post_id = isset( $_GET['post'] ) 
				? absint( $_GET['post'] ) 
				: 0;
		} else {
			$screen->builder_meta_field = '';
			$screen->builder_post_id = 0;
		}

		return $screen;
	}

	/**
	 * Check if current screen is the Ultimate Fields Builder editor.
	 *
	 * This method can be used as a standalone function to detect the builder editor
	 * without relying on the WP_Screen object property.
	 *
	 * @since 1.0
	 * @param \WP_Screen|null $screen Optional. Screen object. Default current screen.
	 * @return bool True if builder editor, false otherwise.
	 */
	public static function is_builder_editor( $screen = null ) {
		if ( ! $screen ) {
			$screen = get_current_screen();
		}

		if ( ! $screen ) {
			return false;
		}

		// Check all conditions for builder editor
		return (
			in_array( $screen->base, [ 'post', 'post-new' ], true ) 
			&& isset( $_GET['action'] )
			&& isset( $_GET['meta'] )
			&& $_GET['action'] === 'ultimate-builder'
		);
	}

	/**
	 * Get the builder meta field being edited.
	 *
	 * @since 1.0
	 * @return string The meta field name, or empty string if not in builder.
	 */
	public static function get_builder_meta_field() {
		$screen = get_current_screen();
		
		if ( $screen && isset( $screen->builder_meta_field ) ) {
			return $screen->builder_meta_field;
		}

		// Fallback to direct GET parameter check
		return isset( $_GET['meta'] ) ? sanitize_text_field( $_GET['meta'] ) : '';
	}

	/**
	 * Get the builder post ID being edited.
	 *
	 * @since 1.0
	 * @return int The post ID, or 0 if not in builder.
	 */
	public static function get_builder_post_id() {
		$screen = get_current_screen();
		
		if ( $screen && isset( $screen->builder_post_id ) ) {
			return $screen->builder_post_id;
		}

		// Fallback to direct GET parameter check
		return isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	}
}
