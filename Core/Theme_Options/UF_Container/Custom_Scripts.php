<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Custom_Scripts{
    public static function init(){
        Container::create('custom_scripts_options')
            ->add_location( 'options', 'custom-scripts-options' )
            ->set_description_position('label')
            ->add_fields(array(
                Field::create('textarea', 'head_scripts')
                    ->set_description(__('Scripts entered here will be output in the head section.', 'mv23theme'))
                    ->set_attr(array(
                        'data-type' => 'html'
                    )),
                Field::create('textarea', 'body_scripts', 'Body Scripts (After body)')
                    ->set_description(__('Scripts entered here will be output immediately after the opening body tag.', 'mv23theme'))
                    ->set_attr(array(
                        'data-type' => 'html'
                    )),
                Field::create('textarea', 'footer_scripts', 'Footer Scripts (Before body)')
                    ->set_description(__('Scripts entered here will be output immediately before the closing body tag.', 'mv23theme'))
                    ->set_attr(array(
                        'data-type' => 'html'
                    )),
                Field::create('message', 'Hint_1')->set_description('Usar < script >...< /script >'),
                Field::create('message', 'Hint_2')->set_description('Jquery : (function($){ ... })(jQuery)'),
            ));
    }
}