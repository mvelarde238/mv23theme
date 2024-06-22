<?php
use Ultimate_Fields\Options_Page;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$logos_field_names = array();

add_action( 'admin_menu', function() {
	add_menu_page('Theme Options','Theme Options','manage_options','theme-options/theme-options-admin.php',function(){},'',4 );
});

if( !function_exists( 'rearrange_theme_options_submenu_order' ) ){
    function rearrange_theme_options_submenu_order( $menu_ord ){
        global $submenu;
        // Enable the next line to see all menu orders
        // echo '<pre>'.print_r($submenu['theme-options/theme-options-admin.php'],true).'</pre>';

        $arr = array();
        $arr[] = $submenu['theme-options/theme-options-admin.php'][5];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][6];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][0];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][7];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][3];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][2];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][4];
        $arr[] = $submenu['theme-options/theme-options-admin.php'][1];
        $submenu['theme-options/theme-options-admin.php'] = $arr;

        return $menu_ord;
    }
}
if(current_user_can('administrator')) add_filter( 'custom_menu_order', 'rearrange_theme_options_submenu_order');

// -----------------------------------------------------------------------------------------------------------------------------------------------
// $theme_options_page = Options_Page::create( 'theme-options', 'Theme Options' )->set_position( 2 );
$theme_options_page = Options_Page::create( 'theme-options', 'Theme Options' )->set_parent( 'theme-options/theme-options-admin.php' );
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

$logos_field_names['custom'] = 'Custom';

Container::create( 'main_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->set_layout( 'grid' )
    ->add_fields($main_options_fields);

$rrss_fields = array();
$rrss_fields[] = Field::create( 'repeater', 'rrss', 'Redes Sociales' )->set_add_text('Agregar')->hide_label()
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
        ));

Container::create( 'rrss_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->set_layout( 'grid' )
    ->set_title('Redes Sociales')
    ->add_fields($rrss_fields);

Container::create( 'global_options' ) 
    ->add_location( 'options', $theme_options_page )
    ->add_fields(array(
        Field::create( 'wp_object', 'theme_footer_post', 'Pie de página' )->add( 'posts', 'post_type=footer' ),
        Field::create( 'checkbox', 'activate_gm', 'Activar Google Maps' )->set_text('Activar'),
        Field::create( 'multiselect', 'gm_services','Google Map Services')->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
            'places' => 'Places'
        ))->add_dependency('activate_gm'),

        Field::create( 'multiselect', 'show_editor_in','El theme oculta el editor de texto en páginas y productos, mostrarlo en los siguientes lugares')->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
            'page' => 'Páginas',
            'product' => 'Productos'
        )),
        Field::create( 'complex', 'scroll_animations', 'Activar Animaciones Avanzadas' )->add_fields(array(
            Field::create( 'checkbox', 'activate' )->set_text('Activar')->hide_label()->set_width( 50 ),
            Field::create( 'checkbox', 'activate_indicators' )->set_text('Activar indicadores')->hide_label()->set_width( 50 )->add_dependency('activate'),
        )),
        Field::create( 'checkbox', 'disable_comments_styles', 'Desactivar theme styles en comentarios' )->set_text('Desactivar'),
    ));

// -----------------------------------------------------------------------------------------------------------------------------------------------
// $header_page = Options_Page::create( 'header', 'Header' )->set_parent( $theme_options_page );
$header_page = Options_Page::create( 'header', 'Header' )->set_parent( 'theme-options/theme-options-admin.php' );

$header_fields = array(
    Field::create( 'tab', 'Static Header' ),
    Field::create( 'select', 'static_header_logo', 'Versión del Logo')->add_options($logos_field_names),
    Field::create( 'image', 'custom_static_header_logo', 'Seleccionar logo' )->add_dependency('static_header_logo','custom','='),
    Field::create( 'complex', 'static_header_bgc', 'Color de fondo' )->add_fields(array(
        Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar'),
        Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc'),
        Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
    )),
    Field::create( 'select', 'static_header_color_scheme', 'Color del Texto' )->add_options( array(
        '' => 'Default',
        'text-color-1' => 'Negro',
        'text-color-2' => 'Blanco',
    ))->set_default_value( DEFAULT_TEXT_COLOR ),

    Field::create( 'tab', 'Sticky Header' ),
    Field::create( 'select', 'sticky_header_logo', 'Versión del Logo')->add_options($logos_field_names),
    Field::create( 'image', 'custom_sticky_header_logo', 'Seleccionar logo' )->add_dependency('sticky_header_logo','custom','='),
    Field::create( 'complex', 'sticky_header_bgc', 'Color de fondo' )->add_fields(array(
        Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->set_default_value(1),
        Field::create( 'color', 'bgc', 'Color' )->set_width( 50 )->add_dependency('add_bgc')->set_default_value('#ffffff'),
        Field::create( 'text', 'alpha', 'Transparencia' )->set_width( 25 )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
    )),
    Field::create( 'select', 'sticky_header_color_scheme', 'Color del Texto' )->add_options( array(
        '' => 'Default',
        'text-color-1' => 'Negro',
        'text-color-2' => 'Blanco',
    ))->set_default_value( DEFAULT_TEXT_COLOR ),
);

Container::create( 'header_options' ) 
    ->add_location( 'options', $header_page )
    ->add_fields($header_fields);

// -----------------------------------------------------------------------------------------------------------------------------------------------
$custom_scripts_page = Options_Page::create( 'custom_scripts', 'Custom Scripts' )->set_parent( 'theme-options/theme-options-admin.php' );

Container::create( 'custom_scripts_options' ) 
    ->add_location( 'options', $custom_scripts_page )
    ->add_fields(array(
        Field::create( 'textarea', 'head_scripts' )->set_attr(array(
            'data-type' => 'html'
        )),
        Field::create( 'textarea', 'footer_scripts' )->set_attr(array(
            'data-type' => 'html'
        )),
        Field::create( 'message', 'Hint_1' )->set_description('Usar < script >...< /script >'),
        Field::create( 'message', 'Hint_2' )->set_description('Jquery : (function($){ ... })(jQuery)'),
    ));