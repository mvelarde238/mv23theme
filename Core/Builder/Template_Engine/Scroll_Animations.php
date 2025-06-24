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
                            $from_or_to = array();
                            $from_or_to_raw = $tween_raw['animated_properties'][$key] ?? array();
                            if( is_array($from_or_to_raw) ){
                                foreach ($from_or_to_raw as $item) {
                                    if( isset($item['custom']) && $item['custom'] ){
                                        if($item['custom_value'] != '') $from_or_to[$item['property']] = $item['custom_value'];
                                    } else {
                                        if( $item['property'] != 'customProperty' ){
                                            $from_or_to[$item['property']] = $item['value'];
                                        } else {
                                            if( !empty($item['custom_prop']) ) $from_or_to[$item['custom_prop']] = $item['value'];
                                        }
                                    }
                                }
                            }
                            $tween[] = $from_or_to;
                        }

                        $position = ($tween_raw['position']['key'] == 'custom') ? $tween_raw['position']['custom_key'] : $tween_raw['position']['key'];
                        $tween[] = $position;

                        $timeline[] = $tween;
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
                    'toggle_class' => $toggle_class
                ));
            }
                    
            $scroll_data_attributes .= "data-scroll-animations='".json_encode($scroll_animations)."'";
        }

        return $scroll_data_attributes;
    }
}