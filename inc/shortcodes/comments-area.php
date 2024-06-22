<?php
function print_ccmments_area() {
    ob_start();
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    return ob_get_clean();
}
add_shortcode( 'comments_area', 'print_ccmments_area' );