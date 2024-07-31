<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Id{
    /**
     * Return id string
     */
    public static function get_id( $args ){
        return (isset($args['module_id']) && !empty($args['module_id'])) ? $args['module_id'] : '';
    }

    /**
     * Return html attribute
     */
    public static function get_attribute( $args ){
        $id = self::get_id( $args );
        return ( $id ) ? 'id="'.$id.'"' : '';
    }
}