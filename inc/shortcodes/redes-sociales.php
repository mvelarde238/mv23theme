<?php
function print_redes_sociales( $atts ) {
	$redes_sociales = get_option( 'rrss' );

	$a = shortcode_atts( array(
		'class' => 'style1',
	), $atts );

	ob_start(); ?>
	<span class="rrss-module <?php echo $a['class']; ?>">
		<?php if (!empty($redes_sociales)): ?>
				<?php foreach ($redes_sociales as $red): ?>
					<?php if ($red['url'] != ''): ?>
						<a href="<?php echo $red['url']; ?>" target="_blank"><i class="fa fa-<?php echo $red['icon']; ?>"></i></a>
					<?php endif ?>
				<?php endforeach ?>						
		<?php endif ?>
	</span>
	<?php
	return ob_get_clean();
}
add_shortcode( 'redes_sociales', 'print_redes_sociales' );