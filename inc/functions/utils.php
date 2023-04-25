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