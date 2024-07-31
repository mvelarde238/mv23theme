<?php
namespace Theme_Custom_Fields\Template_Engine;

class Borders{
    /**
     * Return array of css bordes properties
     */
    public static function get_styles($args){
        $styles = array();

        $show_border = (isset($args['show_border'])) ? $args['show_border'] : false;
        $add_border_radius = (isset($args['add_border_radius'])) ? $args['add_border_radius'] : false;

        if ($show_border) {
            $border_width = $args['border']['width'] . 'px';
            $style_and_color = $args['border']['style'] . ' ' . $args['border']['color'];
            $border_properties = $border_width . ' ' . $style_and_color;

            if ($args['border_apply_to'] == 'all') {
                $styles[] = 'border:' . $border_properties;
            }

            if ($args['border_apply_to'] == 'custom') {
                $custom_border = $args['custom_border'];
                if ($custom_border['top'] == 1) $styles[] = 'border-top:' . $border_properties;
                if ($custom_border['right'] == 1) $styles[] = 'border-right:' . $border_properties;
                if ($custom_border['bottom'] == 1) $styles[] = 'border-bottom:' . $border_properties;
                if ($custom_border['left'] == 1) $styles[] = 'border-left:' . $border_properties;
            }
        }

        if ($add_border_radius) {
            $radius_properties = $args['border_radius'] . 'px;';
            if ($args['radius_apply_to'] == 'all') {
                $styles[] = 'border-radius:' . $radius_properties;
            }

            if ($args['radius_apply_to'] == 'custom') {
                $custom_radius = $args['custom_radius'];
                if ($custom_radius['top-left'] == 1) $styles[] = 'border-top-left-radius:' . $radius_properties;
                if ($custom_radius['top-right'] == 1) $styles[] = 'border-top-right-radius:' . $radius_properties;
                if ($custom_radius['bottom-right'] == 1) $styles[] = 'border-bottom-right-radius:' . $radius_properties;
                if ($custom_radius['bottom-left'] == 1) $styles[] = 'border-bottom-left-radius:' . $radius_properties;
            }
        }

        return $styles;
    }
}
