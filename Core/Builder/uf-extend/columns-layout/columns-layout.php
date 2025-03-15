<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Columns_Layout\Columns_Layout;

/**
 * Extends Ultimate Fields with the common settings field.
 */
add_action( 'uf.extend', function(){
	// Use the Ultimate Fields autoloader
	new Autoloader( 'Ultimate_Fields\\Columns_Layout', __DIR__ . '/classes' );

	// Let the base class add all necessary hooks
	new Columns_Layout( __FILE__, '1.0.4' );
});
