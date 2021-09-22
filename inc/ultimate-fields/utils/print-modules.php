<?php
function ultimate_fields_page_content($id=null){ 
	$page_ID = $id;

	if ($page_ID != null) {

		if (get_post_type($page_ID) == 'seccion_reusable') {
			$section_type = get_post_meta( $page_ID,'section_type', true);
			if ($section_type == 'componente') {
				$componentes = get_post_meta( $page_ID,'componentes', true);
				$fake_module = array(
					'componentes' => $componentes,
					'__type' => 'modulos',
					'layout' => 'layout1',
					'text_color' => 'text-color-default',
					'class' => '',
					'bgi' => null,
					'padding' => array(
						'top' => 0,
						'bottom' => 0,
					),
				);
				$modulos = array( $fake_module );
			}
			if ($section_type == 'modulo') {
				$modulos = get_post_meta( $page_ID,'v23_modulos', true);
			}
		} else {
			$modulos = get_post_meta( $page_ID,'v23_modulos', true);
		}

	} else { // is footer 
		$current_lang = (function_exists('pll_current_language')) ? pll_current_language() : '';
		if (!empty($current_lang) && $current_lang == 'en') {
			$modulos = get_option( 'footer_modules_en' );
		} else {
			$modulos = get_option( 'footer_modules' );
		}
	}

	if( is_array($modulos) && count($modulos)>0 ):
		ob_start();
		foreach ($modulos as $modulo ) : 
			
			if ($modulo['__type']=='modulos'){
				print_module_view($modulo);
			}

			if ($modulo['__type']=='modulos-reusables'){
				$modulos_reusables = get_post_meta( $modulo['seccion_reusable'],'v23_modulos', true);
				if( is_array($modulos_reusables) && count($modulos_reusables)>0 ):
					foreach ($modulos_reusables as $modulo_reusable) {
						print_module_view($modulo_reusable);
					}
				endif;
			} 
			
		endforeach;
		return ob_get_clean();
	endif;
}


function print_module_view($modulo){
	$layout = $modulo['layout'];
	$full_width_class = ($layout == 'layout2' || $layout == 'layout3') ? 'full-width' : '';
	$parallax = ( isset($modulo['parallax']) && $modulo['parallax'] == 1 ) ? 'parallax' : '';

	$classes_array = format_classes(array(
		'cf',
		'page-module',
		'editor-de-texto',
		$modulo['text_color'],
		$modulo['class'],
		$full_width_class,
		$parallax
	));

	$content_bg = ( isset($modulo['content_bg'] ) && $modulo['content_bg'] == 1 ) ? true : false;
	if ($content_bg) {
		$content_bg_class = 'content-bg';
		if( $modulo['content_bg_boxed'] == 1 ) $content_bg_class .= ' content-bg--boxed'; 
		$content_bgc = $modulo['content_bgc'];
		$content_bgc_alpha = $modulo['content_bgc_alpha'];
		$content_bgc_rgba = hexToRgb($content_bgc, $content_bgc_alpha);
		$content_bg_style = 'background-color: rgba('.$content_bgc_rgba.');';
	}

	$style = '';
	$style = get_background_styles($modulo);

	if (isset($modulo['add_bgc'])) $style .= ($modulo['add_bgc'] && $modulo['bgc']) ? 'background-color: '.$modulo['bgc'].';' : '';
	$style .= ($modulo['padding']['top'] == 1) ? 'padding-top:0;' : '';  
	$style .= ($modulo['padding']['bottom'] == 1) ? 'padding-bottom:0;' : ''; 
	$style = ($style) ? 'style="'.$style.'"' : ''; 

	$class = generate_class_attribute($classes_array);
	$id = generate_id_attribute($modulo);

	$componentes = $modulo['componentes'];
	// if( is_array($componentes) && count($componentes)==0 ) return;
	?>
	<section <?=$id?> <?=$class?> <?=$style?>>
		<?php 
		if ($layout == 'layout2') echo '<div class="container">';
		if ($content_bg) echo '<div class="'.$content_bg_class.'" style="'.$content_bg_style.'">';
		foreach ($componentes as $componente ) { 
			set_query_var( 'componente', $componente );
			get_template_part('/inc/ultimate-fields/componentes/views/'.$componente['__type']);
		}
		if ($content_bg) echo '</div>';
		if ($layout == 'layout2') echo '</div>'; 
		?>
	</section>
	<?php 
}