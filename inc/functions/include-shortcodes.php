<?php
get_template_part( 'inc/shortcodes/icono' );
get_template_part( 'inc/shortcodes/redes-sociales' );
get_template_part( 'inc/shortcodes/accordion' );
get_template_part( 'inc/shortcodes/posts' );
get_template_part( 'inc/shortcodes/pagination' );
get_template_part( 'inc/shortcodes/posts-filter' );
get_template_part( 'inc/shortcodes/gallery' );
get_template_part( 'inc/shortcodes/comments-area' );

if (IS_MULTILANGUAGE) {
	get_template_part( 'inc/shortcodes/idiomas' );
}

if (WOOCOMMERCE_IS_ACTIVE) {
	get_template_part( 'inc/shortcodes/open-minicart' );
}