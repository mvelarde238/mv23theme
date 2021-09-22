<?php
$tipo = $componente['__type'];
$content = $componente['content'];

$classes_array = format_classes(array(
	'componente',
	'editor-de-texto',
	get_color_scheme($componente),
	$componente['class']
));

if ( $componente['tablet_text_align'] != '' ) array_push($classes_array, $componente['tablet_text_align'].'-on-tablet');
if ( $componente['mobile_text_align'] != '') array_push($classes_array, $componente['mobile_text_align'].'-on-mobile');

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if($content) echo apply_filters('the_content', $content); ?>
</div>