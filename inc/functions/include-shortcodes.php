<?php
get_template_part( 'inc/shortcodes/icono' );
get_template_part( 'inc/shortcodes/redes-sociales' );
get_template_part( 'inc/shortcodes/accordion' );
get_template_part( 'inc/shortcodes/posts' );
get_template_part( 'inc/shortcodes/pagination' );
get_template_part( 'inc/shortcodes/posts-filter' );
get_template_part( 'inc/shortcodes/gallery' );

if (IS_MULTILANGUAGE) {
	get_template_part( 'inc/shortcodes/idiomas' );
}

if (!function_exists('mv23_include_shortcodes')) {
	function mv23_include_shortcodes(){ return; }
}