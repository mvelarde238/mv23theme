<?php
$tipo = $componente['__type'];
$content = $componente['content'];

$classes_array = format_classes(array(
	'componente',
	'template-part',
	get_color_scheme($componente),
	$componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if($content) echo get_template_part('templates/'.$content); ?>
</div>