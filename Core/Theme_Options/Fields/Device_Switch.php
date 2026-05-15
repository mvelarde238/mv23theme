<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Device_Switch {
    public static function get_fields(){
        $device_switch = Field::create( 'image_select', 'device_switch' )
            ->set_default_value('desktop')
        	->add_options(array(
        		'desktop'  => array(
        			'label' => 'Desktop',
        			'image' => BUILDER_PATH.'/assets/images/devices/desktop.png'
        		),
                'tablet'  => array(
        			'label' => 'Tablet',
                    'image' => BUILDER_PATH.'/assets/images/devices/tablet.png'
                ),
                'mobileLandscape'  => array(
        			'label' => 'Mobile Landscape',
                    'image' => BUILDER_PATH.'/assets/images/devices/mobile-landscape.png'
                ),
                'mobilePortrait'  => array(
        			'label' => 'Mobile Portrait',
                    'image' => BUILDER_PATH.'/assets/images/devices/mobile-portrait.png'
                )
        	));

        if( isset($_GET['action']) && $_GET['action'] === 'ultimate-builder') {
            $device_switch->hide_label();
        }

        $fields = array(
            $device_switch
        );
        return $fields;
    }
}