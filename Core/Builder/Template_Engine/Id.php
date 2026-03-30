<?php
namespace Core\Builder\Template_Engine;

Class Id{
    /**
     * Return id string
     */
    public static function get_id( $args ){
        $id = ( isset($args['settings']['id']) && $args['settings']['id'] != '' ) 
            ? $args['settings']['id']
            : null;

        if( isset($args['attributes']) && isset($args['attributes']['id']) ){
            $id = $args['attributes']['id'];
        } 

        return ( !empty($id) ) ? $id : '';
    }
}