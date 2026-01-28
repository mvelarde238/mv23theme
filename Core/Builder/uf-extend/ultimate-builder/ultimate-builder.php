<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Ultimate_Builder\Ultimate_Builder;

/**
 * Extends Ultimate Fields with a builder field
 */
add_action( 'uf.extend', function(){
	// Use the Ultimate Fields autoloader
	new Autoloader( 'Ultimate_Fields\\Ultimate_Builder', __DIR__ . '/classes' );

	// Let the base class add all necessary hooks
	new Ultimate_Builder( __FILE__, '1.1.0' );
});
