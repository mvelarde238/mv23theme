<?php
use Theme\Page;

function body_style(){
	$style = '';
	
	$page_bgc = get_metadata(Page::getInstance()->get_type(), Page::getInstance()->get_id(),'page_bgc', true);

	if ( is_array($page_bgc) && array_key_exists('add_bgc', $page_bgc) ) {
		$style .= ($page_bgc['add_bgc']) ? 'background-color: '.$page_bgc['bgc'].';' : '';
	}

	if (!empty($style)) {
		$style = 'style="'.$style.'"';		
	}
	echo $style;
}