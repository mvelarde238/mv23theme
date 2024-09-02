<?php
use Ultimate_Fields\Container;
use Theme_Custom_Fields\Common_Settings;

return Container::create( '_all-settings' )
    ->add_fields( Common_Settings::get_fields('main') )
    ->add_fields( Common_Settings::get_fields('video-background') )
    ->add_fields( Common_Settings::get_fields('margins') )
    ->add_fields( Common_Settings::get_fields('borders') )
    ->add_fields( Common_Settings::get_fields('box-shadow') )
    ->add_fields( Common_Settings::get_fields('animation') )
    ->add_fields( Common_Settings::get_fields('scroll-animations') );