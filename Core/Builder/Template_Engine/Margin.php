<?php
namespace Core\Builder\Template_Engine;

Class Margin{
    /**
     * Return array of css margins
     */
    public static function get_styles( $args ){
        $styles = array();
        $settings = ( isset($args['settings']) ) ? $args['settings'] : array();

        if ( isset($settings['margin'])  ) {
            $margin_settings = $settings['margin'];

            if ( $margin_settings['top'] != '' ) $styles[] = 'margin-top:'.$margin_settings['top'].'px';  
            if ( $margin_settings['bottom'] != '' ) $styles[] = 'margin-bottom:'.$margin_settings['bottom'].'px';  
            if ( $margin_settings['left'] != '' ) $styles[] = 'margin-left:'.$margin_settings['left'].'px';  
            if ( $margin_settings['right'] != '' ) $styles[] = 'margin-right:'.$margin_settings['right'].'px'; 
        }

        return $styles;
    }
}