<?php
namespace Core\Builder\Template_Engine;

Class Margin{
    /**
     * Return array of css margins
     */
    public static function get_styles( $args ){
        $styles = array();

        if ( isset($args['settings']['margin'])  ) {
            $margin_settings = $args['settings']['margin'];

            // Get formatted values for each direction
            $top = isset($margin_settings['top']) && $margin_settings['top'] !== '' ? Helpers::format_numeric_value_as_pixels($margin_settings['top']) : null;
            $right = isset($margin_settings['right']) && $margin_settings['right'] !== '' ? Helpers::format_numeric_value_as_pixels($margin_settings['right']) : null;
            $bottom = isset($margin_settings['bottom']) && $margin_settings['bottom'] !== '' ? Helpers::format_numeric_value_as_pixels($margin_settings['bottom']) : null;
            $left = isset($margin_settings['left']) && $margin_settings['left'] !== '' ? Helpers::format_numeric_value_as_pixels($margin_settings['left']) : null;

            // Check if we can simplify
            $simplified = Helpers::simplify_values('margin', $top, $right, $bottom, $left);

            if ($simplified) {
                $styles['margin'] = $simplified;
            } else {
                // Fall back to individual properties
                if ($top !== null) $styles['margin-top'] = $top;
                if ($right !== null) $styles['margin-right'] = $right;
                if ($bottom !== null) $styles['margin-bottom'] = $bottom;
                if ($left !== null) $styles['margin-left'] = $left;
            }
        }
    
        return $styles;
    }
}