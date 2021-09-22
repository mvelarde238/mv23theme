<?php
function print_download_link( $atts ){
	$a = shortcode_atts( array(
		'text' => 'Descargar',
		'url' => '',
		'class' => '',
	), $atts ); 

	$file = ($a['url']) ? $a['url'] : '#';
	$text = ($a['text']) ? $a['text'] : 'Enlace de descarga';
	$class = ($a['class']) ? $a['class'] : '';

	ob_start();
	?>
	<a class="<?php echo $class ?>" href="<?php echo $file; ?>" download><span class="icon"></span> <?php echo $text; ?></a>
	<?php return ob_get_clean();
}
add_shortcode( 'download_link', 'print_download_link' );