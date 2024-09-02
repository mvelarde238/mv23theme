<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Id{
    /**
     * Return id string
     */
    public static function get_id( $args ){
        $id = ( isset($args['settings']['main_attributes']) && isset($args['settings']['main_attributes']['id']) ) 
            ? $args['settings']['main_attributes']['id']
            : null;
        return ( !empty($id) ) ? $id : '';
    }

    /**
     * Return html attribute
     */
    public static function get_attribute( $args ){
        $id = self::get_id( $args );
        return ( $id ) ? 'id="'.$id.'"' : '';
    }
}