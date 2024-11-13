<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Layout_Control\Layout_Control;

/**
 * Extends Ultimate Fields with the layout field.
 *
 * @since 1.0
 */
add_action( 'uf.extend', 'uf_lc_extend' );
function uf_lc_extend() {
	// Use the Ultimate Fields autoloader for the layout control
	new Autoloader( 'Ultimate_Fields\\Layout_Control', __DIR__ . '/classes' );

	// Let the base class add all necessary hooks
	new Layout_Control( __FILE__, '1.0' );
}
