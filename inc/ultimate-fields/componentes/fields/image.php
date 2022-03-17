<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$image_fields = array(
    Field::create( 'tab', 'Contenido' ),
    Field::create('radio', 'type', 'Seleccione el tipo de contenido:')->set_orientation('horizontal')->add_options(array(
        'image' => 'Imágen',
        'video' => 'Video'
    )),
    Field::create( 'image', 'image' )->hide_label()->add_dependency('type','image','='),
    Field::create( 'video', 'bgvideo', 'Video de Fondo' )->add_dependency('type','video','='),
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
);


$image_args = array(
    'title' => 'Imágen / Video',
    'edit_mode' => 'popup',
    'fields' => array_merge($image_fields, $acciones_fields, $settings_fields, $margenes, $bordes, $box_shadow, $animation)
);

$image = Repeater_Group::create( 'Imágen', $image_args );