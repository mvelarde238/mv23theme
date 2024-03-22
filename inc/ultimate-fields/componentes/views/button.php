<?php
$tipo = $componente['__type'];
$style = $componente['style'];
$fullwidth = (isset($componente['fullwidth'])) ? $componente['fullwidth'] : false;
if($fullwidth) $style .= ' btn-block';

$text = $componente['text'] ?: 'Botón';
$icon = (isset( $componente['icon'])) ?  $componente['icon'] : null;
if( $icon ) {
    $icon_position = $componente['icon_position'] ?: 'left';
    $icon_html = '<i class="fa '.$icon.'"></i>';
    $style .= ' btn--icon-'.$componente['icon_position'];

    $text = ( $icon_position === 'left' ) ? $icon_html.' '.$text : $text.' '.$icon_html;
} 

$size = (isset($componente['size'])) ? $componente['size'] : false;
if($size) $style .= ' btn--'.$componente['size'];

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

$attributes = ( isset($componente['attributes']) ) ? $componente['attributes'] : array();
$additional_attrs = '';
if( is_array($attributes) && count($attributes) > 0 ){
    foreach ($attributes as $item) {
        if( $item['attribute'] && $item['value'] ){
            $additional_attrs .= ' '.$item['attribute'].'="'.$item['value'].'"';
        }
    }
}

$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';

$classes_array = format_classes(array(
	'componente',
	'button-cmp',
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
		<?php if($text) echo '<a href="'.$href.'" '.$attrs.' class="'.$style.'"'.$additional_attrs.'>'.$text.'</a>'; ?>
	<?php if ($layout == 'layout2') echo '</div>'; ?>
</div>