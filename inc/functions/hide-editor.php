<?php
add_action( 'init', function() {

    $posttypes = array('post','page');
    $show_editor_in = get_option('show_editor_in') ? get_option('show_editor_in') : array();

    foreach ($posttypes as $slug){
        if ( !in_array($slug, $show_editor_in) ) {
            remove_post_type_support( $slug, 'editor' );
        }
    }
}, 99);