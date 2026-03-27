<?php
namespace Ultimate_Fields\Ultimate_Builder;

class Templates_Generator{

    private static function process_component( $component, &$datastore ) {
        // Prepare processed component
        $processed = [
            'gjs_component' => null,
            'gjs_styles' => [],
            'styles' => '' // kept for backward compatibility, styles are stored in gjs data since 3.5.0
        ];

        // Generate unique ID for this component
        $__id = 'cmp_' . substr(md5(uniqid()), 0, 8);

        // Process sub-components if any
        $gjs_sub_components = [];
        if (isset($component['components']) && is_array($component['components'])) {
            foreach ($component['components'] as $sub_component) {
                $processed_sub = self::process_component($sub_component, $datastore);
                $gjs_sub_components[] = $processed_sub['gjs_component'];
                // Collect styles from sub-components
                if (!empty($processed_sub['gjs_styles'])) {
                    $processed['gjs_styles'] = array_merge($processed['gjs_styles'], $processed_sub['gjs_styles']);
                }
                if (!empty($processed_sub['styles'])) {
                    $processed['styles'] .= $processed_sub['styles'];
                }
            }
        }

        // GJS Component (hierarchical structure)
        $processed['gjs_component'] = [
            'type' => $component['type'],
            '__id' => $__id
        ];
        if (!empty($gjs_sub_components)) {
            $processed['gjs_component']['components'] = $gjs_sub_components;
        }

        // Datastore entry (flat structure - keyed by __id)
        $datastore_entry = [
            '__type' => $component['type']
        ];
        // Copy component data except reserved keys
        $reserved_keys = ['type', 'components', 'styles'];
        foreach ($component as $key => $value) {
            if (!in_array($key, $reserved_keys)) {
                $datastore_entry[$key] = $value;
            }
        }

        // Process styles if any
        if( isset($component['styles']) ){
            $comp_id = 'id' . substr( md5( uniqid() ), 0, 5 );
            $css_string = '';
            
            foreach( $component['styles'] as $style ){
                // Add selector to style and store in gjs_styles
                // "selectorsAdd" is a flag to indicate a custom selector, e.g: #id.header--sticky
                $style['selectors'] = (!isset($style['selectorsAdd'])) ? [ '#'.$comp_id ] : [];
                $style['selectorsAdd'] = (isset($style['selectorsAdd'])) ? str_replace('%comp_id%', $comp_id, $style['selectorsAdd']) : null;
                $processed['gjs_styles'][] = $style;
                
                // Generate CSS string
                if( isset($style['style']) && is_array($style['style']) ){
                    $css_rules = '';
                    foreach( $style['style'] as $property => $value ){
                        $css_rules .= $property . ':' . $value . ';';
                    }
                    
                    if( isset($style['selectorsAdd']) && !empty($style['selectorsAdd']) ){
                        $selector_string = $style['selectorsAdd'] . '{' . $css_rules . '}';
                    } else {
                        $selector_string = '#' . $comp_id . '{' . $css_rules . '}';
                    }
                    
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
        }

        // Add to flat datastore
        $datastore[$__id] = $datastore_entry;

        return $processed;
    }

    public static function generate_templates( $content_structure = array() ) {
        $content_structure = [ $content_structure ]; // Ensure it's an array of components
        $gjs_components = array();
        $datastore = array();
        $gjs_styles = [];
        $styles = '* { box-sizing: border-box; } body {margin: 0;}';

        if( is_array( $content_structure ) && ! empty( $content_structure ) ) {
            foreach( $content_structure as $component ) {
                $processed = self::process_component( $component, $datastore );
                $gjs_components[] = $processed['gjs_component'];
                if (!empty($processed['gjs_styles'])) {
                    $gjs_styles = array_merge($gjs_styles, $processed['gjs_styles']);
                }
                if( ! empty( $processed['styles'] ) ) {
                    $styles .= $processed['styles'];
                }
            }
        }

        // Wrap all components in a main wrapper if not already wrapped
        $gjs_wrapper = $gjs_components[0] ?? [
            'type' => 'wrapper',
            '__id' => 'cmp_wrapper'
        ];

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
            'datastore' => $datastore,
            'styles' => $styles
        );
    }
}