<?php
define ('THEME_MIGRATOR_DIR', __DIR__);
define ('THEME_MIGRATOR_PATH', get_template_directory_uri() . '/inc/migrator');

require_once( 'classes/Core.php' );

Theme_Migrator\Core::getInstance();