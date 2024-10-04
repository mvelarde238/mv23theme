<?php
namespace Core\Builder\Template_Engine;

Class Padding{
    /**
     * Return array of css paddings
     */
    public static function get_styles( $args ){
        $styles = array();

        if ( isset($args['settings']['padding'])  ) {
            $padding_settings = $args['settings']['padding'];

            if ( $padding_settings['top'] != '' ) $styles[] = 'padding-top:'.$padding_settings['top'].'px';  
            if ( $padding_settings['bottom'] != '' ) $styles[] = 'padding-bottom:'.$padding_settings['bottom'].'px';  
            if ( $padding_settings['left'] != '' ) $styles[] = 'padding-left:'.$padding_settings['left'].'px';  
            if ( $padding_settings['right'] != '' ) $styles[] = 'padding-right:'.$padding_settings['right'].'px'; 
        }

        return $styles;
    }
}