<?php
function format_classes($classes){
	$cleaned = array();

	foreach ($classes as $class) {
		if ($class != '') {
			array_push($cleaned, $class);
		}	
	}

	return $cleaned;
}

function generate_class_attribute($classes,$componente=null){
	if (isset($componente['theme_clases']) && !empty($componente['theme_clases'])) $classes = array_merge($classes, $componente['theme_clases']);
	return (!empty($classes)) ? 'class="'.implode(' ', $classes).'"' : '';
}


function generate_id_attribute($componente){
	return (isset($componente['module_id']) && !empty($componente['module_id'])) ? 'id="'.$componente['module_id'].'"' : '';
}