<?php
$tipo = $componente['__type'];
$image = $componente['image'];
if(empty($image)) return;
$image_url = wp_get_attachment_url($image);
$alignment = $componente['alignment'];

if($componente['aspect_ratio'] == 'aspect-ratio-default'){
	$element_style = 'text-align:'.$alignment.';';
} else {
	$element_style = 'background-image: url('.$image_url.');';
}

$actions = (isset($componente['actions'])) ? $componente['actions'] : null;
$aspect_ratio = ( isset($componente['aspect_ratio']) && $componente['aspect_ratio'] != 'aspect-ratio-default' ) ? $componente['aspect_ratio'] : '';
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

// video implementation
$video_url = null;
$videos = $componente['bgvideo'];
$video_id = (is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
if($video_id) {
	$video_url = wp_get_attachment_url($video_id);
	$video_opacity = (isset($componente['video_opacity']) && $componente['video_opacity'] ) ? $componente['video_opacity'] : 100;
	$video_style = ($video_opacity != 100) ? 'style="opacity:'.($video_opacity/100).';"' : ''; 
}
$has_video = ($video_url) ? 'has-video-background' : '';
// end video implementation

$classes_array = format_classes(array(
	'componente',
	'image',
	get_color_scheme($componente),
	$componente['class'],
	$has_video,
	$aspect_ratio
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
	<div class="image__element" style="<?=$element_style?>">
		<?php if($componente['aspect_ratio'] == 'aspect-ratio-default'): ?>
			<img src="<?=$image_url?>" alt="">
		<?php endif; ?>
		<?php if ($video_id): ?>
			<video <?=$video_style?> width="100%" loop muted="muted"><source src="<?=$video_url?>">Your browser does not support the video tag.</video>
		<?php endif ?>
		<?php echo generate_actions_code($componente); ?>
		<?php if ( is_array($actions) && count($actions)>0 ):
		foreach ($actions as $action) {
			if ($action['trigger'] == 'click' && $action['action'] == 'open-video-popup') {
				if ($video_id):
		        	echo '<a class="cover-all zoom-video" href="'.$video_url.'"></a>';
		        endif;
			}
		}
	endif; ?>
	</div>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  