<?php
 
/**
 * Get Post object by post_meta query
 *
 * @use         $post = get_post_by_meta( array( meta_key = 'page_name', 'meta_value = 'contact' ) )
 * @since       1.0.4
 * @return      Object      WP post object
 */
function get_post_by_meta( $args = array() )
{
   
    // Parse incoming $args into an array and merge it with $defaults - caste to object ##
    $args = ( object )wp_parse_args( $args );
   
    // grab page - polylang will take take or language selection ##
    $args = array(
        'meta_query'        => array(
            array(
                'key'       => $args->meta_key,
                'value'     => $args->meta_value
            )
        ),
        'post_type'         => 'location',
        'posts_per_page'    => '1'
    );
   
    // run query ##
    $posts = get_posts( $args );
   
    // check results ##
    if ( ! $posts || is_wp_error( $posts ) ) return false;
   
    // test it ##
    #pr( $posts[0] );
   
    // kick back results ##
    return $posts[0];
   
}