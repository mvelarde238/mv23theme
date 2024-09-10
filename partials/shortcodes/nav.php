<?php
use Core\Frontend\Nav_Walker;

function print_nav( $atts ) {
	$a = shortcode_atts( array(
		'id' => null,
	), $atts );

	ob_start();
    if( $a['id'] ){
        echo '<div class="horizontal-nav-1">';
        wp_nav_menu(array(
            'menu' => $a['id'],
            'container' => false,                           
            'container_class' => '',
            'walker' => new Nav_Walker(),
        ));
        echo '</div>';
    }
	return ob_get_clean();
}
add_shortcode( 'nav', 'print_nav' );