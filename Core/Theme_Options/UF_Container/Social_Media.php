<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Social_Media{
    public static function init(){
        Container::create( 'rrss_options' ) 
            ->add_location( 'options', 'theme-options' )
            ->set_layout( 'grid' )
            ->set_title( __('Social Networks','mv23theme') )
            ->add_fields(array(
                Field::create( 'repeater', 'rrss', __('Social Networks','mv23theme') )->set_add_text(__('Add','mv23theme'))->hide_label()
                    ->set_chooser_type('dropdown')
                // ->set_layout( 'table' )
                ->add_group(__('Social Network','mv23theme'), array(
                    'title_template' => '<%= icon %> : <%= url %>',
                    'fields' => array(
                        Field::create( 'select', 'icon', __('Social Network','mv23theme'))->add_options( array(
                            '' => __('Select','mv23theme'),
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'instagram' => 'Instagram',
                            'youtube' => 'Youtube',
                            'whatsapp' => 'WhatsApp',
                            'telegram' => 'Telegram',
                            'vimeo' => 'Vimeo',
                            'behance' => 'Behance',
                            'github' => 'Github',
                            'tiktok' => 'Tiktok',
                            'flickr' => 'Flickr',
                            'soundcloud' => 'SoundCloud',
                            'skype' => 'Skipe',
                            'linkedin' => 'Linkedin',
                            'pinterest' => 'Pinterest',
                            'envelope' => 'Mail'
                        ))->set_width( 25 ),
                        Field::create( 'text', 'url' )->set_width( 75 )->add_dependency('icon','whatsapp','!='),
                        Field::create( 'text', 'number', __('Phone number','mv23theme') )->set_width( 25 )->add_dependency('icon','whatsapp','='),
                        Field::create( 'text', 'msg', __('Message','mv23theme') )->set_width( 50 )->add_dependency('icon','whatsapp','=')->set_default_value(__('Hello, I need more information about...','mv23theme')),
                    )
                ))
            ));
    }
}