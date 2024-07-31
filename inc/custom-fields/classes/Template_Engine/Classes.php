<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Classes{
    /**
     * Return array of classes
     */
    public static function get_classes( $args ){
        $classes = array();

        if( isset($args['__type']) && $args['__type'] != '' && gettype($args['__type']) == 'string' ){
            $classes[] = $args['__type'];
        }
        
        if (isset($args['additional_classes']) && is_array($args['additional_classes']) && !empty($args['additional_classes'])){
            $classes = array_merge( $args['additional_classes'], $classes );
        } 

        if (isset($args['class']) && !empty($args['class']) && gettype($args['class']) == 'string'){
            $classes = array_merge( $classes, explode(' ', $args['class']) );
        }

        if (isset($args['theme_clases']) && !empty($args['theme_clases']) && $args['theme_clases'][0] != '' ){
            $classes = array_merge($classes, $args['theme_clases']);
        } 

        if (isset($args['layout'])) {
            $layout = $args['layout'];
            if ( !empty($layout) && $layout != 'layout1') $classes[] = 'full-width';
        }

        if( isset($args['video_background_data']) ){
            $classes[] = $args['video_background_data']['class'];
        }
        
        $color_scheme = self::get_color_scheme( $args );
        if ( $color_scheme ) $classes[] = $color_scheme;

        return $classes;
    }

    /**
     * Return color scheme string
     */
    public static function get_color_scheme($args){
        $color_scheme = (array_key_exists('color_scheme', $args)) ? $args['color_scheme'] : '';

        // page module is using this:

        if( isset($args['text_color']) ) $color_scheme = $args['text_color'];
    
        switch ($color_scheme) {
            case 'text-color-2':
            case 'dark-scheme':
                $text_color = 'text-color-2';
                break;
            
            case 'text-color-default':
            case 'default-scheme':
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