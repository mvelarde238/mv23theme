<?php
function oembed($post_content) { 
    parse_str( parse_url( $post_content, PHP_URL_QUERY ),  $matches);

    if (!empty($matches['v'])) { 
        $post_content = '<div class="video-responsive"><object>';
        $post_content .= '<param name="movie" value="http://www.youtube.com/v/' . $matches['v'] . '&hl=en_US&fs=1&"></param>';
        $post_content .= '<param name="allowFullScreen" value="true"></param>';
        $post_content .= '<param name="allowscriptaccess" value="always"></param>';
        $post_content .= '<embed src="http://www.youtube.com/v/' . $matches['v'] . '&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="415" height="250"></embed>';
        $post_content .= '</object></div>';
        $post_content = $post_content;
    }
    return $post_content;
};