<?php
namespace Core\Builder\Template_Engine;

Class Margin{
    /**
     * Return array of css margins
     */
    public static function get_styles( $args ){
        $styles = array();
        $settings = ( isset($args['settings']) ) ? $args['settings'] : array();

        if ( isset($settings['margin'])  ) {
            $margin_settings = $settings['margin'];
            $margins = array('top','right','bottom','left');

            foreach ($margins as $key) {
                if ( $margin_settings[$key] != '' ) $styles[] = 'margin-'.$key.':'.self::format_margin_value($margin_settings[$key]);  
            }
        }

        return $styles;
    }

    private static function format_margin_value( $value ){
        $formatted_value = ( self::isNumeric($value) ) ? $value.'px' : $value;
        return $formatted_value;
    }

    private static function isNumeric($str){
        return preg_match('/^[0-9]+$/', $str);
    }
}