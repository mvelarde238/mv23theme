<?php
function print_pagination( $atts ){
    if( is_singular( 'archive_page' ) ) return '--pagination--'; 
    if (have_posts()) :
        ob_start();
        mv23_page_navi();
        return ob_get_clean();
    endif;
    // $page_ID = get_the_ID();
    // $posttype = get_post_meta( $page_ID, 'archive_posttype', true );
    // $args_query = array( 'post_type' => $posttype, 'posts_per_page' => 9, 'order'=>'DESC', 'paged' => get_query_var('paged') );
    // $query = new WP_Query( $args_query ); 
    // ob_start();
    // mv23_page_navi($query);
    // return ob_get_clean();
}
add_shortcode( 'pagination', 'print_pagination' );