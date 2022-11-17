<?php
function generate_animation_attributes($componente){
	$animation_attibutes = '';
	if (!isset($componente['add_animation'])) $componente['add_animation'] = false;

	if( $componente['add_animation'] ){
		if (!isset($componente['animation_delay'])) $componente['animation_delay'] = '';
		if (!isset($componente['animation'])) $componente['animation'] = '';
		$animation = ( $componente['animation'] != '' ) ? 'data-animation="'.$componente['animation'].'"' : '';
		$animation_delay = ( $componente['animation_delay'] != '' ) ? 'data-delay="'.$componente['animation_delay'].'"' : '';
		$animation_attibutes = $animation.' '.$animation_delay;
	}

	return $animation_attibutes;
}