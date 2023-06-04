<?php
$tipo = $componente['__type'];
$text = $componente['text'];
$style = $componente['style'];
$fullwidth = (isset($componente['fullwidth'])) ? $componente['fullwidth'] : false;
if($fullwidth) $style .= ' btn-block';

$type = $componente['type'];
$href = '#';
$attrs = '';
if($type == 'link'){
    $url_type = $componente['url_type'];
    switch ($url_type) {
        case 'externa':
            $href = $componente['url'];
            break;
        
        case 'interna':
            if($componente['post']){
                $href = get_permalink( str_replace('post_','',$componente['post']) );
            }
            break;
    }
    $attrs = ( isset($componente['new_tab']) && $componente['new_tab'] == 1) ? 'target="_blank"' : ''; 
}
if($type == 'download'){
    if($componente['file']){
        $href = wp_get_attachment_url( $componente['file'] );
        $attrs = ( isset($componente['new_tab']) && $componente['new_tab'] == 1) ? 'target="_blank"' : 'download'; 
    }
}

$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';

$classes_array = format_classes(array(
	'componente',
	'button',
	get_color_scheme($componente),
	$class
));

$alignment = $componente['alignment'];
if ( $alignment != 'left' ) array_push($classes_array, $alignment.'-align');

if ( $componente['tablet_text_align'] != '' ) array_push($classes_array, $componente['tablet_text_align'].'-on-tablet');
if ( $componente['mobile_text_align'] != '') array_push($classes_array, $componente['mobile_text_align'].'-on-mobile');

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
	<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
		<?php if($text) echo '<a href="'.$href.'" '.$attrs.' class="'.$style.'">'.$text.'</a>'; ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>