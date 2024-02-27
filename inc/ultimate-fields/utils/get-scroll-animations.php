<?php
function generate_scroll_animations($componente){
    $scroll_data_attributes = '';

    if( SCROLL_ANIMATIONS ){
        if (!isset($componente['scroll_animations'])) $componente['scroll_animations'] = null;
        if (!isset($componente['add_scroll_animation'])) $componente['add_scroll_animation'] = false;

        if( $componente['add_scroll_animation'] && $componente['scroll_animations']){
            $scroll_animations = array();

            if( is_array($componente['scroll_animations']) && count($componente['scroll_animations']) > 0 ){
                foreach ($componente['scroll_animations'] as $group) {

                    $settings = $group['settings'];
                    if( IS_MOBILE && $settings['turn_off_in_mobile'] == 1 ) continue;           

                    $trigger_element = ($settings['trigger-element']['el'] == 'selector' ) ? $settings['trigger-element']['selector'] : 'this';
                    $element = ($settings['element']['el'] == 'this' ) ? 'this' : $settings['element']['selector'];

                    $from = array();
                    foreach ($group['animated_properties']['from'] as $item) {
                        if($item['value'] != '') $from[$item['property']] = $item['value'];
                    }

                    $to = array();
                    foreach ($group['animated_properties']['to'] as $item) {
                        if($item['value'] != '') $to[$item['property']] = $item['value'];
                    }

                    $add_indicators = ( SCROLL_INDICATORS ) ? ((isset($settings['add_indicators'])) ? $settings['add_indicators'] : false) : false;
                    $set_pin = (isset($settings['set_pin'])) ? $settings['set_pin'] : false;
                    $trigger_carrusel = (isset($settings['trigger_carrusel'])) ? $settings['trigger_carrusel'] : false;

                    array_push($scroll_animations, array(
                        'trigger_element' => $trigger_element,
                        'element' => array('key'=>$settings['element']['el'], 'el'=>$element),
                        'trigger_hook' => $settings['trigger-hook'],
                        'duration' => $settings['duration'],
                        'offset' => $settings['offset'],
                        'add_indicators' => $add_indicators,
                        'set_pin' => $set_pin,
                        'trigger_carrusel' => $trigger_carrusel,
                        'from' => json_encode($from),
                        'to' => json_encode($to)
                    ));
                }
            }

            $scroll_data_attributes .= "data-scroll-animations='".json_encode($scroll_animations)."'";
        }
    }

	return $scroll_data_attributes;
}