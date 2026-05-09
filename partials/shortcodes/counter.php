<?php
function print_counter( $atts ) {
	$a = shortcode_atts( array(
		'number'              => 23,
		'duration'            => 1000,
		'prefix'              => '',
		'suffix'              => '',
		'format_number'       => '0',
		'thousands_separator' => '.',
	), $atts );

	$prefix = ! empty( $a['prefix'] ) ? esc_html( $a['prefix'] ) : '';
	$suffix = ! empty( $a['suffix'] ) ? esc_html( $a['suffix'] ) : '';

	ob_start();
	echo '<span class="counter"
		data-duration="' . esc_attr( $a['duration'] ) . '"
		data-number="' . esc_attr( $a['number'] ) . '"
		data-format="' . esc_attr( $a['format_number'] ) . '"
		data-thousands-sep="' . esc_attr( $a['thousands_separator'] ) . '">';
	echo $prefix . '<span class="counter-number">' . esc_html( $a['number'] ) . '</span>' . $suffix;
	echo '</span>';
	return ob_get_clean();
}
add_shortcode( 'counter', 'print_counter' );