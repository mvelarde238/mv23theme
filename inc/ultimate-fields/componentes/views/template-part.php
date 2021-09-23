<?php
$tipo = $componente['__type'];
$content = $componente['content'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$classes_array = format_classes(array(
	'componente',
	'template-part',
	get_color_scheme($componente),
	$componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($content) echo get_template_part('templates/'.$content); ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>