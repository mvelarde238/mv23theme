<?php
namespace Core\Builder\Template_Engine;

Class Scroll_Animations{
    /**
     * Return html attribute
     */
    public static function get_attributes( $args ){
        $scroll_data_attributes = '';
    
        if( SCROLL_ANIMATIONS ){

            if( 
                isset($args['scroll_animations_settings']) && 
                is_array($args['scroll_animations_settings']) && 
                isset($args['scroll_animations_settings']['groups']) && 
                is_array($args['scroll_animations_settings']['groups']) ){
                $scroll_animations = array();
    
                if( count($args['scroll_animations_settings']['groups']) > 0 ){
                    foreach ($args['scroll_animations_settings']['groups'] as $group) {
    
                        $settings = $group['settings'];
                        if( IS_MOBILE && isset($settings['disable_on_mobile']) && $settings['disable_on_mobile'] == 1 ) continue;           
    
                        $toggle_actions = 'play none none reset';
                        if( isset($settings['set_advanced_settings']) && $settings['set_advanced_settings'] && $settings['toggle_actions'] ){
                            $toggle_actions = $settings['toggle_actions'];
                        }
                        $trigger_element = ($settings['trigger_element']['el'] == 'selector' ) ? $settings['trigger_element']['selector'] : 'this';
                        $start = ($settings['start_at']['hook'] != 'custom') ? $settings['start_at']['hook'] : $settings['start_at']['custom_hook'];
                        $end = ( isset($settings['end_at']['customize']) && $settings['end_at']['customize'] ) ? $settings['end_at']['custom'] : '+='.$settings['end_at']['basic'];
                        $add_indicators = (isset($settings['add_indicators'])) ? $settings['add_indicators'] : false;
                        $pin_settings = $settings['pin_settings'] ?? array( 'pinned_el' => 'trigger_el', 'selector' => '', 'push_followers' => 1 );
                        $trigger_carrusel = (isset($settings['trigger_carrusel'])) ? $settings['trigger_carrusel'] : false;
                        $set_pin = $settings['set_pin'] ?? false;

                        $timeline = array();
                        $timeline_raw = $group['timeline'] ?? false;
                        if( $timeline_raw ){
                            foreach ($timeline_raw as $tween_raw) {
                                $tween = array();

                                $tween[] = $tween_raw['element'];

                                $from = array();
                                $from_raw = $tween_raw['animated_properties']['from'] ?? array();
                                if( is_array($from_raw) ){
                                    foreach ($from_raw as $item) {
                                        if($item['value'] != '') $from[$item['property']] = $item['value'];
                                    }
                                }
                                $tween[] = $from;
    
                                $to = array();
                                $to_raw = $tween_raw['animated_properties']['to'] ?? array();
                                if(is_array($to_raw)){
                                    foreach ($to_raw as $item) {
                                        if($item['value'] != '') $to[$item['property']] = $item['value'];
                                    }
                                }
                                $tween[] = $to;

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
                            'timeline' => $timeline
                        ));
                    }
                    
                    $scroll_data_attributes .= "data-scroll-animations='".json_encode($scroll_animations)."'";
                }
    
            }
        }
    
        return $scroll_data_attributes;
    }
}