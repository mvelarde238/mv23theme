<?php
function ultimate_fields_page_content($id = null)
{
	$page_ID = $id;

	if ($page_ID != null) {
		
		$modulos = get_post_meta($page_ID, 'v23_modulos', true);

	} else { // is footer 
		$current_lang = (function_exists('pll_current_language')) ? pll_current_language() : '';
		if (!empty($current_lang) && $current_lang == 'en') {
			$modulos = get_option('footer_modules_en');
		} else {
			$modulos = get_option('footer_modules');
		}
	}

	$content_layout = get_post_meta($page_ID, 'content_layout', true);

	if (is_array($modulos) || is_array($content_layout)) :
		ob_start();
		if (is_array($modulos) && count($modulos) > 0) :
			foreach ($modulos as $modulo) :

				if ($modulo['__type'] == 'modulos') {
					print_module_view($modulo);
				}

				if ($modulo['__type'] == 'modulos-reusables') {
					$modulos_reusables = get_post_meta($modulo['seccion_reusable'], 'v23_modulos', true);
					if (is_array($modulos_reusables) && count($modulos_reusables) > 0) :
						foreach ($modulos_reusables as $modulo_reusable) {
							print_module_view($modulo_reusable);
						}
					endif;
				}

			endforeach;
		endif;

		if (is_array($content_layout) && count($content_layout) > 0) : ?>
			<div class="componente columnas-simples">
				<?php echo Content_Layout::the_content($content_layout); ?>
			</div>
		<?php endif;

		return ob_get_clean();
	endif;
}


function print_module_view($modulo)
{
	$visibility = (isset($modulo['visibility'])) ? $modulo['visibility'] : '';

	if ($visibility == 'is_private' && !current_user_can('administrator')) return;
	if ($visibility == 'user_is_logged_in' && !is_user_logged_in()) return;
	if ($visibility == 'user_is_not_logged_in' && is_user_logged_in()) return;

	// ***********************************************************************************************
	$layout = $modulo['layout'];
	$full_width_class = ($layout == 'layout2' || $layout == 'layout3') ? 'full-width' : '';
	$parallax = (isset($modulo['parallax']) && $modulo['parallax'] == 1) ? 'parallax' : '';

	$video_background = video_background($modulo);

	$classes_array = format_classes(array(
		'cf',
		'page-module',
		'editor-de-texto',
		$modulo['text_color'],
		$modulo['class'],
		$full_width_class,
		$parallax,
		$video_background['class']
	));

	$content_bg = (isset($modulo['content_bg']) && $modulo['content_bg'] == 1) ? true : false;
	if ($content_bg) {
		$content_bg_class = 'content-bg';
		if ($modulo['content_bg_boxed'] == 1) $content_bg_class .= ' content-bg--boxed';
		$content_bgc = $modulo['content_bgc'];
		$content_bgc_alpha = $modulo['content_bgc_alpha'];
		$content_bgc_rgba = hexToRgb($content_bgc, $content_bgc_alpha);
		$content_bg_style = 'background-color: rgba(' . $content_bgc_rgba . ');';
	}

	$style = '';
	$style = get_background_styles($modulo);

	if (isset($modulo['add_bgc'])) $style .= ($modulo['add_bgc'] && $modulo['bgc']) ? 'background-color: ' . $modulo['bgc'] . ';' : '';
	$style .= ($modulo['padding']['top'] == 1) ? 'padding-top:0;' : '';
	$style .= ($modulo['padding']['bottom'] == 1) ? 'padding-bottom:0;' : '';
	$style = ($style) ? 'style="' . $style . '"' : '';
	
	$class = generate_class_attribute($classes_array);
	$id = generate_id_attribute($modulo);
	$scrollAnimations = (SCROLL_ANIMATIONS) ? generate_scroll_animations($modulo) : '';

	$componentes = $modulo['componentes'];
	// if( is_array($componentes) && count($componentes)==0 ) return;
	?>
	<section <?= $id ?> <?= $class ?> <?= $style ?> <?= $scrollAnimations ?>>
		<?php if($video_background['code']) echo $video_background['code'] ?>
		<?php
		if ($layout == 'layout2') echo '<div class="container">';
		if ($content_bg) echo '<div class="' . $content_bg_class . '" style="' . $content_bg_style . '">';
		foreach ($componentes as $componente) {
			set_query_var('componente', $componente);
			get_template_part('/inc/ultimate-fields/componentes/views/' . $componente['__type']);
		}
		if ($content_bg) echo '</div>';
		if ($layout == 'layout2') echo '</div>';
		?>
	</section>
<?php
}



add_filter('the_content', function ($content) {
	ob_start();
	if ($content) echo '<div class="page-module"><div class="componente">' . $content . '</div></div>';
	// I would liketo add ultimate_fields_page_content() here but dosnt work
	$filtered_content = ob_get_clean();
	return $filtered_content;
}, 100);
