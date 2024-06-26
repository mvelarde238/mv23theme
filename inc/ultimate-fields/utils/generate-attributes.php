<?php
function generate_attributes($componente, $classes_array){
	$style = '';
	$style .= get_margenes($componente);
	
	$background_styles = get_background_styles($componente);
	if ($background_styles) $style .= $background_styles;
	
	$border_styles = get_border_styles($componente);
	if ($border_styles) $style .= $border_styles;
	
	$box_shadows = get_box_shadow($componente);
	if ($box_shadows) $style .= $box_shadows;
	
	if ($componente['__type']=='separador') {
		$unit = ( isset($componente['unit']) ) ? $componente['unit'] : 'px';
		$style .= 'height:'.$componente['height'].$unit;
	}

	if ($componente['__type']=='icono-y-texto') {
		if (isset($componente['iposition']) && $componente['iposition'] != 'top' && $componente['ialign']) $style .= "align-items:".$componente['ialign'].";";
	}

	$no_padding_components = array('columnas-internas','columnas-simples','columnas','grid-de-items','column','grid__item','fila','separador','components-wrapper');
	if ( 
		!in_array($componente['__type'], $no_padding_components) &&
		($background_styles || $border_styles || $box_shadows)
	){
		array_push($classes_array, 'add-padding');
	} 

	if ( /* theses components dosnt need padding but need component spacing-separator when they have background, border or shadow */
		in_array($componente['__type'], $no_padding_components ) &&
		($background_styles || $border_styles || $box_shadows)
	){
		array_push($classes_array, 'componente');
	}

	if ($componente['__type']=='columnas') {
		if ($background_styles || $border_styles || $box_shadows) array_push($classes_array, 'componente');
	}
	$style = ($style) ? 'style="'.$style.'"' : '';

	if (isset($componente['layout'])) {
		$layout = $componente['layout'];
		if ($layout == 'layout2' || $layout == 'layout3' || $layout == 'layout4') array_push($classes_array, 'full-width');
	}
	
	$class = generate_class_attribute($classes_array,$componente);
	$id = generate_id_attribute($componente);
	$animationAttrs = generate_animation_attributes($componente);

	$scrollAnimations = (SCROLL_ANIMATIONS) ? generate_scroll_animations($componente) : '';

	$attributes = [ $id,$class,$style,$animationAttrs,$scrollAnimations ];
	return implode(' ',array_filter($attributes));
}