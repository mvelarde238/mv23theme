<?php
function print_counter( $atts ) {
	$a = shortcode_atts( array(
		'number' => 0,
		'start' => 0,
		'duration' => 1000
	), $atts );

	ob_start(); ?>
	<span class="counter"
		data-duration="<?php echo $a['duration']; ?>"
		data-start="<?php echo $a['start']; ?>"
		data-number="<?php echo $a['number']; ?>"><?php echo $a['start']; ?></span>
	<?php return ob_get_clean();
}
add_shortcode( 'counter', 'print_counter' );