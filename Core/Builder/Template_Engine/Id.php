<?php
namespace Core\Builder\Template_Engine;

Class Id{
    /**
     * Return id string
     */
    public static function get_id( $args ){
        $id = ( isset($args['settings']['main_attributes']) && isset($args['settings']['main_attributes']['id']) ) 
            ? $args['settings']['main_attributes']['id']
            : null;

        if( isset($args['__gjsAttributes']) && isset($args['__gjsAttributes']['id']) ){
            $id = $args['__gjsAttributes']['id'];
        } 

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