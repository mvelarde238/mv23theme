<?php
use Theme\CPT;

$megamenus = new CPT(
    array(
        'post_type_name' => 'megamenu',
        'plural' => 'Megamenú',
    ), 
    array(
        'show_in_menu' => 'theme-options-menu',
		'show_in_nav_menus' => false,
		'show_in_admin_bar' => false,
        'supports' => array('title')
    )
);