<?php
function video_background($componente){
	$video_background = array( 'url' => null, 'code' => null, 'class' => '' );

    $video_source = ( isset($componente['video_source']) ) ? $componente['video_source'] : 'selfhosted';
    $video_type = ( isset($componente['video_type']) ) ? $componente['video_type'] : 'popable';
    $class = 'has-video-background';

    $video_settings = (isset($componente['video_settings'])) ? $componente['video_settings'] : array(
        'loop' => 0,
        'muted' => 0,
        'autoplay' => 0,
        'opacity' => 100
    );

    $video_opacity = (isset($componente['video_opacity']) && $componente['video_opacity'] ) ? $componente['video_opacity'] : $video_settings['opacity'];
    $video_style = ($video_opacity != 100) ? 'style="opacity:'.($video_opacity/100).';"' : ''; 

    if( $video_source == 'selfhosted' ){
        $videos = ( isset($componente['bgvideo']) ) ? $componente['bgvideo'] : array();
        $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
        if($video_id) {
        	$video_url = wp_get_attachment_url($video_id);
            if($video_url){
                $poster = ( $videos['poster'] ) ? wp_get_attachment_url( $videos['poster'] ) : null;
                $video_background['url'] = $video_url;
                $video_background['class'] = $class;
                $video_background['code'] = '<video '.$video_style;
                if( $video_type == 'playable' ) $video_background['code'] .= ' controls';
                if( $video_settings['muted'] ) $video_background['code'] .= ' muted="muted"';
                if( $video_settings['loop'] ) $video_background['code'] .= ' loop';
                if( $poster ) $video_background['code'] .= ' poster="'.$poster.'"';
                if( $video_settings['autoplay'] ) $video_background['code'] .= ' autoplay';
                $video_background['code'] .= '><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
            }
        }
    }

    if( $video_source == 'youtube' ){
        $video_url = $componente['youtube_url'];
        if( $video_url ){
            $video_background['url'] = $video_url;
            $video_background['class'] = $class;

            $youtube_pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
            preg_match($youtube_pattern, $video_url, $matches);
            $video_id = $matches[count($matches)-1];

            $video_background['code'] = '<iframe '.$video_style.' src="https://www.youtube.com/embed/'.$video_id.'?showinfo=0&rel=0';
            if( $video_type != 'playable' ) $video_background['code'] .= '&controls=0';
            if( $video_settings['muted'] ) $video_background['code'] .= '&mute=1';
            if( $video_settings['autoplay'] ) $video_background['code'] .= '&autoplay=1';
            if( $video_settings['loop'] ) $video_background['code'] .= '&loop=1';
            $video_background['code'] .= '" frameborder="0" allowfullscreen></iframe>';
        }
    }

	return $video_background;
}