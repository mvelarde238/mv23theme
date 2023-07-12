<?php
$tipo = $componente['__type'];
$content = $componente['content'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';

$classes_array = format_classes(array(
	'componente',
	'editor-de-texto',
	get_color_scheme($componente),
	$class
));

if ( isset($componente['tablet_text_align']) && $componente['tablet_text_align'] != '' ) array_push($classes_array, $componente['tablet_text_align'].'-on-tablet');
if ( isset($componente['mobile_text_align']) && $componente['mobile_text_align'] != '') array_push($classes_array, $componente['mobile_text_align'].'-on-mobile');

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($content) echo do_shortcode(wpautop(oembed($content))); ?>
		<?php echo generate_actions_code($componente); ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>