<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Contenido'),
    
    Field::create( 'image_select', 'istyle', 'Estilo')->add_options(array(
        'default'  => array(
            'label' => 'Normal',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-default.png'
        ),
        'circle'  => array(
            'label' => 'Circular',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-circle.png'
        ),
        'circle-outline'  => array(
            'label' => 'Circular y Lineal',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-circle-outline.png'
        ),
    ))->set_width(33),

    Field::create( 'image_select', 'iposition', 'Posición')->add_options(array(
        'left'  => array(
            'label' => 'Izquierda',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-left.png'
        ),
        'right'  => array(
            'label' => 'Derecha',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-right.png'
        ),
        'top'  => array(
            'label' => 'Arriba',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top.png'
        ),
    ))->set_width(33),

    Field::create( 'image_select', 'ialign', 'Alineación')->add_options(array(
        'center'  => array(
            'label' => 'Centro',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icono-centro.png'
        ),
        'flex-start'  => array(
            'label' => 'Arriba',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icono-arriba.png'
        ),
        'flex-end'  => array(
            'label' => 'Abajo',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icono-abajo.png'
        ),
    ))->set_width(33)->add_dependency('iposition','top','!='),

    Field::create( 'image_select', 'itopalign', 'Alineación')->add_options(array(
        'center'  => array(
            'label' => 'Centro',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top.png'
        ),
        'left'  => array(
            'label' => 'Izquierda',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top-left.png'
        ),
        'right'  => array(
            'label' => 'Derecha',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top-right.png'
        ),
    ))->set_width(33)->add_dependency('iposition','top','='),

    Field::create( 'radio', 'ielement','Seleccione que mostrar:')->set_orientation( 'horizontal' )->add_options( array(
        'icono' => 'Icono',
        'imagen' => 'Imagen',
    ))->set_width(25),
    Field::create( 'icon', 'iname', 'Icono' )->add_set( 'font-awesome' )->set_width(25)->add_dependency('ielement','icono','='),
    Field::create( 'image', 'iimage', 'Imágen' )->set_width(15)->add_dependency('ielement','imagen','='),
    Field::create( 'number', 'ifontsize', 'Tamaño')->set_width(25)->set_default_value(40),
    Field::create( 'color', 'icolor', 'Color del ícono')->set_width(25),
    
    Field::create( 'checkbox', 'ihas_bgc','Activar color de fondo' )->set_text( 'Activar' )->set_width(20)->add_dependency('istyle','circle-outline','='),
    Field::create( 'color', 'ibgc', 'Color de Fondo')->set_width(20)
        ->add_dependency('istyle','circle','=')
        ->add_dependency_group()
        ->add_dependency('istyle','circle-outline','=')
        ->add_dependency('ihas_bgc'),
    Field::create( 'number', 'ibgc_alpha', 'Transparencia del fondo' )->set_width(60)->enable_slider( 5, 100 )->set_default_value(100)->set_step( 5 )
        ->add_dependency('istyle','circle','=')
        ->add_dependency_group()
        ->add_dependency('istyle','circle-outline','=')
        ->add_dependency('ihas_bgc'),
    Field::create( 'section', 'Texto' ),
    Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 5 )->set_width(100),
    Field::create( 'checkbox', 'center-all' )->set_text( 'Centrar icono y texto' )->hide_label(),

    // Field::create( 'complex', 'ienlace' )->rows_layout()->add_fields(array(
    //     Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
    //             '' => 'Desactivar',
    //             'interna' => 'Página Interna',
    //             'externa' => 'Página Externa',
    //     )),
    //     Field::create( 'wp_object', 'post', 'URL Interna' )->add( 'posts' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
    //     Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
    //     Field::create( 'checkbox', 'new_tab' )->set_text( 'Abrir en una nueva ventana.' )->hide_label()->add_dependency('url_type'),
    // ))->hide_label()->set_width(50),
);

$it_responsive_settings = array(
    Field::create( 'tab', 'Responsive' ),
    Field::create( 'checkbox', 'hide-icon-on-mobile' )->set_text( 'Ocultar Icono en moviles' )->hide_label(),
);

$icon_and_text_args = array(
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $default_settings_fields, $acciones_fields, $it_responsive_settings),
    'title_template' => '<%= content %>'
);

$icon_and_text = Repeater_Group::create( 'Icono y texto', $icon_and_text_args );