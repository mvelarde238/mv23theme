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

$wp_media_folder = $componente['wp_media_folder'];
if($wp_media_folder){
    $settings = $componente['wp_media_folder_settings'];
	$orderby = ($settings['orderby']) ? $settings['orderby'] : 'custom';
	$order = (isset($settings['order']) && $settings['order']) ? $settings['order'] : 'DESC';

    $shortcode = '[gallery link="'.$settings['link'].'" columns="'.$settings['columns'].'" display="'.$settings['display'].'" wpmf_folder_id="'.$wp_media_folder.'" wpmf_autoinsert="1" orderby="'.$orderby.'" wpmf_order="'.$order.'" size="'.$settings['size'].'" targetsize="'.$settings['targetsize'].'"]';
}

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($wp_media_folder) echo do_shortcode($shortcode); ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>