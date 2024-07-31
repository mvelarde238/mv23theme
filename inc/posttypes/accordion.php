<?php
$accordions = new CPT(
    array(
        'post_type_name' => 'v23accordion',
        'singular' => 'Accordion',
        'plural' => 'Accordion',
    ), 
    array(
        'show_in_menu' => 'theme-options-menu',
		'show_in_nav_menus' => false,
		'show_in_admin_bar' => false,
        'supports' => array('title')
    )
);

/*
 * Manage CPT columns
 */
$accordions->columns(array(
    'cb' => '<input type="checkbox" />',
    'title' => __('Title'),
    'shortcode' => __('Shortcode'),
    'date' => __('Date')
));
    
$accordions->populate_column('shortcode', function($column_name, $post) {
    echo '<code>[accordion id="'.$post->ID.'"]</code>';
});