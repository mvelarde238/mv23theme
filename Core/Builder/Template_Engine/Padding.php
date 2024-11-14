<?php
namespace Core\Builder\Template_Engine;

Class Padding{
    /**
     * Return array of css paddings
     */
    public static function get_styles( $args ){
        $styles = array();

        if ( isset($args['settings']['padding'])  ) {
            $padding_settings = $args['settings']['padding'];
            $paddings = array('top','right','bottom','left');

            foreach ($paddings as $key) {
                if ( $padding_settings[$key] != '' ) $styles[] = 'padding-'.$key.':'.self::format_padding_value($padding_settings[$key]);  
            }
        }

        return $styles;
    }

    private static function format_padding_value( $value ){
        $formatted_value = ( self::isNumeric($value) ) ? $value.'px' : $value;
        return $formatted_value;
    }

    private static function isNumeric($str){
        return preg_match('/^[0-9]+$/', $str);
    }
}