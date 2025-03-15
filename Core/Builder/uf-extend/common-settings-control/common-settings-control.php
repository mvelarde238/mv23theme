<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Common_Settings_Control\Common_Settings_Control;

/**
 * Extends Ultimate Fields with the common settings field.
 */
add_action( 'uf.extend', function(){
	// Use the Ultimate Fields autoloader
	new Autoloader( 'Ultimate_Fields\\Common_Settings_Control', __DIR__ . '/classes' );

	// Let the base class add all necessary hooks
	new Common_Settings_Control( __FILE__, '1.0.2' );
});
