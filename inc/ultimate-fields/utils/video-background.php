<?php
function video_background($componente){
	$video_background = array( 'url' => null, 'code' => null, 'class' => '' );

    $video_source = ( isset($componente['video_source']) ) ? $componente['video_source'] : 'selfhosted';
    $video_opacity = (isset($componente['video_opacity']) && $componente['video_opacity'] ) ? $componente['video_opacity'] : 100;
    $video_style = ($video_opacity != 100) ? 'style="opacity:'.($video_opacity/100).';"' : ''; 

    if( $video_source == 'selfhosted' ){
        $videos = ( isset($componente['bgvideo']) ) ? $componente['bgvideo'] : array();
        $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
        if($video_id) {
        	$video_url = wp_get_attachment_url($video_id);
            if($video_url){
                $video_background['url'] = $video_url;
                $video_background['class'] = 'has-video-background';
                $video_background['code'] = '<video '.$video_style.' width="100%" loop muted="muted" autoplay><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
            }
        }
    }

    if( $video_source == 'youtube' ){
        $video_url = $componente['youtube_url'];
        if( $video_url ){
            $video_background['url'] = $video_url;
            $video_background['class'] = 'has-video-background';
            preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $video_url, $matches);
            $video_id = $matches[1]; //should contain the youtube id
            $video_background['code'] = '<iframe '.$video_style.' src="https://www.youtube.com/embed/'.$video_id.'?controls=0&mute=1&showinfo=0&rel=0&autoplay=1&loop=1" frameborder="0" allowfullscreen></iframe>';
        }
    }

	return $video_background;
}