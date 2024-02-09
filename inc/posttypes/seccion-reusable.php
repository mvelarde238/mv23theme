<?php
$secciones_reusables = new CPT(
    array(
        'post_type_name' => 'seccion_reusable',
        'plural' => 'Secciones Reusables',
    ), 
    array(
        'show_in_menu' => 'theme-options/theme-options-admin.php',
		'show_in_nav_menus' => false,
		'show_in_admin_bar' => false,
        'supports' => array('title','page-attributes','revisions')
    )
);