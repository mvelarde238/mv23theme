<?php
use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$logos_field_names = array();

// -----------------------------------------------------------------------------------------------------------------------------------------------
$theme_options_page = Options_Page::create( 'theme-options', 'Theme Options' )->set_position( 2 );
$main_options_fields = array();

for ($i=1; $i <= LOGOS_QUANTITY; $i++) { 
    switch ($i) {
        case 1:
            $field_name = 'main_logo';
            break;

        case 2:
            $field_name = 'secondary_logo';
            break;
        
        default:
            $field_name = 'logo_v'.$i;
            break;
    }
    $field_title = 'Logo Versión '.$i;
    $logos_field_names[$field_name] = $field_title;
    $main_options_fields[] = Field::create( 'image', $field_name, $field_title )->set_width(25);
}

$main_options_fields[] = Field::create( 'repeater', 'rrss', 'Redes Sociales' )->set_add_text('Agregar')->set_layout( 'table' )
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
        ));

Container::create( 'main_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->set_layout( 'grid' )
    ->add_fields($main_options_fields);

Container::create( 'page_editor_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->add_fields(array(
        Field::create( 'checkbox', 'activate_gm', 'Activar Google Maps' )->set_text('Activar'),
        Field::create( 'multiselect', 'show_editor_in','El theme oculta el editor de texto en entradas, páginas y productos, mostrarlo en los siguientes lugares')->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
            'post' => 'Entradas',
            'page' => 'Páginas',
            'product' => 'Productos'
        )),
    ));

// -----------------------------------------------------------------------------------------------------------------------------------------------
$header_page = Options_Page::create( 'header', 'Header' )->set_parent( $theme_options_page );

$header_fields = array(
    Field::create( 'tab', 'Header Fijo' ),
    Field::create( 'select', 'fixed_header_logo', 'Versión del Logo')->add_options($logos_field_names),
    Field::create( 'complex', 'fixed_header_bgc', 'Color de fondo' )->add_fields(array(
        Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
        Field::create( 'color', 'bgc', 'Color' )->set_width( 75 )->add_dependency('add_bgc')->hide_label(),
    )),
    Field::create( 'select', 'fixed_header_color_scheme', 'Color del Texto' )->add_options( array(
        'text-color-default' => 'Negro',
        'text-color-2' => 'Blanco',
    ))->set_default_value( 'text-color-default' ),

    Field::create( 'tab', 'Header Flotante' ),
    Field::create( 'select', 'floating_header_logo', 'Versión del Logo')->add_options($logos_field_names),
    Field::create( 'complex', 'floating_header_bgc', 'Color de fondo' )->add_fields(array(
        Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->set_default_value(1),
        Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc')->set_default_value('#ffffff'),
        Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
    )),
    Field::create( 'select', 'floating_header_color_scheme', 'Color del Texto' )->add_options( array(
        'text-color-default' => 'Negro',
        'text-color-2' => 'Blanco',
    ))->set_default_value( 'text-color-default' ),
);

Container::create( 'header_options' ) 
    ->add_location( 'options', $header_page )
    // ->set_layout( 'grid' )
    // ->set_style( 'seamless' )
    ->add_fields($header_fields);

// -----------------------------------------------------------------------------------------------------------------------------------------------
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

// -----------------------------------------------------------------------------------------------------------------------------------------------
$custom_scripts_page = Options_Page::create( 'custom_scripts', 'Custom Scripts' )->set_parent( $theme_options_page );

Container::create( 'custom_scripts_options' ) 
    ->add_location( 'options', $custom_scripts_page )
    ->add_fields(array(
        Field::create( 'textarea', 'head_scripts' ),
        Field::create( 'textarea', 'footer_scripts' ),
        Field::create( 'message', 'Hint_1' )->set_description('Usar < script >...< /script >'),
        Field::create( 'message', 'Hint_2' )->set_description('Jquery : (function($){ ... })(jQuery)'),
    ));