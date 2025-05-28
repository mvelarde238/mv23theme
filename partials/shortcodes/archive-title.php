<?php
function print_archive_title(){
    ob_start();
    echo the_archive_title();
    return ob_get_clean();
}
add_shortcode( 'archive_title', 'print_archive_title' );