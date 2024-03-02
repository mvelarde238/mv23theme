<?php
// Add specific CSS class by filter.
add_filter( 'body_class', function( $classes ) {

    $page_color_scheme = get_metadata(Page::getInstance()->get_type(), Page::getInstance()->get_id(),'page_color_scheme', true);

	if ( $page_color_scheme && $page_color_scheme == 'dark-scheme' ) {
        return array_merge( $classes, array( 'text-color-2' ) );
    } else {
        return $classes;
    }
});