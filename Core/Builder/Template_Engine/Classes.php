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

        if (isset($args['classes']) && is_array($args['classes']) && !empty($args['classes'])){
            $classes = array_merge( $args['classes'], $classes );
        }
        
        if (isset($args['additional_classes']) && is_array($args['additional_classes']) && !empty($args['additional_classes'])){
            $classes = array_merge( $args['additional_classes'], $classes );
        }

        if( isset($args['settings']['classes']) && !empty($args['settings']['classes']) ){
            $classes = array_merge( $classes, explode(' ', $args['settings']['classes']) );
        } 

        if (isset($args['theme_clases']) && !empty($args['theme_clases']) && $args['theme_clases'][0] != '' ){
            $classes = array_merge($classes, $args['theme_clases']);
        }

        if (isset($args['settings']['helpers']) && !empty($args['settings']['helpers']['list']) ){
            $classes = array_merge($classes, $args['settings']['helpers']['list']);
        }

        if (isset($args['settings']['hide_on'])){
            $responsive = $args['settings']['hide_on'];
            if( isset($responsive['desktop']) && $responsive['desktop'] ) $classes[] = 'hide-on-large-only';
            if( isset($responsive['tablet']) && $responsive['tablet'] ) $classes[] = 'hide-on-med-only';
            if( isset($responsive['mobile']) && $responsive['mobile'] ) $classes[] = 'hide-on-small-only';
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
                $text_color = 'dark-mode';
                break;
            
            case 'default_scheme':
                $text_color = 'light-mode';
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