<?php
$tipo = $componente['__type'];
$content = $componente['content'];
$iposition = (isset($componente['iposition'])) ? 'icon--'.$componente['iposition'] : '';
$center_all_class = (isset($componente['center-all']) && $componente['center-all'] == 1) ? 'center-all' : '';

$classes_array = format_classes(array(
	'componente',
	'icon-and-text',
	$iposition,
	$center_all_class,
	get_color_scheme($componente),
	$componente['class'],
));

// if ( $componente['tablet_text_align'] != '' ) array_push($classes_array, $componente['tablet_text_align'].'-on-tablet');
// if ( $componente['mobile_text_align'] != '') array_push($classes_array, $componente['mobile_text_align'].'-on-mobile');

// **************************************************************************************************
$icon_element = $componente['ielement'];

if ($icon_element == 'icono') {
	$element = '<i class="fa '.$componente['iname'].'"></i>';
} else {
	$imagen_url = wp_get_attachment_url($componente['iimage']);
	$element = '<img style="height:'.$componente['ifontsize'].'px;" src="'.$imagen_url .'" />';
}

// if ($icon_element == 'icono') 
	$icon_style = 'font-size:'.$componente['ifontsize'].'px;';
if($componente['icolor']) $icon_style .= 'color:'.$componente['icolor'].';';
$icon_style .= ($componente['iposition'] == 'top' && isset($componente['itopalign']) && $componente['itopalign']) ? "text-align:".$componente['itopalign'].";" : "text-align:center;";
$icon_style = ($icon_style) ? 'style="'.$icon_style.'"' : '';

$classes = array('icon');
if($componente['istyle']!='default') array_push($classes, 'icon--'.$componente['istyle']);
if(isset($componente['hide-icon-on-mobile']) && $componente['hide-icon-on-mobile']) array_push($classes, 'hide-on-small-only');
$icon_class = (!empty($classes)) ? 'class="'.implode(' ',$classes).'"' : '';

$hasBackground = false;
if ($componente['istyle'] == 'circle' ) $hasBackground = true;
if ($componente['istyle'] == 'circle-outline' && $componente['ihas_bgc'] == 1 ) $hasBackground = true;
$backgroundColor = ( $hasBackground ) ? 'rgba('.hexToRgb($componente['ibgc'],$componente['ibgc_alpha']).')' : '';

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<div <?=$icon_class?> <?=$icon_style?>>
		<?php if ($componente['istyle']!='default') { echo '<span style="background-color:'.$backgroundColor.'">'; } else { echo '<span>'; }; ?>
				<?php echo $element; ?>
		</span>
	</div>
	<div><?php if($content) echo apply_filters('the_content', $content); ?></div>
	<?php echo generate_actions_code($componente); ?>
</div>