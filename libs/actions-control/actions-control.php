<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Actions_Control\Actions_Control;

/**
 * Extends Ultimate Fields with the actions control field.
 */
add_action( 'uf.extend', function(){
	// Use the Ultimate Fields autoloader
	new Autoloader( 'Ultimate_Fields\\Actions_Control', __DIR__ . '/classes' );

	// Let the base class add all necessary hooks
	new Actions_Control( __FILE__, '0.0.1' );
});
