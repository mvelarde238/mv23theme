<?php
function get_border_styles($componente){
	$style = '';

	$show_border = (isset($componente['show_border'])) ? $componente['show_border'] : false;
	$add_border_radius = (isset($componente['add_border_radius'])) ? $componente['add_border_radius'] : false;

	if ($show_border) {
		$border_width = $componente['border']['width'].'px';
		$style_and_color = $componente['border']['style'].' '.$componente['border']['color'].';';
		$border_properties = $border_width.' '.$style_and_color;

		if ($componente['border_apply_to'] == 'all') {
			$style .= 'border:'.$border_properties;
		}

		if ($componente['border_apply_to'] == 'custom') {
			$custom_border = $componente['custom_border'];
			$style .= ($custom_border['top'] == 1) ? 'border-top:'.$border_properties : '';  
			$style .= ($custom_border['right'] == 1) ? 'border-right:'.$border_properties : '';  
			$style .= ($custom_border['bottom'] == 1) ? 'border-bottom:'.$border_properties : '';  
			$style .= ($custom_border['left'] == 1) ? 'border-left:'.$border_properties : '';  
		}
	}

	if ($add_border_radius) {
		$radius_properties = $componente['border_radius'].'px;';
		if ($componente['radius_apply_to'] == 'all') {
			$style .= 'border-radius: '.$radius_properties;
		}

		if ($componente['radius_apply_to'] == 'custom') {
			$custom_radius = $componente['custom_radius'];
			$style .= ($custom_radius['top-left'] == 1) ? 'border-top-left-radius:'.$radius_properties : '';
			$style .= ($custom_radius['top-right'] == 1) ? 'border-top-right-radius:'.$radius_properties : '';
			$style .= ($custom_radius['bottom-right'] == 1) ? 'border-bottom-right-radius:'.$radius_properties : '';
			$style .= ($custom_radius['bottom-left'] == 1) ? 'border-bottom-left-radius:'.$radius_properties : '';
		}
	}

	return $style; 
}