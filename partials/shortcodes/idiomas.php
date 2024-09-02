<?php
function print_idiomas( $atts ){
	$a = shortcode_atts( array(
		'class' => 'style1',
		'text' => ''
	), $atts );
	ob_start();
	?>
	<div class="idiomas <?php echo $a['class']; ?>">
		<?php 
		if (function_exists('pll_the_languages')) {

			if ($a['text']) echo '<span class="idiomas__desc">'.$a['text'].'</span>';

			$langs = pll_the_languages(array( 'raw' => 1 )); 

			if (!empty($langs)) :
				echo '<ul>';
				foreach ($langs as $lang) :
					$slug_locale = $lang['slug'] . '_' . strtoupper($lang['slug']); // DEVUELVE MAL EN INGLES en por us
					$flag = $lang['flag'];
					$name = $lang['slug'];
					$url = $lang['url'];
					$class = ($lang['current_lang'] == $slug_locale) ? 'active' : '';

					echo '<li>';
					echo '<a lang="'.$slug_locale.'" hreflang="'.$slug_locale.'" href="'.$url.'" class="'.$class.'">';
					echo $name;
					// echo $lang['slug'];
					// echo '<img src="'.$flag.'" alt="'.$slug_locale.'">';
					echo '</a>';
					echo '</li>';
				endforeach;
				echo '</ul>';
			endif;
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'idiomas', 'print_idiomas' );