<?php
function print_pagination( $atts ){
    if( is_singular( 'archive_page' ) ) return '--pagination--'; 
    if (have_posts()) :
        ob_start();
        get_template_part('partials/pagination');
        return ob_get_clean();
    endif;
}
add_shortcode( 'pagination', 'print_pagination' );