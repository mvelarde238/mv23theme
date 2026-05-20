<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Core\Theme_Options\Fields\Device_Switch;
use Core\Theme_Options\Fields\Global_Styles as Global_Styles_Options;

class Global_Styles{
    public static function init(){
        Container::create( 'global_styles' )
            ->set_title( __('Global Styles','mv23theme') )
            ->set_description_position('label')
            ->add_location( 'options', 'theme-options' )
            // ->add_location( 'customizer', array(
            //     'postmessage_fields' => array( 
            //         'typography_settings', 
            //         'headings_settings',
            //         'links_settings',
            //     )
            // ))
            ->add_fields( Device_Switch::get_fields() )
            ->add_fields( Global_Styles_Options::get_fields() );
    }
}