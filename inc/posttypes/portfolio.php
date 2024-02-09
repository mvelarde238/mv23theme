<?php
$portfolios = new CPT(
    'portfolio', 
    array(
        'supports' => array('title','editor','thumbnail','revisions','excerpt'),
		'menu_icon' => 'dashicons-screenoptions',
    )
);

$portfolios->register_taxonomy(array(
	'taxonomy_name' => 'portfolio-cat',
	'singular' => 'Categoría',
	'plural' => 'Categorías',
	'slug' => 'portfolio-cat'
));