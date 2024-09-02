<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'rrss_options' ) 
    ->add_location( 'options', 'theme-options' )
    ->set_layout( 'grid' )
    ->set_title('Redes Sociales')
    ->add_fields(array(
        Field::create( 'repeater', 'rrss', 'Redes Sociales' )->set_add_text('Agregar')->hide_label()
        // ->set_layout( 'table' )
        ->add_group('Red Social', array(
            'title_template' => '<%= icon %> : <%= url %>',
            'fields' => array(
                Field::create( 'select', 'icon', 'Red Social')->add_options( array(
                    '' => '--Seleccione--',
                    'facebook' => 'Facebook',
                    'twitter' => 'Twitter',
                    'instagram' => 'Instagram',
                    'youtube' => 'Youtube',
                    'whatsapp' => 'WhatsApp',
                    'telegram' => 'Telegram',
                    'vimeo' => 'Vimeo',
                    'behance' => 'Behance',
                    'github' => 'Github',
                    'tumblr' => 'Tumblr',
                    'flickr' => 'Flickr',
                    'soundcloud' => 'SoundCloud',
                    'skype' => 'Skipe',
                    'linkedin' => 'Linkedin',
                    'pinterest' => 'Pinterest',
                    'envelope' => 'Mail'
                ))->set_width( 25 ),
                Field::create( 'text', 'url' )->set_width( 75 )->add_dependency('icon','whatsapp','!='),
                Field::create( 'text', 'number' )->set_width( 25 )->add_dependency('icon','whatsapp','='),
                Field::create( 'text', 'msg', 'Message' )->set_width( 50 )->add_dependency('icon','whatsapp','=')->set_default_value('Hola, necesito más información...'),
            )
        ))
    ));