<?php
use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$theme_options_page = Options_Page::create( 'theme-options', 'Theme Options' )->set_position( 2 );

Container::create( 'main_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->set_layout( 'grid' )
    ->add_fields(array(
        Field::create( 'image', 'main_logo', 'Logo Principal' )->set_width(33),
        Field::create( 'image', 'secondary_logo', 'Logo versión 2' )->set_width(33),
        Field::create( 'repeater', 'rrss', 'Redes Sociales' )->set_add_text('Agregar')->set_layout( 'table' )
        ->add_group('Red Social', array(
            'fields' => array(
                Field::create( 'select', 'icon')->add_options( array(
                    '' => '--Seleccione--',
                    'facebook' => 'Facebook',
                    'twitter' => 'Twitter',
                    'instagram' => 'Instagram',
                    'youtube' => 'Youtube',
                    'vimeo' => 'Vimeo',
                    'behance' => 'Behance',
                    'github' => 'Github',
                    'tumblr' => 'Tumblr',
                    'flickr' => 'Flickr',
                    'soundcloud' => 'SoundCloud',
                    'skype' => 'Skipe',
                    'linkedin' => 'Linkedin',
                    'pinterest' => 'Pinterest',
                ))->set_width( 25 ),
                Field::create( 'text', 'url' )->set_width( 75 ),
            )
        )),
    ));

Container::create( 'page_editor_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->add_fields(array(
        Field::create( 'checkbox', 'activate_gm', 'Activar Google Maps' )->set_text('Activar'),
        Field::create( 'multiselect', 'show_editor_in','El theme oculta el editor de texto en entradas y páginas, mostrarlo en los siguientes lugares')->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
            'post' => 'Entradas',
            'page' => 'Páginas'
        )),
    ));


$footer_page = Options_Page::create( 'footer', 'Pie de Página' )->set_parent( $theme_options_page );

$footer_fields = array(
    Field::create( 'tab', 'Español' ),
    Field::create( 'repeater', 'footer_modules' )->hide_label()->set_add_text('Agregar Módulo')->add_group($modulos),
);
if (IS_MULTILANGUAGE) {
    array_push($footer_fields, Field::create( 'tab', 'English' ));
    array_push($footer_fields, Field::create( 'repeater', 'footer_modules_en' )->hide_label()->set_add_text('Add Module')->add_group($modulos));
}

Container::create( 'footer_options' ) 
    ->add_location( 'options', $footer_page )
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields($footer_fields);


$custom_scripts_page = Options_Page::create( 'custom_scripts', 'Custom Scripts' )->set_parent( $theme_options_page );

Container::create( 'custom_scripts_options' ) 
    ->add_location( 'options', $custom_scripts_page )
    ->add_fields(array(
        Field::create( 'textarea', 'head_scripts' ),
        Field::create( 'textarea', 'footer_scripts' ),
        Field::create( 'message', 'Hint_1' )->set_description('Usar < script >...< /script >'),
        Field::create( 'message', 'Hint_2' )->set_description('Jquery : (function($){ ... })(jQuery)'),
    ));