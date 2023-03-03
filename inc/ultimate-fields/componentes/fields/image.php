<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$image = Repeater_Group::create( 'Imágen', array(
    'title' => 'Imágen / Video',
    'edit_mode' => 'popup',
    'fields' => array(
        Field::create( 'tab', 'Contenido' ),
        Field::create('radio', 'type', 'Seleccione el tipo de contenido:')->set_orientation('horizontal')->add_options(array(
            'image' => 'Imágen',
            'video' => 'Video'
        )),
        Field::create( 'image', 'image' )->hide_label()->add_dependency('type','image','='),

        Field::create( 'radio', 'video_source','Seleccione el origen del video:')->set_orientation( 'horizontal' )->add_options( array(
            'selfhosted' => 'Medios',
            'youtube' => 'Youtube'
        ))->add_dependency('type', 'video', '=')->set_width(50),
        Field::create( 'text', 'youtube_url', 'URL')->add_dependency('type', 'video', '=')->add_dependency('video_source','youtube','=')->set_width(50),
        Field::create( 'video', 'bgvideo', 'Video de Fondo' )->add_dependency('type','video','=')->add_dependency('video_source','selfhosted','=')->set_width(50),
        Field::create( 'number', 'video_opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->add_dependency('type','video','='),
        Field::create( 'tab', 'Tamaño' ),
        Field::create( 'image_select', 'aspect_ratio' )->add_options(array(
            'aspect-ratio-default'  => array(
                'label' => 'default',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-default-b.png'
            ),
            'aspect-ratio-4-3'  => array(
                'label' => '4:3',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-4-3.png'
            ),
            'aspect-ratio-1-1'  => array(
                'label' => '1:1',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-1-1.png'
            ),
            'aspect-ratio-16-9'  => array(
                'label' => '16:9',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-16-9.png'
            ),
            'aspect-ratio-2-1'  => array(
                'label' => '16:9',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-2-1.png'
            ),
            'aspect-ratio-2_5-1'  => array(
                'label' => '2.5:1',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-2_5-1.png'
            ),
            'aspect-ratio-4-1'  => array(
                'label' => '4:1',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-4-1.png'
            ),
            'aspect-ratio-3-4'  => array(
                'label' => '3:4',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-3-4.png'
            ),
            'aspect-ratio-9-16'  => array(
                'label' => '9:16',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-9-16.png'
            ),
            'aspect-ratio-1-2'  => array(
                'label' => '1:2',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-1-2.png'
            ),
            'aspect-ratio-1-2_5'  => array(
                'label' => '1:2.5',
                'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-1-2_5.png'
            ),
        )),
        Field::create( 'select', 'alignment','Alineación de la imágen')->add_options( array(
            'left' => 'Izquierda',
            'center' => 'Centrar',
            'right' => 'Derecha'
        ))->add_dependency('aspect_ratio','aspect-ratio-default','='),
    ),
))
->add_fields($acciones_fields)
->add_fields($settings_fields_container->get_fields());