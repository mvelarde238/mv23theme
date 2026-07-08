<?php
namespace Core\Builder\Template_Engine;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

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

        if (isset($args['classes']) && is_array($args['classes']) && !empty($args['classes'])){
            foreach ($args['classes'] as $class) {
                if (is_string($class) && !empty($class)) {
                    $classes[] = $class;
                }
            }
        }

        if( isset($args['settings']['classes']) && !empty($args['settings']['classes']) ){
            $additional_classes = Handlebars::parse($args['settings']['classes']);
            $classes = array_merge( $classes, explode(' ', $additional_classes) );
        } 

        if( isset($args['settings']['utility_classes']) && is_array($args['settings']['utility_classes']) && !empty($args['settings']['utility_classes']) ){
            $classes = array_merge( $classes, $args['settings']['utility_classes'] );
        }

        if (isset($args['settings']['color_scheme']) && !empty($args['settings']['color_scheme']['key']) ){
            $classes[] = $args['settings']['color_scheme']['key'];
        }

        if (isset($args['settings']['hide_on'])){
            $responsive = $args['settings']['hide_on'];
            if( isset($responsive['desktop']) && $responsive['desktop'] ) $classes[] = 'hide-on-large-only';
            if( isset($responsive['tablet']) && $responsive['tablet'] ) $classes[] = 'hide-on-med-only';
            if( isset($responsive['mobile']) && $responsive['mobile'] ) $classes[] = 'hide-on-small-only';
        }

        // Remove duplicate/empty classes and preserve order
        $classes = self::remove_duplicate_classes( $classes );

        return $classes;
    }

    /**
     * Remove duplicate and empty classes from array while preserving order
     *
     * @param array $classes
     * @return array
     */
    public static function remove_duplicate_classes( $classes ){
        if ( ! is_array( $classes ) ) return array();

        $unique = array();
        foreach ( $classes as $c ) {
            if ( ! is_string( $c ) ) continue;
            $c = trim( $c );
            if ( $c === '' ) continue;
            if ( ! in_array( $c, $unique, true ) ) {
                $unique[] = $c;
            }
        }

        return $unique;
    }
}