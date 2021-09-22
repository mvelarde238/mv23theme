<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$aspect_ratio_fields = array(
    Field::create( 'tab', 'Tamaño' ),
    Field::create( 'image_select', 'aspect_ratio' )->add_options(array(
        'aspect-ratio-default'  => array(
            'label' => 'default',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-default.png'
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
    Field::create( 'select', 'content_alignment','Alineación del Contenido')->add_options( array(
        'flex-start' => 'Arriba',
        'center' => 'Al centro',
        'flex-end' => 'Abajo',
        'space-between' => 'Distribuir'
    ))->add_dependency('aspect_ratio','aspect-ratio-default','!='),
);

$videocard_fields = array(
    Field::create( 'tab', 'Video Card' ),
    Field::create( 'video', 'bgvideo', 'Video de Fondo' ),
    Field::create( 'number', 'video_opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 ),
);

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    $componentes_field,
);

$card_margenes = $margenes;
$card_margenes[] = Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20);

$card_args = array(
    'title' => 'Card',
    'edit_mode' => 'popup',
    'fields' => array_merge($fields, $aspect_ratio_fields, $videocard_fields, $acciones_fields, $settings_fields, $card_margenes, $bordes, $box_shadow, $animation)
);

$card = Repeater_Group::create( 'Card', $card_args );