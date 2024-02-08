<?php
/**
 * Dequeue styles.
 */
if( !function_exists('mv23_dequeue_styles') ){
    function mv23_dequeue_styles() {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wc-block-style' );
    }
}
add_action( 'wp_print_styles', 'mv23_dequeue_styles', 100 );