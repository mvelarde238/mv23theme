<?php
function oembed($post_content)
{
    $pattern = '@\s(http|https)://(www\.)?youtu[^\s]*@i';
    $matches = array();
    preg_match_all($pattern, $post_content, $matches);

    foreach ($matches[0] as $match) {
        $iframe = '<p><div class="video-responsive">'.wp_oembed_get($match).'</div></p>';
        $post_content = str_replace($match, $iframe, $post_content);
    }
    return $post_content;
};