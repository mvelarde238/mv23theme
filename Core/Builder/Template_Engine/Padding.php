<?php
namespace Core\Builder\Template_Engine;

use Core\Builder\Template_Engine\Helpers;

Class Padding{
    /**
     * Return array of css paddings
     */
    public static function get_styles( $args ){
        $styles = array();
    
        if ( isset($args['settings']['padding'])  ) {
            $padding_settings = $args['settings']['padding'];
            
            // Get formatted values for each direction
            $top = isset($padding_settings['top']) && $padding_settings['top'] !== '' ? Helpers::format_numeric_value_as_pixels($padding_settings['top']) : null;
            $right = isset($padding_settings['right']) && $padding_settings['right'] !== '' ? Helpers::format_numeric_value_as_pixels($padding_settings['right']) : null;
            $bottom = isset($padding_settings['bottom']) && $padding_settings['bottom'] !== '' ? Helpers::format_numeric_value_as_pixels($padding_settings['bottom']) : null;
            $left = isset($padding_settings['left']) && $padding_settings['left'] !== '' ? Helpers::format_numeric_value_as_pixels($padding_settings['left']) : null;

            // Check if we can simplify
            $simplified = Helpers::simplify_values('padding', $top, $right, $bottom, $left);

            if ($simplified) {
                $styles['padding'] = $simplified;
            } else {
                // Fall back to individual properties
                if ($top !== null) $styles['padding-top'] = $top;
                if ($right !== null) $styles['padding-right'] = $right;
                if ($bottom !== null) $styles['padding-bottom'] = $bottom;
                if ($left !== null) $styles['padding-left'] = $left;
            }
        }
    
        return $styles;
    }
}