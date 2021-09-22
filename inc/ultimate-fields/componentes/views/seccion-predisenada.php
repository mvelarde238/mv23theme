<?php
$tipo = $componente['__type'];
$componentes = $componente['componentes'];
$section = $componente['section'];
$actions = $componente['actions'];

$classes_array = format_classes(array(
	'componente',
	'predesigned-section',
	$section,
	'cf',
	get_color_scheme($componente),
	$componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php foreach ($componentes as $componente ) { 
		$path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$componente['__type'].'.php';
		include $path;
	} ?>
</div>  