<?php
function print_counter( $atts ) {
	$a = shortcode_atts( array(
		'number' => 23,
		'start' => 0,
		'duration' => 1000,
		'prefix' => '',
		'suffix' => '',
	), $atts );

	$prefix = ! empty( $a['prefix'] ) ? esc_html( $a['prefix'] ) : '';
	$suffix = ! empty( $a['suffix'] ) ? esc_html( $a['suffix'] ) : '';

	ob_start();
	echo '<span class="counter"
		data-duration="' . esc_attr( $a['duration'] ) . '"
		data-start="' . esc_attr( $a['start'] ) . '"
		data-number="' . esc_attr( $a['number'] ) . '">';
	echo $prefix . '<span class="counter-number">' . esc_html( $a['number'] ) . '</span>' . $suffix;
	echo '</span>';
	return ob_get_clean();
}
add_shortcode( 'counter', 'print_counter' );