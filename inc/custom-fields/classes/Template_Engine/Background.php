<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Background{
    /**
     * Return array of css background properties
     */
    public static function get_styles( $args ){
        $styles = array();

	    if (isset($args['color_de_fondo']) && $args['color_de_fondo']['add_bgc']) {
	        $styles[] = 'background-color:'.$args['color_de_fondo']['bgc'];
	    }

        // page module is using this:
        if (isset($args['add_bgc']) && $args['add_bgc'] && $args['bgc']){
            $styles[] ='background-color: ' . $args['bgc'];
        } 
        
        $bgi_url = (isset($args['bgi']) && $args['bgi'] != '') ? wp_get_attachment_url($args['bgi']) : null;
        if( $bgi_url ){
            $styles[] = 'background-image:url('.$bgi_url.')';
            if ($args['bgi_options']['repeat'] != 'repeat') $styles[] = 'background-repeat: '.$args['bgi_options']['repeat'];
            if ($args['bgi_options']['size'] != 'auto') $styles[] = 'background-size: '.$args['bgi_options']['size'];
            if ($args['bgi_options']['position_x'] != 'left') $styles[] = 'background-position-x: '.$args['bgi_options']['position_x'];
            if ($args['bgi_options']['position_y'] != 'top') $styles[] = 'background-position-y: '.$args['bgi_options']['position_y'];
        }

        return $styles;
    }
}