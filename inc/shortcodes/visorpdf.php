<?php
function print_visor_pdf( $atts ){
	$a = shortcode_atts( array(
		'pdf' => '',
	), $atts ); 

	if ($a['pdf']) :
		ob_start();
		?>
		<div class="visorpdf">
			<embed src="<?php echo $a['pdf']; ?>">
		</div>
		<?php
		return ob_get_clean();
	endif;
}
add_shortcode( 'visorpdf', 'print_visor_pdf' );