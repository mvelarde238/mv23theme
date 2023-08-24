<?php
if (!function_exists('get_main_color')) {
    function get_main_color(){
        return MAIN_COLOR;
    }
}

if (!function_exists('get_secondary_color')) {
    function get_secondary_color(){
        return SECONDARY_COLOR;
    }
}

if (!function_exists('get_tertiary_color')) {
    function get_tertiary_color(){
        return TERTIARY_COLOR;
    }
}

if (!function_exists('get_taxonomy_by_term_id')) {
    function get_taxonomy_by_term_id( $term_id ) {
    
    	// We can't get a term if we don't have a term ID.
    	if ( 0 === $term_id || null === $term_id ) {
    		return;
    	}
    
    	// Grab the term using the ID then read the name from the associated taxonomy.
    	$taxonomy = '';
    	$term = get_term( $term_id );
    	if ( !is_wp_error($term) ) {
    		$taxonomy = $term->taxonomy;
    	}
    
    	return trim( $taxonomy );
    }
}

if(!function_exists('scroll_animation_is_active')){
    function scroll_animation_is_active(){
        $is_active = false;
        $the_option = get_option('scroll_animations');
        if($the_option) $is_active = $the_option['activate'];
        return $is_active;
    }
}

if(!function_exists('scroll_indicators_is_active')){
    function scroll_indicators_is_active(){
        $is_active = false;
        $the_option = get_option('scroll_animations');
        if($the_option) $is_active = $the_option['activate_indicators'];
        return $is_active;
    }
}

if(!function_exists('get_video_details_from_url')){
    function get_video_details_from_url( $url ){
        $video_details = array();

        if( !empty($url) ){
            if ( (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) ) {
                $source = 'youtube';
                $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
            } 
            else if( strpos($url, 'vimeo.com') !== false ){
                $source = 'vimeo';
                $pattern = '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im';
            }
            else if( strpos($url, 'dailymotion.com') !== false ){
                $source = 'dailymotion';
                $pattern = '/\b(?:dailymotion)\.com\b/i';
            } else {
                $source = null;
                $pattern = null;
            }
            
            if( $pattern ){
                preg_match($pattern, $url, $matches);
                $video_details['id'] = $matches[count($matches)-1];
                $video_details['source'] = $source;
            }
        }
        return $video_details;
    }
}