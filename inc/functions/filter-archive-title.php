<?php
add_filter( 'get_the_archive_title', function ($title) {

	if ( is_category() ) {

		$title = single_cat_title( '', false );

	} elseif ( is_tag() ) {

		// $title = single_tag_title( '', false );
		return $title;

	} elseif ( is_author() ) {

		// $title = '<span class="vcard">' . get_the_author() . '</span>' ;
		return $title;
	}

    return $title;
});