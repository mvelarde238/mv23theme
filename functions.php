<?php
/*
Author: Miguel Velarde
URL: http://velarde23.com 
*/

/* Configure autoloading */
require_once get_template_directory() . '/core/includes/Autoloader.php';
$autoloader = new \Core\Includes\Autoloader( 'Core' , get_template_directory() , '.php' );

require_once( 'core/includes/utils.php' );
require_once( 'core/includes/constants.php' );

/* Run main theme class */
$theme = new \Core\MV23_Theme();
$theme->init_modules();
$theme->init();

/* DON'T DELETE THIS CLOSING TAG */ ?>