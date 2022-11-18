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
                    $trigger_element = ($settings['trigger_element']['el'] == 'selector' ) ? $settings['trigger_element']['selector'] : 'this';
                    $element = ($settings['element']['el'] == 'selector' ) ? $settings['element']['selector'] : 'this';

                    $from = array();
                    foreach ($group['animated_properties']['from'] as $item) {
                        if($item['value'] != '') $from[$item['property']] = $item['value'];
                    }

                    $to = array();
                    foreach ($group['animated_properties']['to'] as $item) {
                        if($item['value'] != '') $to[$item['property']] = $item['value'];
                    }

                    array_push($scroll_animations, array(
                        'trigger_element' => $trigger_element,
                        'element' => $element,
                        'trigger_hook' => $settings['trigger-hook'],
                        'duration' => $settings['duration'],
                        'offset' => $settings['offset'],
                        'add_indicators' => $settings['add_indicators'],
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