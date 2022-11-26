<?php
function video_background($componente){
	$video_background = array( 'url' => null, 'code' => null, 'class' => '' );

    $videos = ( isset($componente['bgvideo']) ) ? $componente['bgvideo'] : array();
    $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
    if($video_id) {
    	$video_url = wp_get_attachment_url($video_id);
        if($video_url){
            $video_opacity = (isset($componente['video_opacity']) && $componente['video_opacity'] ) ? $componente['video_opacity'] : 100;
            $video_style = ($video_opacity != 100) ? 'style="opacity:'.($video_opacity/100).';"' : ''; 

            $video_background['url'] = $video_url;
            $video_background['class'] = 'has-video-background';
            $video_background['code'] = '<video '.$video_style.' width="100%" loop muted="muted" autoplay><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
        }
    }

	return $video_background;
}