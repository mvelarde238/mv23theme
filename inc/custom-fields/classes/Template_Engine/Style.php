<?php
namespace Theme_Custom_Fields\Template_Engine;

use Theme_Custom_Fields\Template_Engine\Margin;
use Theme_Custom_Fields\Template_Engine\Borders;
use Theme_Custom_Fields\Template_Engine\Box_Shadow;
use Theme_Custom_Fields\Template_Engine\Background;

Class Style{
    /**
     * Return array of css data
     */
    public static function get_data( $args ){
        $styles = array();

        $margins = Margin::get_styles($args);
        if( !empty($margins) ) $styles = array_merge( $styles, $margins );

        $background = Background::get_styles($args);
        if( !empty($background) ) $styles = array_merge( $styles, $background );

        $borders = Borders::get_styles($args);
        if( !empty($borders) ) $styles = array_merge( $styles, $borders );

        $box_shadow = Box_Shadow::get_styles($args);
        if( !empty($box_shadow) ) $styles = array_merge( $styles, $box_shadow );

        if (isset($args['additional_styles']) && is_array($args['additional_styles']) && !empty($args['additional_styles'])){
            $styles = array_merge( $args['additional_styles'], $styles );
        } 

        return array(
            'all' => $styles,
            'attribute' => (!empty($styles)) ? 'style="'.implode(';', $styles).'"' : '',
            'margins' => $margins,
            'background' => $background,
            'borders' => $borders,
            'box_shadow' => $box_shadow
        );
    }
}