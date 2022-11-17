<?php
function generate_scroll_animations($componente){
    $scroll_data_attributes = '';

    if( SCROLL_ANIMATIONS ){
        if (!isset($componente['sa-settings'])) $componente['sa-settings'] = null;
        if (!isset($componente['sa-properties-settings'])) $componente['sa-properties-settings'] = null;
        if (!isset($componente['add_scroll_animation'])) $componente['add_scroll_animation'] = false;

        if( $componente['add_scroll_animation'] && $componente['sa-settings'] &&  $componente['sa-properties-settings']){
            $settings = $componente['sa-settings'];

            $trigger_element = ($settings['trigger_element']['el'] == 'selector' ) ? $settings['trigger_element']['selector'] : 'this';
            $scroll_data_attributes .= 'data-sa-trigger="'.$trigger_element.'" ';
            
            $element = ($settings['element']['el'] == 'selector' ) ? $settings['element']['selector'] : 'this';
            $scroll_data_attributes .= 'data-sa-element="'.$element.'" ';

            $scroll_data_attributes .= 'data-sa-hook="'.$settings['trigger-hook'].'" ';
            $scroll_data_attributes .= 'data-sa-duration="'.$settings['duration'].'" ';
            $scroll_data_attributes .= 'data-sa-offset="'.$settings['offset'].'" ';
            $scroll_data_attributes .= 'data-sa-indicators="'.$settings['add_indicators'].'" ';

            // initial values
            $from = array();
            // if( $componente['sa-properties-settings']['initial_values_check'] ){
                foreach ($componente['sa-properties-settings']['initial_values'] as $item) {
                    if($item['value'] != '') $from[$item['property']] = $item['value'];
                }
            // }
            $scroll_data_attributes .= "data-sa-from='".json_encode($from)."' ";

            // animated properties
            $to = array();
            foreach ($componente['sa-properties-settings']['properties'] as $item) {
                if($item['value'] != '') $to[$item['property']] = $item['value'];
            }
            $scroll_data_attributes .= "data-sa-to='".json_encode($to)."'";
        }
    }

	return $scroll_data_attributes;
}