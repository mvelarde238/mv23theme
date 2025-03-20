<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Maps_Options{
    public static function init(){
        Container::create( 'maps_options' ) 
            ->set_title( __('Maps Options','mv23theme') )
            ->add_location( 'options', 'theme-options' )
            ->add_fields(array(
                Field::create( 'complex', 'leaflet_map_wrapper', __('Leaflet Maps','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'checkbox', 'activate_leaflet', __('Activate LeaftLet','mv23theme') )->set_text(__('Activate','mv23theme'))->fancy()
                )),
                Field::create( 'complex', 'google_map_wrapper', __('Google Maps','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'checkbox', 'activate_gm', __('Activate Google Maps','mv23theme') )->set_text(__('Activate','mv23theme'))->fancy()->set_width(30),
                    Field::create( 'text', 'uf_google_maps_api_key', __('Google Maps API Key','mv23theme'))->add_dependency('activate_gm')->required()->set_width(70),
                    Field::create( 'multiselect', 'gm_services', __('Google Map Services','mv23theme'))
                        ->set_input_type( 'checkbox' )
                        ->set_orientation( 'horizontal' )
                        ->add_dependency( 'activate_gm' )
                        ->add_options( array(
                            'places' => 'Places'
                        ))
                )),
            ));
    }
}