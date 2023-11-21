<?php
function oembed($post_content)
{
    $matches = array();
    $patterns = array(
        '@(?<!href=["\'])((http|https)://(www\.)?youtu[^\s"]+)@i',
        '@(?<!href=["\'])((http|https)://(www\.)?vimeo[^\s"]+)@i'
    );

    foreach ($patterns as $patern) {
        preg_match_all($patern, $post_content, $matches);
        
        foreach ($matches[0] as $match) {
            $iframe = '<p><div class="video-responsive">'.wp_oembed_get($match).'</div></p>';
            $post_content = str_replace($match, $iframe, $post_content);
        }
    }
    return $post_content;
};