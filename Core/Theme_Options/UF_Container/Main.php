<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Core\Theme_Options\Fields\Global_Settings;
use Core\Theme_Options\Fields\Colors;
use Core\Theme_Options\Fields\Fonts;
use Core\Theme_Options\Fields\Container_Settings;
use Core\Theme_Options\Fields\Social_Networks;

class Main{
    public static function init(){
        Container::create( 'main_theme_options' )
            ->set_title( __('Global Settings','mv23theme') )
            ->set_description_position('label')
            ->add_location( 'options', 'theme-options' )
            // ->add_location( 'customizer', array(
            //     'postmessage_fields' => array( 
            //         'theme_colors', 
            //         'containers_settings' 
            //     )
            // ))
            ->add_fields( Global_Settings::get_fields() )
            ->add_fields( Colors::get_fields() )
            ->add_fields( Fonts::get_fields() )
            ->add_fields( Container_Settings::get_fields() )
            ->add_fields( Social_Networks::get_fields() );
    }
}