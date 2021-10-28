<?php
function print_posts( $atts ){
    if( is_singular( 'archive_page' ) ) return '--posts--';

    if(is_tax()){
        $queried_object = get_taxonomy(get_queried_object()->taxonomy)->object_type;
        $object_type = $queried_object[0];
    } else {
        $queried_object = get_queried_object();
        $object_type = $queried_object->name;
    }

    if (have_posts()) :
        ob_start();
        echo '<div class="posts-listing posts-listing--style1">';
        while (have_posts()) : the_post();    
            get_template_part( 'inc/partials/minipost', $object_type );
        endwhile;
        echo '</div>';
        return ob_get_clean();
    endif;
}
add_shortcode( 'posts', 'print_posts' );