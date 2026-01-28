<?php
namespace Ultimate_Fields\Ultimate_Builder;

class Templates_Generator{

    private static function process_component( $component ) {
        // Prepare processed component
        $processed = [
            'uf_component' => null,
            'gjs_component' => null,
            'gjs_styles' => [],
            'styles' => ''
        ];

        // Process sub-components if any
        $gjs_sub_components = [];
        $uf_sub_components = [];
        if (isset($component['components']) && is_array($component['components'])) {
            foreach ($component['components'] as $sub_component) {
                $processed_sub = self::process_component($sub_component);
                $gjs_sub_components[] = $processed_sub['gjs_component'];
                $uf_sub_components[] = $processed_sub['uf_component'];
                // Collect styles from sub-components
                if (!empty($processed_sub['gjs_styles'])) {
                    $processed['gjs_styles'] = array_merge($processed['gjs_styles'], $processed_sub['gjs_styles']);
                }
                if (!empty($processed_sub['styles'])) {
                    $processed['styles'] .= $processed_sub['styles'];
                }
            }
        }

        // GJS Component
        $processed['gjs_component'] = [
            'type' => $component['__type'],
        ];
        if (!empty($gjs_sub_components)) {
            $processed['gjs_component']['components'] = $gjs_sub_components;
        }

        // UF Component
        $processed['uf_component'] = $component;
        unset($processed['uf_component']['styles']);
        if (!empty($uf_sub_components)) {
            $processed['uf_component']['components'] = $uf_sub_components;
        }

        // Process styles if any
        $comp_id = 'id' . substr( md5( uniqid() ), 0, 5 );
        if( isset($component['styles']) ){
            $css_string = '';
            
            foreach( $component['styles'] as $style ){
                $style['selectors'] = [ '#'.$comp_id ];
                $processed['gjs_styles'][] = $style;
                
                // Generate CSS string
                if( isset($style['style']) && is_array($style['style']) ){
                    $css_rules = '';
                    foreach( $style['style'] as $property => $value ){
                        $css_rules .= $property . ':' . $value . ';';
                    }
                    
                    $selector_string = '#' . $comp_id . '{' . $css_rules . '}';
                    
                    // Wrap in media query if needed
                    if( isset($style['mediaText']) ){
                        $css_string .= '@media ' . $style['mediaText'] . '{' . $selector_string . '}';
                    } else {
                        $css_string .= $selector_string;
                    }
                }
            }
            
            $processed['styles'] .= $css_string;
            $processed['gjs_component']['attributes']['id'] = $comp_id;
            $processed['uf_component']['__gjsAttributes']['id'] = $comp_id;
        }

        return $processed;
    }

    public static function generate_templates( $inner_components = array() ) {
        $gjs_components = array();
        $uf_components = array();
        $gjs_styles = [];
        $styles = '* { box-sizing: border-box; } body {margin: 0;}';

        if( is_array( $inner_components ) && ! empty( $inner_components ) ) {
            foreach( $inner_components as $component ) {
                $processed = self::process_component( $component );
                $gjs_components[] = $processed['gjs_component'];
                $uf_components[] = $processed['uf_component'];
                if (!empty($processed['gjs_styles'])) {
                    $gjs_styles = array_merge($gjs_styles, $processed['gjs_styles']);
                }
                if( ! empty( $processed['styles'] ) ) {
                    $styles .= $processed['styles'];
                }
            }
        }

        $gjs_wrapper['components'] = array(
            array(
                'type' => 'container',
                'classes' => array('container'),
                'attributes' => array(),
                'components' => $gjs_components
            )
        );

        $uf_wrapper['components'] = array(
            array(
                '__type' => 'container',
                'components' => $uf_components
            )
        );

        return array(
            'gjs_template' => array(
                'dataSources' => array(),
                'assets' => array(),
                'styles' => $gjs_styles,
                'pages' => array(
                    array(
                        'frames' => array(
                            array(
                                'component' => $gjs_wrapper
                            )
                        ),
                        'type' => 'main'
                    )
                ),
                'symbols' => array()
            ),
            'uf_template' => array(
                $uf_wrapper
            ),
            'styles' => $styles
        );
    }
}