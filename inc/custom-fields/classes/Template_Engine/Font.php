<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Font{
    /**
     * Return array of css font properties
     */
    public static function get_styles( $args ){
        $styles = array();
        $settings = ( isset($args['settings']) ) ? $args['settings'] : array();

	    if (
            isset( $settings['font_color'] ) &&              
            $settings['font_color']['color_scheme'] == 'custom' &&
            !empty($settings['font_color']['color']) ) {

	        $styles[] = 'color:'.$settings['font_color']['color'];
	    }      

        return $styles;
    }
}