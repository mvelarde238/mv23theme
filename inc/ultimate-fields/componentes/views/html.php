<?php
$tipo = $componente['__type'];
$content = $componente['content'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';

$classes_array = format_classes(array(
	'componente',
	'html',
	get_color_scheme($componente),
	$class
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($content) echo $content; ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>