<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'icon' )
    ->add_location( 'shortcode', array(
        'template' => '<div style="background-color:#f1f1f1"><p style="text-align:<%= textalign %>;margin-bottom:0"><span class="fa <%= name %>" style="font-size:<%= fontsize %>px;"></span></p><p style="text-align:center;font-size:13px;margin-bottom:0">Icono simple: [i name="<%= name %>"]</p></div>'
        // 'preview_image' => 48
    ))
    ->set_layout( 'grid' )
    ->add_fields(array(
        Field::create( 'tab', 'Icono'),
        Field::create( 'radio', 'element','Seleccione que mostrar antes del texto:')->set_orientation( 'horizontal' )->add_options( array(
            'icono' => 'Icono',
            'imagen' => 'Imagen',
        ))->set_width(50),
        Field::create( 'icon', 'name', 'Icono' )->add_set( 'font-awesome' )->set_width(50)->add_dependency('element','icono','='),
        Field::create( 'image', 'image', 'Imágen' )->set_width(50)->add_dependency('element','imagen','='),

        Field::create( 'tab', 'Settings'),
        Field::create( 'number', 'fontsize', 'Tamaño')->set_width(20)->set_default_value(40),
        Field::create( 'color', 'color', 'Color del ícono')->set_width(20),
        Field::create( 'select', 'textalign', 'Alineación')->add_options( array(
            'left' => 'Izquierda',
            'center' => 'Centro',
            'right' => 'Derecha'
        ))->set_width(20),
        Field::create( 'select', 'style', 'Estilo')->add_options( array(
            'mv23theme' => 'Normal',
            'circle' => 'Circular',
            'circle-outline' => 'Circular y Lineal',
        ))->set_width(20),
        Field::create( 'checkbox', 'has_bgc','Activar color de fondo' )->set_text( 'Activar' )->set_width(20)->add_dependency('style','circle-outline','='),
        Field::create( 'color', 'bgc', 'Color de Fondo')->set_width(25)
            ->add_dependency('style','circle','=')
            ->add_dependency_group()
            ->add_dependency('style','circle-outline','=')
            ->add_dependency('has_bgc'),
        Field::create( 'number', 'bgc_alpha', 'Transparencia del fondo' )->set_width(75)->enable_slider( 5, 100 )->set_default_value(100)->set_step( 5 )
            ->add_dependency('style','circle','=')
            ->add_dependency_group()
            ->add_dependency('style','circle-outline','=')
            ->add_dependency('has_bgc'),

        Field::create( 'complex', 'enlace' )->rows_layout()->add_fields(array(
            Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
                '' => 'Desactivar',
                'interna' => 'Página Interna',
                'externa' => 'Página Externa',
            )),
            Field::create( 'wp_object', 'post', 'URL Interna' )->add( 'posts' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
            Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
            Field::create( 'checkbox', 'new_tab' )->set_text( 'Abrir en una nueva ventana.' )->hide_label()->add_dependency('url_type'),
        ))->hide_label(),
    ));

// add_filter( 'uf.icon.ultimate-icons', 'my_theme_icon_set' );
// function my_theme_icon_set() {
//     return array(
//         'prefix'     => 'materialize-icons',
//         'name'       => 'Materialize Icons',
//         // Optional: This stylesheet will be loaded in the admin
//         // 'stylesheet' => get_stylesheet_directory_uri() . '/css/icons.css',
//         'stylesheet' => 'https://fonts.googleapis.com/icon?family=Material+Icons',
//         // A version, used to load the latest CSS
//         // 'version'    => '4.7.1',
//         'groups'     => array(
//             array(
//                 'groupName' => 'Admin Menu Icons',
//                 'icons'     => array( 'shopping_cart', 'local_shipping' )
//             ),
//         )
//     );
// }