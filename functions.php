<?php
/*
Author: Miguel Velarde
URL: http://velarde23.com 
*/

/* Configure autoloading */
require_once get_template_directory() . '/Core/Includes/Autoloader.php';
$autoloader = new \Core\Includes\Autoloader( 'Core' , get_template_directory() , '.php' );

require_once( 'Core/Includes/utils.php' );
require_once( 'Core/Includes/constants.php' );
require_once( 'libs/ultimate-fields/ultimate-fields.php' );
require_once( 'libs/ultimate-fields-layout-control-master/ultimate-fields-layout-control.php' );

/* Run main theme class */
$theme = new \Core\Theme();
$theme->init();

/* DON'T DELETE THIS CLOSING TAG */ ?>