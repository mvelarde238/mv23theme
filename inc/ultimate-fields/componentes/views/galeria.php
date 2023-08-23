<?php
$tipo = $componente['__type'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';

$classes_array = format_classes(array(
    'componente',
	'mv23-gallery',
	get_color_scheme($componente),
	$class
));

$source = ( isset($componente['source']) ) ? $componente['source'] : 'wp-media'; // default for backward compatibility
$settings = $componente['wp_media_folder_settings'];
$orderby = ($settings['orderby']) ? $settings['orderby'] : 'custom';

$shortcode = '[gallery link="'.$settings['link'].'" columns="'.$settings['columns'].'"  size="'.$settings['size'].'" orderby="'.$orderby.'"';

if($source == 'wp-media'){
	$wp_media_folder = $componente['wp_media_folder'];
	if($wp_media_folder){
		$order = (isset($settings['order']) && $settings['order']) ? $settings['order'] : 'DESC';
		$shortcode .= ' wpmf_folder_id="'.$wp_media_folder.'" wpmf_autoinsert="1" wpmf_order="'.$order.'" targetsize="'.$settings['targetsize'].'"';
	}
} else {
	$gallery = $componente['gallery'];
	$ids = implode(',',$gallery);
	$shortcode .= ' ids="'.$ids.'"';
}

if(WPMEDIAFOLDER_IS_ACTIVE) $shortcode .= ' display="'.$settings['display'].'"';
$shortcode .= ']';

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($shortcode) echo do_shortcode($shortcode); ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>