<?php
$tipo = $componente['__type'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';

$classes_array = format_classes(array(
    'componente',
	'theme-gallery-comp',
	get_color_scheme($componente),
	$class
));

$hide_gallery = ( isset($componente['hide_gallery']) ) ? $componente['hide_gallery'] : false;
if($hide_gallery) array_push($classes_array,'hide');

$source = ( isset($componente['source']) ) ? $componente['source'] : 'wp-media'; // default for backward compatibility
$settings = $componente['wp_media_folder_settings'];
$aspect_ratio = ( isset($componente['aspect_ratio']) && $componente['aspect_ratio'] != 'aspect-ratio-default' ) ? $componente['aspect_ratio'] : 'aspect-ratio-default';
$shortcode_name = ($source === 'manual') ? 'theme_gallery' : 'theme_gallery';
$gallery_id = $componente['gallery_id'];

$shortcode = '['.$shortcode_name.' link="'.$settings['link'].'" columns="'.$settings['columns'].'"  size="'.$settings['size'].'" targetsize="'.$settings['targetsize'].'" aspectratio="'.$aspect_ratio.'" display="'.$settings['display'].'" gallery_id="'.$gallery_id.'"';

if($source == 'wp-media'){
	$wp_media_folder = $componente['wp_media_folder'];
	if($wp_media_folder){
		$shortcode .= ' wpmf_folder_id="'.$wp_media_folder.'" wpmf_autoinsert="1"';
	}
} else {
	$gallery = $componente['gallery'];
	$ids = implode(',',$gallery);
	$shortcode .= ' ids="'.$ids.'"';
}

$shortcode .= ']';

$attributes = generate_attributes($componente, $classes_array);

if($aspect_ratio != 'default') $attributes .= ' style="--aspect-ratio:'.$aspect_ratio.'"';
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($shortcode) echo do_shortcode($shortcode); ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>