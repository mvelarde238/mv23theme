<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Core\Theme_Options\Fields\Logos;
use Core\Theme_Options\Fields\Colors;
use Core\Theme_Options\Fields\Typography;
use Core\Theme_Options\Fields\Header;
use Core\Theme_Options\Fields\Page_Container;
use Core\Theme_Options\Fields\Single_Pages;

class Main{
    public static function init(){
        Container::create( 'main_theme_options' )
            ->set_title( __('Theme Options','mv23theme') )
            ->set_description_position('label')
            ->add_location( 'options', 'theme-options' )
            ->add_location( 'customizer', array(
                'postmessage_fields' => array( 'colors_wrapper', 'primary_color_variations', 'secondary_color_variations', 'typography_css_vars', 'static_header_logo_height', 'sticky_header_logo_height', 'static_header_bgc', 'sticky_header_bgc', 'containers_width' )
            ))
            ->add_fields( Logos::get_fields() )
            ->add_fields( Colors::get_fields() )
            ->add_fields( Typography::get_fields() )
            ->add_fields( Header::get_fields('static') )
            ->add_fields( Header::get_fields('sticky') )
            ->add_fields( Page_Container::get_fields() )
            ->add_fields( Single_Pages::get_fields() );
    }
}