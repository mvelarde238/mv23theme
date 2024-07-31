<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Animation{
    /**
     * Return html attribute
     */
    public static function get_attributes( $args ){
        $animation_attibutes = '';

        if( isset($args['add_animation']) && $args['add_animation'] ){
        	if (!isset($args['animation_delay'])) $args['animation_delay'] = '';
        	if (!isset($args['animation'])) $args['animation'] = '';
        	$animation = ( $args['animation'] != '' ) ? 'data-animation="'.$args['animation'].'"' : '';
        	$animation_delay = ( $args['animation_delay'] != '' ) ? 'data-delay="'.$args['animation_delay'].'"' : '';
        	$animation_attibutes = $animation.' '.$animation_delay;
        }

        return $animation_attibutes;
    }
}