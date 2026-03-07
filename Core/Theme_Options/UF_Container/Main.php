<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Core\Theme_Options\Fields\Global_Settings;
use Core\Theme_Options\Fields\Colors;
use Core\Theme_Options\Fields\Typography;
use Core\Theme_Options\Fields\Page_Container;

class Main{
    public static function init(){
        Container::create( 'main_theme_options' )
            ->set_title( __('Theme Options','mv23theme') )
            ->set_description_position('label')
            ->add_location( 'options', 'theme-options' )
            ->add_location( 'customizer', array(
                'postmessage_fields' => array( 'theme_colors', 'typography_css_vars', 'containers_width' )
            ))
            ->add_fields( Global_Settings::get_fields() )
            ->add_fields( Colors::get_fields() )
            ->add_fields( Typography::get_fields() )
            ->add_fields( Page_Container::get_fields() );
    }
}