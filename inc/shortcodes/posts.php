<?php
function print_posts( $atts ){
    if( is_singular( 'archive_page' ) ) return '--posts--';

    if (have_posts()) :
        ob_start();
        echo '<div class="posts-listing posts-listing--style1">';
        while (have_posts()) : the_post();    
            $queried_object = get_queried_object();
            get_template_part( 'inc/partials/minipost', $queried_object->name );
        endwhile;
        echo '</div>';
        return ob_get_clean();
    endif;
}
add_shortcode( 'posts', 'print_posts' );