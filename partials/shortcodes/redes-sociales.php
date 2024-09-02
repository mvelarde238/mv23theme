<?php
function print_redes_sociales( $atts ) {
	$redes_sociales = get_option( 'rrss' );

	$a = shortcode_atts( array(
		'class' => 'style1',
	), $atts );

	ob_start(); ?>
	<span class="rrss-module <?php echo $a['class']; ?>">
		<?php if (!empty($redes_sociales)):
			foreach ($redes_sociales as $red):
				$icon = ($red['icon'] == 'youtube') ? 'youtube-play' : $red['icon'];
				$link = '';
				if($red['icon'] != 'whatsapp'){
					if($red['url']) $link = $red['url'];
				} else {					
					if($red['number']) $link = 'https://api.whatsapp.com/send?phone='.$red['number'].'&text='.$red['msg'];
				}
				if($link) echo '<a href="'.$link.'" target="_blank"><i class="fa fa-'.$icon.'"></i></a>'; 
			endforeach;						
		endif ?>
	</span>
	<?php
	return ob_get_clean();
}
add_shortcode( 'redes_sociales', 'print_redes_sociales' );