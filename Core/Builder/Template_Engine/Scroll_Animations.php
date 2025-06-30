<?php
namespace Core\Builder\Template_Engine;

Class Scroll_Animations{
    /**
     * Return html attribute
     */
    public static function get_attribute( $animations_settings ){
        $scroll_data_attributes = '';
    
        $scroll_animations = array();
    
        if( count($animations_settings['groups']) > 0 ){
            foreach ($animations_settings['groups'] as $group) {
    
                $settings = $group['settings'];
                if( isset($settings['disable_everywhere']) && $settings['disable_everywhere'] == 1 ) continue;
                if( IS_MOBILE && isset($settings['disable_on_mobile']) && $settings['disable_on_mobile'] == 1 ) continue;           
    
                $trigger_element = ($settings['trigger_element']['el'] == 'selector' ) ? $settings['trigger_element']['selector'] : 'this';
                $start = ($settings['start_at']['hook'] != 'custom') ? $settings['start_at']['hook'] : $settings['start_at']['custom_hook'];
                $add_indicators = (isset($settings['add_indicators'])) ? $settings['add_indicators'] : false;
                $pin_settings = $settings['pin_settings'] ?? array( 'pinned_el' => 'trigger_el', 'selector' => '', 'push_followers' => 1 );
                $trigger_carrusel = (isset($settings['trigger_carrusel'])) ? $settings['trigger_carrusel'] : false;
                $set_pin = $settings['set_pin'] ?? false;

                // toggle actions setting
                $toggle_actions = 'play none none reverse';
                if( isset($settings['set_advanced_settings']) && $settings['set_advanced_settings'] && $settings['toggle_actions'] ){
                    $toggle_actions = $settings['toggle_actions'];
                }

                // end setting
                $end = '';
                if( isset($settings['end_at']['customize']) && $settings['end_at']['customize'] ){
                    $end = $settings['end_at']['custom'];
                } else {
                    if( $settings['end_at']['basic'] ) $end = '+='.$settings['end_at']['basic'];
                }

                // toggle class setting
                $toggle_class = '';
                if( isset($settings['set_advanced_settings']) && $settings['set_advanced_settings'] ){
                    $toggle_class_key = $settings['toggle_class']['el'] ?? 'this';
                    $toggle_class_class = $settings['toggle_class']['classname'] ?? '';
                    if( $toggle_class_key == 'this' ){
                        $toggle_class = $toggle_class_class;
                    }else{
                        $toggle_class_selector = $settings['toggle_class']['selector'] ?? '';
                        if( $toggle_class_selector ){
                            $toggle_class = array(
                                'targets' => $toggle_class_selector,
                                'className' => $toggle_class_class
                            );
                        }
                    }
                }

                $timeline = array();
                $timeline_raw = $group['timeline'] ?? false;
                if( $timeline_raw ){
                    foreach ($timeline_raw as $tween_raw) {
                        $tween = array();
                        $tween[] = $tween_raw['element'];

                        foreach (['from','to'] as $key) {
                            $from_or_to = self::process_raw_properties($tween_raw['animated_properties'][$key]);
                            $tween[] = $from_or_to;
                        }

                        $position = ($tween_raw['position']['key'] == 'custom') ? $tween_raw['position']['custom_key'] : $tween_raw['position']['key'];
                        $tween[] = $position;

                        $timeline[] = $tween;
                    }
                }

                // initial rules setting
                $initial_rules = array();
                if( isset($settings['set_initial_rules']) && $settings['set_initial_rules'] ){
                    if(is_array($settings['initial_rules']) && !empty($settings['initial_rules'])){
                        foreach ($settings['initial_rules'] as $rule_group) {
                            $rule_data = array();
                            $rule_data[] = $rule_group['element'];
                            $rule_data[] = self::process_raw_properties($rule_group['rules']);
                            $initial_rules[] = $rule_data;
                        }
                    }
                }
                            
                array_push($scroll_animations, array(
                    'toggle_actions' => $toggle_actions,
                    'trigger_element' => $trigger_element,
                    'start' => $start,
                    'end' => $end,
                    'add_indicators' => $add_indicators,
                    'set_pin' => $set_pin,
                    'pin_settings' => $pin_settings,
                    'trigger_carrusel' => $trigger_carrusel,
                    'timeline' => $timeline,
                    'toggle_class' => $toggle_class,
                    'initial_rules' => $initial_rules
                ));
            }
                    
            $scroll_data_attributes .= "data-scroll-animations='".json_encode($scroll_animations)."'";
        }

        return $scroll_data_attributes;
    }

    private static function process_raw_properties($raw_properties) {
        $processed_properties = array();

        if( is_array($raw_properties) && !empty($raw_properties) ) {
            foreach ($raw_properties as $property) {
                if (isset($property['custom']) && $property['custom']) {
                    if ($property['custom_value'] != '') {
                        $processed_properties[$property['property']] = $property['custom_value'];
                    }
                } else {
                    if ($property['property'] != 'customProperty') {
                        $processed_properties[$property['property']] = $property['value'];
                    } else {
                        if (!empty($property['custom_prop'])) {
                            $processed_properties[$property['custom_prop']] = $property['value'];
                        }
                    }
                }
            }
        }

        return $processed_properties;
    }
}