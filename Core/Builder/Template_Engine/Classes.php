<?php
namespace Core\Builder\Template_Engine;

Class Classes{
    /**
     * Return array of classes
     */
    public static function get_classes( $args ){
        $classes = array();

        if( isset($args['__type']) && $args['__type'] != '' && gettype($args['__type']) == 'string' ){
            $classes[] = str_replace('_','-',$args['__type']);
        }
        
        if (isset($args['additional_classes']) && is_array($args['additional_classes']) && !empty($args['additional_classes'])){
            $classes = array_merge( $args['additional_classes'], $classes );
        }

        if( isset($args['settings']['main_attributes']) && isset($args['settings']['main_attributes']['class']) ){
            $classes = array_merge( $classes, explode(' ', $args['settings']['main_attributes']['class']) );
        } 

        if (isset($args['theme_clases']) && !empty($args['theme_clases']) && $args['theme_clases'][0] != '' ){
            $classes = array_merge($classes, $args['theme_clases']);
        }

        if (isset($args['settings']['helpers']) && !empty($args['settings']['helpers']['list']) ){
            $classes = array_merge($classes, $args['settings']['helpers']['list']);
        }

        if (isset($args['settings']['responsive'])){
            $responsive = $args['settings']['responsive'];
            if( isset($responsive['hide_on_desktop']) && $responsive['hide_on_desktop'] ) $classes[] = 'hide-on-large-only';
            if( isset($responsive['hide_on_tablet']) && $responsive['hide_on_tablet'] ) $classes[] = 'hide-on-med-only';
            if( isset($responsive['hide_on_mobile']) && $responsive['hide_on_mobile'] ) $classes[] = 'hide-on-small-only';
        } 

        if (isset($args['settings']['layout'])) {
            $layout = $args['settings']['layout']['key'];
            if ( !empty($layout) && ($layout == 'layout2' || $layout == 'layout3') ) $classes[] = 'full-width';
        }
        
        $color_scheme = self::get_color_scheme( $args );
        if ( $color_scheme ) $classes[] = $color_scheme;

        return $classes;
    }

    /**
     * Return color scheme string
     */
    public static function get_color_scheme($args){
        $color_scheme = ( isset($args['settings']['font_color']) ) ? $args['settings']['font_color']['color_scheme'] : '';
    
        switch ($color_scheme) {
            case 'dark_scheme':
                $text_color = 'text-color-2';
                break;
            
            case 'default_scheme':
                $text_color = 'text-color-1';
                break;
    
            default:
                $text_color = null;
                break;
        }
    
        return $text_color;
    }

    /**
     * Return html attribute
     */
    public static function get_attribute( $args ){
        $classes = self::get_classes( $args );
        return (!empty($classes)) ? 'class="'.implode(' ', $classes).'"' : '';
    }
}