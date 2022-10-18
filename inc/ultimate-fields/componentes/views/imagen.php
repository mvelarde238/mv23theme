<?php
$tipo = $componente['__type'];
$image = $componente['image'];
$type = ( isset($componente['type']) ) ? $componente['type'] : 'image';

if(empty($image) && count($componente['bgvideo']['videos']) < 1 ) return;

$image_url = wp_get_attachment_image_url( $image, IMAGE_THUMB_SIZE );
$alignment = $componente['alignment'];

if($componente['aspect_ratio'] == 'aspect-ratio-default'){
	$element_style = 'text-align:'.$alignment.';';
} else {
	$element_style = 'background-image: url('.$image_url.');';
}

$actions = (isset($componente['actions'])) ? $componente['actions'] : null;
$aspect_ratio = ( isset($componente['aspect_ratio']) && $componente['aspect_ratio'] != 'aspect-ratio-default' ) ? $componente['aspect_ratio'] : 'aspect-ratio-default';
if($type == 'video' && $aspect_ratio == 'aspect-ratio-default') $aspect_ratio = 'aspect-ratio-16-9';
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$video_background = video_background($componente);

$classes_array = format_classes(array(
	'componente',
	'image',
	get_color_scheme($componente),
	$componente['class'],
	$video_background['class'],
	$aspect_ratio
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
	<div class="image__element" style="<?=$element_style?>">
		<?php if( $type == 'image' && $componente['aspect_ratio'] == 'aspect-ratio-default'): ?>
			<img src="<?=$image_url?>" alt="">
		<?php endif; ?>
		<?php if($video_background['code']) echo $video_background['code'] ?>
		<?php echo generate_actions_code($componente); ?>
	</div>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  