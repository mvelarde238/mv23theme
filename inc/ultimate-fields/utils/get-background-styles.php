<?php
function get_background_styles($componente){
	$style = '';
	$bgi = wp_get_attachment_url($componente['bgi']);

	if (array_key_exists('color_de_fondo', $componente)) {
	    $style .= ($componente['color_de_fondo']['add_bgc']) ? 'background-color: '.$componente['color_de_fondo']['bgc'].';' : '';
	}
	$style .= ($bgi) ? 'background-image: url('.$bgi.');' : '';
	$style .= ($bgi && $componente['bgi_options']['repeat'] != 'repeat') ? 'background-repeat: '.$componente['bgi_options']['repeat'].';' : '';
	$style .= ($bgi && $componente['bgi_options']['size'] != 'auto') ? 'background-size: '.$componente['bgi_options']['size'].';' : '';
	$style .= ($bgi && $componente['bgi_options']['position_x'] != 'left') ? 'background-position-x: '.$componente['bgi_options']['position_x'].';' : '';
	$style .= ($bgi && $componente['bgi_options']['position_y'] != 'top') ? 'background-position-y: '.$componente['bgi_options']['position_y'].';' : '';
	
	return $style;
}