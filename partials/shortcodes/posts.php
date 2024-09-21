<?php
function print_posts(){
    if( is_singular( 'archive_page' ) ) return '--posts--';

    ob_start();
    get_template_part('partials/loop');
    return ob_get_clean();
}
add_shortcode( 'posts', 'print_posts' );