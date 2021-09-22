<?php
if (!function_exists('get_tinymce_custom_colors')) {
	function get_tinymce_custom_colors(){
		$main_color = str_replace('#', '', MAIN_COLOR);
		$secondary_color = str_replace('#', '', SECONDARY_COLOR);
		$tertiary_color = str_replace('#', '', TERTIARY_COLOR);
		return '"'.$main_color.'", "Corporativo 1","'.$secondary_color.'", "Corporativo 2","'.$tertiary_color.'", "Corporativo 3"';
	}
}