<?php
use Core\Builder\Component\Comments_Area;

function print_comments_area() {
    ob_start();
    echo Comments_Area::display();
    return ob_get_clean();
}
add_shortcode( 'comments_area', 'print_comments_area' );