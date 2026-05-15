<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Social_Networks {
    public static function get_fields(){
        $social_networks_default_value = get_option( 'social_networks', array() );

        $fields = array(
            Field::create( 'tab', 'social_networks_tab', __('Social Networks','mv23theme') ),
            Field::create( 'repeater', 'social_networks', __('Social Networks','mv23theme') )
                ->set_add_text(__('Add Social Network','mv23theme'))
                ->set_description(__('Add the URLs of your social networks. For WhatsApp, add the phone number with country code and without symbols.<br><br>To show them on your website, you can use these shortcodes: [social_networks] [redes_sociales].','mv23theme'))
                ->set_default_value($social_networks_default_value)
                ->set_chooser_type('dropdown')
                ->add_group('social-network', array(
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
                            'skype' => 'Skype',
                            'linkedin' => 'Linkedin',
                            'pinterest' => 'Pinterest',
                            'envelope' => 'Mail'
                        ))->set_width( 25 ),
                        Field::create( 'text', 'url' )->set_width( 75 )->add_dependency('icon','whatsapp','!='),
                        Field::create( 'text', 'number', __('Phone number','mv23theme') )->set_width( 25 )->add_dependency('icon','whatsapp','='),
                        Field::create( 'text', 'msg', __('Message','mv23theme') )->set_width( 50 )->add_dependency('icon','whatsapp','=')->set_default_value(__('Hello, I need more information about...','mv23theme')),
                    )
                ))
        );

        return $fields;
    }
}