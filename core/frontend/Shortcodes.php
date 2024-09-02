<?php
namespace Core\FrontEnd;

class Shortcodes{
    public function __construct(){}

    public function init(){
        get_template_part( 'partials/shortcodes/icono' );
        get_template_part( 'partials/shortcodes/redes-sociales' );
        get_template_part( 'partials/shortcodes/accordion' );
        get_template_part( 'partials/shortcodes/posts' );
        get_template_part( 'partials/shortcodes/pagination' );
        get_template_part( 'partials/shortcodes/posts-filter' );
        get_template_part( 'partials/shortcodes/gallery' );
        get_template_part( 'partials/shortcodes/comments-area' );

        if (IS_MULTILANGUAGE) {
        	get_template_part( 'partials/shortcodes/idiomas' );
        }

        if (WOOCOMMERCE_IS_ACTIVE) {
        	get_template_part( 'partials/shortcodes/open-minicart' );
        }
    }
}