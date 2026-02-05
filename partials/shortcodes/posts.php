<?php
use Core\Builder\Component\Archive_Posts;

function print_posts( $atts ) {
	ob_start();
    echo Archive_Posts::display( $atts );
	return ob_get_clean();
}
add_shortcode( 'posts', 'print_posts' );