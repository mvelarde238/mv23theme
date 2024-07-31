<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Margin{
    /**
     * Return array of css margins
     */
    public static function get_styles( $args ){
        $styles = array();

        if ( isset($args['delete_margins']) && $args['delete_margins'] && isset($args['margin']) ) {
            if ($args['margin']['top'] == 1) $styles[] = 'margin-top:0';  
            if ($args['margin']['bottom'] == 1) $styles[] = 'margin-bottom:0';  
            if ($args['margin']['left'] == 1) $styles[] = 'margin-left:0';  
            if ($args['margin']['right'] == 1) $styles[] = 'margin-right:0'; 
        }

        // page module use this
        if ( isset($args['delete_margins']) && $args['delete_margins'] && isset($args['padding']) ) {
            if ($args['padding']['top'] == 1) $styles[] = 'padding-top:0';  
            if ($args['padding']['bottom'] == 1) $styles[] = 'padding-bottom:0';  
        }

        return $styles;
    }
}