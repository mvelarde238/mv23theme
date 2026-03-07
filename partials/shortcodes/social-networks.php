<?php
function print_social_networks( $atts ) {
	$social_networks = get_option( 'social_networks', array() );

	$a = shortcode_atts( array(
		'class' => 'style1',
	), $atts );

	ob_start(); ?>
	<span class="sns-module <?php echo $a['class']; ?>">
		<?php if (!empty($social_networks)):
			foreach ($social_networks as $red):
				$icon = $red['icon'];
				$link = '';
				if($icon != 'whatsapp'){
					if($red['url']) $link = $red['url'];
				} else {					
					if($red['number']) $link = 'https://api.whatsapp.com/send?phone='.$red['number'].'&text='.$red['msg'];
				}
				if($link) echo '<a href="'.$link.'" target="_blank"><i class="bi bi-'.$icon.'"></i></a>'; 
			endforeach;						
		endif ?>
	</span>
	<?php
	return ob_get_clean();
}
add_shortcode( 'social_networks', 'print_social_networks' );
add_shortcode( 'redes_sociales', 'print_social_networks' );