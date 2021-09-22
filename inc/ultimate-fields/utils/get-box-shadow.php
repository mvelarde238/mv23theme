<?php
function get_box_shadow($componente){
	$style = '';

	$add_box_shadow = (isset($componente['add_box_shadow'])) ? $componente['add_box_shadow'] : false;

	if ($add_box_shadow) {
		$box_shadows = $componente['box_shadow'];

		if (is_array($box_shadows) && count($box_shadows) > 0) {
			$style .= 'box-shadow: ';
			$properties = array();
			foreach ($box_shadows as $s) {
				$h_offset = (!empty($s['h-offset'])) ? $s['h-offset'] : "0";
				$v_offset = (!empty($s['v-offset'])) ? $s['v-offset'] : "0";
				$blur = (!empty($s['blur'])) ? $s['blur'] : "0";
				$color = ($s['color']) ? $s['color'] : '#000000';
				$color_rgba = hexToRgb($color,$s['alpha']);

				$property = $h_offset.'px '.$v_offset.'px '.$blur.'px ';
				if($s['spread']) $property .= $s['spread'].'px ';  
				$property .= 'rgba('.$color_rgba.')';
				if($s['position'] == 'inset') $property .= ' inset'; 

				$properties[] = $property;
			}
			$style .= implode(',', $properties).';';
		}
		
	}

	return $style; 
}