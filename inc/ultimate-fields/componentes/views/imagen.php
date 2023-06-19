<?php
$tipo = $componente['__type'];
$image = $componente['image'];
$type = ( isset($componente['type']) ) ? $componente['type'] : 'image';
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$aspect_ratio = ( isset($componente['aspect_ratio']) && $componente['aspect_ratio'] != 'aspect-ratio-default' ) ? $componente['aspect_ratio'] : 'aspect-ratio-default';
$element_style = '';

$classes_array = array(
	'componente',
	'media',
	$type,
	get_color_scheme($componente),
	$componente['class']
);

if($type == 'image'){
	$image_url = wp_get_attachment_image_url( $image, IMAGE_THUMB_SIZE );
	$alignment = $componente['alignment'];
	if($componente['aspect_ratio'] == 'aspect-ratio-default'){
		if($alignment) $element_style = 'text-align:'.$alignment.';';
	} else {
		$element_style = 'background-image: url('.$image_url.');';
	}
}

if($type == 'video'){
	$video_settings = (isset($componente['video_settings'])) ? $componente['video_settings'] : array();
	$video_bgc = $video_settings['bgc'];
	if($video_bgc) $element_style .= 'background-color:'.$video_bgc.';';

	$video_type = ( isset($componente['video_type']) ) ? $componente['video_type'] : 'popable';

	if( $video_type == 'popable' ){
		$componente['actions'] = array(
			array(
				'trigger' => 'click',
				'action' => 'open-video-popup'
			)
		);
	}

	$video_source = ( isset($componente['video_source']) ) ? $componente['video_source'] : 'selfhosted';
	if($video_source != 'selfhosted' && $aspect_ratio == 'aspect-ratio-default') $aspect_ratio = 'aspect-ratio-16-9';

	$video_background = video_background($componente);
	array_push($classes_array, $video_type);
	array_push($classes_array, $video_source);
	array_push($classes_array, $video_background['class']);
}

array_push($classes_array, $aspect_ratio);
$attributes = generate_attributes($componente, format_classes($classes_array) );
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
	<div class="media__element" style="<?=$element_style?>">
		<?php if( $type == 'image' && $componente['aspect_ratio'] == 'aspect-ratio-default'): ?>
			<img src="<?=$image_url?>" alt="">
		<?php endif; ?>
		<?php if($type == 'video' && $video_background['code']) echo $video_background['code'] ?>
		<?php echo generate_actions_code($componente); ?>
	</div>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  