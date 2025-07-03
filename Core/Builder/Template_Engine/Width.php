<?php
namespace Core\Builder\Template_Engine;

Class Width{
    /**
     * Return array of css widths
     */
    public static function get_styles( $args ){
        $styles = array();
        $settings = ( isset($args['settings']) ) ? $args['settings'] : array();

        if ( isset($settings['width'])  ) {
            $width_settings = $settings['width'];
            $widths = array('width', 'max_width', 'min_width');

            foreach ($widths as $key) {
                if ( $width_settings[$key] != '' ) $styles[] = str_replace('_', '-', $key).':'.$width_settings[$key];  
            }
        }

        return $styles;
    }
}