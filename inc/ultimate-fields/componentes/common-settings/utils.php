<?php
$default_settings_fields = array_merge(
	$settings_fields, $margenes, $bordes, $box_shadow, $animation
);


function generate_attributes($componente, $classes_array){
	$style = '';
	$style .= get_margenes($componente);
	
	$background_styles = get_background_styles($componente);
	if ($background_styles) $style .= $background_styles;
	
	$border_styles = get_border_styles($componente);
	if ($border_styles) $style .= $border_styles;
	
	$box_shadows = get_box_shadow($componente);
	if ($box_shadows) $style .= $box_shadows;
	
	if ($componente['__type']=='separador') {
		$style .= 'height:'.$componente['height'] . 'px;';
	}

	if ($componente['__type']=='icono-y-texto') {
		if (isset($componente['iposition']) && $componente['iposition'] != 'top' && $componente['ialign']) $style .= "align-items:".$componente['ialign'].";";
	}

	$no_padding_components = array('columnas-internas','columnas-simples','columnas','grid-de-items','column','grid__item','fila','separador');
	if (!in_array($componente['__type'], $no_padding_components )) {
		if ($background_styles || $border_styles || $box_shadows) array_push($classes_array, 'add-padding');
	}
	if ($componente['__type']=='columnas') {
		if ($background_styles || $border_styles || $box_shadows) array_push($classes_array, 'componente');
	}
	$style = ($style) ? 'style="'.$style.'"' : '';

	if (isset($componente['layout'])) {
		$layout = $componente['layout'];
		if ($layout == 'layout2' || $layout == 'layout3' || $layout == 'layout4') array_push($classes_array, 'full-width');
	}
	
	$class = generate_class_attribute($classes_array,$componente);
	$id = generate_id_attribute($componente);
	$animationAttrs = generate_animation_attributes($componente);

	$attributes = [ $id,$class,$style,$animationAttrs ];
	return implode(' ',array_filter($attributes));
}



function generate_actions_code($componente){
	$actions = (isset($componente['actions'])) ? $componente['actions'] : null;
	$code = '';

	if ( is_array($actions) && count($actions)>0 ):
			foreach ($actions as $action) {
				if ($action['trigger'] == 'click' && $action['action'] == 'open-page') {
					$link = NULL;
            		$enlace = $action['enlace'];
            		switch ($enlace['url_type']) {
            		    case 'externa':
            		        $link = $enlace['url'];
            		        break;
            		    
            		    case 'interna':
            		        $link = get_permalink( str_replace('post_','',$enlace['post']) );
            		        break;
            		}
            		if ($link != NULL):
                    	$target = ($enlace['new_tab'] == 1) ? '_blank' : ''; 
                    	$code = '<a class="cover-all" href="'.$link.'" target="'.$target.'"></a>';
               		endif;
				}
				if ($action['trigger'] == 'click' && $action['action'] == 'open-image-popup') { 
					if( $componente['__type'] == 'imagen' ){
						$link = wp_get_attachment_url($componente['image']);
						$link = (!empty($link)) ? $link : wp_get_attachment_url($componente['bgi']);
					} else {
						$link = wp_get_attachment_url($componente['bgi']);
					}
            		if ($link):
                    	$code = '<a class="cover-all zoom" href="'.$link.'"></a>';
               		endif;
				}
				if ($action['trigger'] == 'click' && $action['action'] == 'open-video-popup') {
					$videos = $componente['bgvideo'];
					$video_id = (is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
					if ($video_id):
						$video_url = wp_get_attachment_url($video_id);
			        	echo '<a class="cover-all zoom-video" href="'.$video_url.'"></a>';
			        endif;
				}
			}
	endif;

	return $code;
}