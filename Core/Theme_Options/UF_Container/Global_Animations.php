<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Global_Animations{
    public static function init(){
        Container::create( 'global_animations' ) 
            ->set_title( __('Global Animations','mv23theme') )
            ->add_location( 'options', 'theme-options', array(
                'context' => 'side'
            ))
            ->add_fields(array(
                Field::create( 'complex', 'scroll_animations' )->add_fields(array(
                    Field::create( 'checkbox', 'activate' )
                        ->set_text( __('Activate Scroll Animations','mv23theme') )
                        ->hide_label()
                ))->hide_label(),
                Field::create( 'common_settings_control', 'global_animations' )
					->set_container( 'scroll_animations_container' )
					->set_add_text( __('Global Animations', 'mv23theme') )
					->hide_label()
            ));
    }
}