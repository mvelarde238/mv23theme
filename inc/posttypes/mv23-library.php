<?php
$mv23_library = new CPT(
	array(
		'post_type_name' => 'mv23_library', 
		'singular' => 'Library',
		'plural' => 'Library'
	),
    array(
		'show_in_menu' => 'edit.php?post_type=page',
        'supports' => array('title','thumbnail'),
		'menu_icon' => 'dashicons-tagcloud',
    )
);

$mv23_library->register_taxonomy(array(
	'taxonomy_name' => 'mv23_library_tax',
	'singular' => 'Categoría',
	'plural' => 'Categorías',
	'slug' => 'mv23_library_tax'
));