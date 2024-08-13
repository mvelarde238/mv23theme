<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Background{
    /**
     * Return array of css background properties
     */
    public static function get_styles( $args ){
        $styles = array();
        $settings = ( isset($args['settings']) ) ? $args['settings'] : array();

	    if (
            isset( $settings['background_color'] ) &&              
            !empty( $settings['background_color']['color'] ) ) {

            $color = $settings['background_color']['color'];
            $alpha = $settings['background_color']['alpha'];
            $bgc = ( $alpha != 100 ) ? 'rgba('.hexToRgb( $color, $alpha ).')' : $color;

	        $styles[] = 'background-color:'.$bgc;
	    }

        if (
            isset( $settings['background_image'] ) &&              
            !empty( $settings['background_image']['image'] ) ) {

            $bgi_url = wp_get_attachment_url( $settings['background_image']['image'] );
            if( $bgi_url ){
                $styles[] = 'background-image:url('.$bgi_url.')';

                $bgi_settings = $settings['background_image']['settings'];

                if ($bgi_settings['size'] != 'auto') $styles[] = 'background-size: '.$bgi_settings['size'];
                if ($bgi_settings['repeat'] != 'repeat') $styles[] = 'background-repeat: '.$bgi_settings['repeat'];
                if ($bgi_settings['position_x'] != 'left') $styles[] = 'background-position-x: '.$bgi_settings['position_x'];
                if ($bgi_settings['position_y'] != 'top') $styles[] = 'background-position-y: '.$bgi_settings['position_y'];
                if (isset($bgi_settings['parallax']) && $bgi_settings['parallax']) $styles[] = 'background-attachment: fixed';
            }
        }        

        return $styles;
    }
}