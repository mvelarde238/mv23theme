<?php
use Theme\Page;

add_filter( 'body_class', function( $classes ) {
    
    $page_color_scheme = get_metadata(Page::getInstance()->get_type(), Page::getInstance()->get_id(),'page_color_scheme', true);
	if ( $page_color_scheme && $page_color_scheme == 'dark-scheme' ) $classes[] = 'text-color-2';

    $hide_static_header = get_metadata(Page::getInstance()->get_type(), Page::getInstance()->get_id(),'hide_static_header', true);
	if ( $hide_static_header ) $classes[] = 'hide-static-header';

    $hide_sticky_header = get_metadata(Page::getInstance()->get_type(), Page::getInstance()->get_id(),'hide_sticky_header', true);
	if ( $hide_sticky_header ) $classes[] = 'hide-sticky-header';

    $disable_comments_styles = get_option( 'disable_comments_styles' );
	if ( $disable_comments_styles ) $classes[] = 'disable-comments-styles';
        
    return $classes;
});