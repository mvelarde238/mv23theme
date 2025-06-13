<?php
namespace Core\Builder\Template_Engine;

use Core\Builder\Template_Engine\Margin;
use Core\Builder\Template_Engine\Padding;
use Core\Builder\Template_Engine\Font;
use Core\Builder\Template_Engine\Borders;
use Core\Builder\Template_Engine\Box_Shadow;
use Core\Builder\Template_Engine\Background;
use Core\Builder\Template_Engine\Color;

Class Style{
    /**
     * Return array of css data
     */
    public static function get_data( $args ){
        $styles = array();
        
        $background = Background::get_styles($args);
        if( !empty($background) ) $styles = array_merge( $styles, $background );

        $color = Color::get_styles($args);
        if( !empty($color) ) $styles = array_merge( $styles, $color );
        
        $box_shadow = Box_Shadow::get_styles($args);
        if( !empty($box_shadow) ) $styles = array_merge( $styles, $box_shadow );

        $margins = Margin::get_styles($args);
        if( !empty($margins) ) $styles = array_merge( $styles, $margins );

        $paddings = Padding::get_styles($args);
        if( !empty($paddings) ) $styles = array_merge( $styles, $paddings );

        $borders = Borders::get_styles($args);
        if( !empty($borders) ) $styles = array_merge( $styles, $borders );

        $font = Font::get_styles($args);
        if( !empty($borders) ) $styles = array_merge( $styles, $font );

        if (isset($args['additional_styles']) && is_array($args['additional_styles']) && !empty($args['additional_styles'])){
            $styles = array_merge( $args['additional_styles'], $styles );
        } 

        return array(
            'all' => $styles,
            'attribute' => (!empty($styles)) ? 'style="'.implode(';', $styles).'"' : '',
            'margins' => $margins,
            'paddings' => $paddings,
            'background' => $background,
            'color' => $color,
            'borders' => $borders,
            'box_shadow' => $box_shadow,
            'font' => $font
        );
    }
}