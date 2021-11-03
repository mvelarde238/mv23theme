<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array( 
    Field::create( 'tab', 'Icono'),
    
    Field::create( 'radio', 'ielement','Seleccione que mostrar:')->set_orientation( 'horizontal' )->add_options( array(
        'icono' => 'Icono',
        'imagen' => 'Imagen',
    ))->set_width(20),
    Field::create( 'icon', 'iname', 'Icono' )->add_set( 'font-awesome' )->add_dependency('ielement','icono','=')->set_width(20),
    Field::create( 'image', 'iimage', 'Imágen' )->add_dependency('ielement','imagen','=')->set_width(20),

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
    ))->set_width(30),

    Field::create( 'image_select', 'iposition', 'Posición')->add_options(array(
        'left'  => array(
            'label' => 'Izquierda',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-left.png'
        ),
        'top'  => array(
            'label' => 'Arriba',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top.png'
        ),
        'right'  => array(
            'label' => 'Derecha',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-right.png'
        ),
    ))->set_width(30),


    // Field::create( 'image_select', 'ialign', 'Alineación')->add_options(array(
    //     'center'  => array(
    //         'label' => 'Centro',
    //         'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icono-centro.png'
    //     ),
    //     'flex-start'  => array(
    //         'label' => 'Arriba',
    //         'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icono-arriba.png'
    //     ),
    //     'flex-end'  => array(
    //         'label' => 'Abajo',
    //         'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icono-abajo.png'
    //     ),
    // ))->set_width(33)->add_dependency('iposition','top','!='),

    // Field::create( 'image_select', 'itopalign', 'Alineación')->add_options(array(
    //     'center'  => array(
    //         'label' => 'Centro',
    //         'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top.png'
    //     ),
    //     'left'  => array(
    //         'label' => 'Izquierda',
    //         'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top-left.png'
    //     ),
    //     'right'  => array(
    //         'label' => 'Derecha',
    //         'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/icon-top-right.png'
    //     ),
    // ))->set_width(33)->add_dependency('iposition','top','='),

    Field::create( 'number', 'ifontsize', 'Tamaño')->set_width(10)->set_default_value(40),
    Field::create( 'color', 'icolor', 'Color del ícono')->set_width(25),
    Field::create( 'checkbox', 'ihas_bgc','Activar fondo' )->set_text( 'Activar' )->set_width(10)->add_dependency('istyle','circle-outline','='),
    Field::create( 'color', 'ibgc', 'Color de Fondo')->set_width(25)->set_default_value(MAIN_COLOR)
        ->add_dependency('istyle','circle','=')
        ->add_dependency_group()
        ->add_dependency('istyle','circle-outline','=')
        ->add_dependency('ihas_bgc'),
    Field::create( 'select', 'itopalign', 'Alineación')->add_options(array(
        'center'  => 'Al Centro',
        'left'  => 'Izquierda',
        'right'  => 'Derecha',
    ))->add_dependency('iposition','top','=')->set_width(20),

    // Field::create( 'section', 'Texto' ),
    Field::create( 'tab', 'Texto'),
    Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 6 )->set_width(100),
    Field::create( 'select', 'ialign', 'Alineación Vertical')->add_options(array(
        'center'  => 'Al Centro',
        'flex-start'  => 'Arriba',
        'flex-end'  => 'Abajo',
    ))->set_width(33)->add_dependency('iposition','top','!='),
    Field::create( 'checkbox', 'center-all', 'Centrar ícono y texto' ),
);

$it_responsive_settings = array(
    Field::create( 'tab', 'Responsive' ),
    Field::create( 'checkbox', 'hide-icon-on-mobile' )->set_text( 'Ocultar Icono en moviles' )->hide_label(),
);

$icon_and_text_args = array(
    'edit_mode' => 'popup',
    'layout' => 'table',
    'fields' => array_merge($fields, $acciones_fields, $it_responsive_settings, $default_settings_fields),
    'title_template' => '<%= content %>'
);

$icon_and_text = Repeater_Group::create( 'Icono y texto', $icon_and_text_args );