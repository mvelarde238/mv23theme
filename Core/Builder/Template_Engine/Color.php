<?php
namespace Core\Builder\Template_Engine;

Class Color{
    /**
     * Return array of css background properties
     */
    public static function get_styles( $args ){
        $styles = array();
        $settings = ( isset($args['settings']) ) ? $args['settings'] : array();

        if (
            isset( $settings['font_color'] ) &&              
            $settings['font_color']['color_scheme'] == 'dark_scheme' ) {

            $styles['color'] = "#ffffff";
        }    

	    if (
            isset( $settings['font_color'] ) &&              
            // $settings['font_color']['color_scheme'] == 'custom' &&
            !empty($settings['font_color']['color']) ) {

	        $styles['color'] = $settings['font_color']['color'];
	    }

        return $styles;
    }
}