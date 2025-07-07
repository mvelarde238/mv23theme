<?php
namespace Core\Builder\Template_Engine;

class Borders{
    /**
     * Return array of css bordes properties
     */
    public static function get_styles($args){
        $styles = array();
        
        if ( isset($args['settings']['border']) ) {
            $border_settings = $args['settings']['border'];
            $unblock = (isset($border_settings['unlock'])) ? $border_settings['unlock'] : false;
            
            if ( $unblock ) {

                $borders = array('top','bottom','right','left');
                foreach ($borders as $border) {
                    if( $border_settings[$border]['width'] !== '' ) {
                        $border_width = $border_settings[$border]['width'] . 'px';
                        $style_and_color = $border_settings[$border]['style'] . ' ' . $border_settings[$border]['color'];
                        $styles[] = 'border-'.$border.':'.$border_width . ' ' . $style_and_color;   
                    }
                }

            } else {
                if( $border_settings['top']['width'] !== '' ) {
                    $border_width = $border_settings['top']['width'] . 'px';
                    $style_and_color = $border_settings['top']['style'] . ' ' . $border_settings['top']['color'];
                    $styles[] = 'border:'.$border_width . ' ' . $style_and_color;   
                }
            }
        }
        
        if ( isset($args['settings']['border_radius']) ) {
            $radius_settings = $args['settings']['border_radius'];

            if ($radius_settings['top_left'] !== '') $styles[] = 'border-top-left-radius:' . $radius_settings['top_left'].'px';
            if ($radius_settings['top_right'] !== '') $styles[] = 'border-top-right-radius:' . $radius_settings['top_right'].'px';
            if ($radius_settings['bottom_right'] !== '') $styles[] = 'border-bottom-right-radius:' . $radius_settings['bottom_right'].'px';
            if ($radius_settings['bottom_left'] !== '') $styles[] = 'border-bottom-left-radius:' . $radius_settings['bottom_left'].'px';
        }

        return $styles;
    }
}
