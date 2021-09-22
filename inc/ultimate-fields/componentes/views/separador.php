<?php
$tipo = $componente['__type'];

$componente_classes = (array_key_exists('class', $componente)) ? $componente['class'] : ''; 

$classes_array = format_classes(array(
	'componente-separador',
	get_color_scheme($componente),
	$componente_classes
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>></div>