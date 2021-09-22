<?php
function generate_animation_attributes($componente){
	if (!isset($componente['animation_delay'])) $componente['animation_delay'] = '';
	if (!isset($componente['animation'])) $componente['animation'] = '';
	$animation = ( $componente['animation'] != '' ) ? 'data-animation="'.$componente['animation'].'"' : '';
	$animation_delay = ( $componente['animation_delay'] != '' ) ? 'data-delay="'.$componente['animation_delay'].'"' : '';

	return $animation.' '.$animation_delay;
}