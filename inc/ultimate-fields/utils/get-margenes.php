<?php
function get_margenes($componente){
	$style = '';
	if (isset($componente['margin'])) {
		$style .= ($componente['margin']['top'] == 1) ? 'margin-top:0;' : '';  
		$style .= ($componente['margin']['bottom'] == 1) ? 'margin-bottom:0;' : '';  
		$style .= ($componente['margin']['left'] == 1) ? 'margin-left:0;' : '';  
		$style .= ($componente['margin']['right'] == 1) ? 'margin-right:0;' : ''; 
	}
	return $style; 
}