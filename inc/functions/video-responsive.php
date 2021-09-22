<?php
add_filter( 'embed_oembed_html', 'tdd_oembed_filter', 10, 4 ); 

function tdd_oembed_filter($html, $url, $attr, $post_ID) {
    $return = '<div class="video-responsive">'.$html.'</div>';
    return $return;
}