<?php
function print_ccmments_area() {
    ob_start();
    get_template_part('partials/comments');
    return ob_get_clean();
}
add_shortcode( 'comments_area', 'print_ccmments_area' );