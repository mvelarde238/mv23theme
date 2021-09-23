<?php
$tipo = $componente['__type'];
$componentes = $componente['componentes'];
$section = $componente['section'];
$actions = $componente['actions'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

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
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
	<?php foreach ($componentes as $componente ) { 
		$path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$componente['__type'].'.php';
		include $path;
	} ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  