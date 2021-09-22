<?php
function v23_exclude_categories( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'category__not_in', array( 3, 4, 7 ) );
    }
}
add_action( 'pre_get_posts', 'v23_exclude_categories' );