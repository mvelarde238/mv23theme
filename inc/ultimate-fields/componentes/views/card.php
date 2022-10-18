<?php
$tipo = $componente['__type'];
$content_type = (isset( $componente['content_type'] )) ? $componente['content_type'] : 'components';

$actions = (isset($componente['actions'])) ? $componente['actions'] : null;
$aspect_ratio = ( isset($componente['aspect_ratio']) && $componente['aspect_ratio'] != 'aspect-ratio-default' ) ? $componente['aspect_ratio'] : '';
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$components_margin = (!empty($componente['components_margin'])) ? $componente['components_margin'] : null;
$components_margin_attrs = ( $components_margin && $components_margin != 20) ? 'data-setmargin='.$components_margin : '';

$video_background = video_background($componente);

$classes_array = format_classes(array(
	'componente',
	'card',
	get_color_scheme($componente),
	$componente['class'],
	$video_background['class'],
	$aspect_ratio
));

if( isset($componente['content_alignment']) && $componente['content_alignment'] != 'flex-start' && !empty($componente['content_alignment']) ) array_push($classes_array, 'alignment-'.$componente['content_alignment']);

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?> <?=$components_margin_attrs?>>
	<?php if($video_background['code']) echo $video_background['code'] ?>
	<?php echo generate_actions_code($componente); ?>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
	<div class="card__components">
		<?php
		if( $content_type == 'simple' ):
			if( $componente['content'] ){
				echo '<div class="editor-de-texto componente">';
				echo do_shortcode(wpautop($componente['content']));
				echo '</div>';
			};
		else: 
			$componentes = $componente['componentes'];
			foreach ($componentes as $componente ) { 
				$componente['layout'] = 'layout1';
				$path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$componente['__type'].'.php';
				include $path;
			} 
		endif; 
		?>
	</div>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  