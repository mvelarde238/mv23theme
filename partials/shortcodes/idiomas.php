<?php
function print_idiomas( $atts ){
	$a = shortcode_atts( array(
		'class' => '',
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

function custom_lang_switcher( $atts ) {
	$output = '';
	if ( function_exists( 'pll_the_languages' ) ) {
		$args = shortcode_atts( array(
			'class'                  => '',
			'echo'                   => 0,
			'show_flags'             => 0,
			'show_names'             => 1,
			'dropdown'               => 0,
			'hide_if_empty'          => 1,
			'display_names_as'       => 'name', // Whether to display the language name or its slug, valid options are 'slug' and 'name'
			'force_home'             => 0,
			'hide_if_no_translation' => 0, 
			'hide_current'           => 0,
			'post_id'                => null,
			'raw'                    => 0, // Return a raw array instead of html markup if set to 1
			'item_spacing'           => 'preserve', //Whether to preserve or discard whitespace between list items, valid options are 'preserve' and 'discard'
			'admin_render'           => 0, // Allows to force the current language code in an admin context if set, default to 0. Need to set the admin_current_lang argument below.
			'admin_current_lang'     => null, // The current language code in an admin context. Need to set the admin_render to 1, defaults not set.
			// 'classes'                => array(), // A list of CSS classes to set to each elements outputted.
			// 'link_classes'           => array() // A list of CSS classes to set to each link outputted.
		), $atts );

		$output = ($args['dropdown']) ? '<div ' : '<ul ';
		$output .= 'class="polylang_langswitcher '.$args['class'].'">'.pll_the_languages( $args );
		$output .= ($args['dropdown']) ? '</div>' : '</ul>';
	}

	return $output;
}

add_shortcode( 'lang_switcher', 'custom_lang_switcher' );