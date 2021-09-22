<?php
function get_color_scheme($componente){
	$color_scheme = (array_key_exists('color_scheme', $componente)) ? $componente['color_scheme'] : '';

	switch ($color_scheme) {
	    case 'dark-scheme':
	        $text_color = 'text-color-2';
	        break;
	    
	    case 'default-scheme':
	        $text_color = 'text-color-1';
	        break;

	    default:
			$text_color = '';
	    	break;
	}

	return $text_color;
}