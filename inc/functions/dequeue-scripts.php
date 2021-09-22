<?php
/**
 * Dequeue scripts.
 */
function mv23_dequeue_scripts() {

    // if (is_front_page()) {
    //   wp_dequeue_script( 'contact-form-7' );
    // }
}
add_action( 'wp_print_scripts', 'mv23_dequeue_scripts', 100 );



/**
 * Dequeue styles.
 */
function mv23_dequeue_styles() {

    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wc-block-style' );

    // if (is_front_page()) {
    //     wp_dequeue_style( 'contact-form-7' );
    // }
}
add_action( 'wp_print_styles', 'mv23_dequeue_styles', 100 );