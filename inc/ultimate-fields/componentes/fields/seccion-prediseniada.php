<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$predesigned_section = Repeater_Group::create( 'Seccion Prediseñada' )
->set_title( 'Seccion Prediseñada' )
->set_edit_mode( 'popup' )
->add_fields(array(
    Field::create( 'tab', 'Prediseñados' ),
    Field::create( 'image_select', 'section' )->add_options(array(
        'predesigned1'  => array(
            'label' => 'Prediseñado 1',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/predesigned1.png'
        ),
        'predesigned2'  => array(
            'label' => 'Prediseñado 2',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/predesigned2.png'
        ),
        'predesigned3'  => array(
            'label' => 'Prediseñado 3',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/predesigned3.png'
        ),
    ))->hide_label(),
    Field::create( 'section', 'predesigned1', 'Agregar 2 Editores de Texto, uno para la imagen y otro para el texto' )->add_dependency('section','predesigned1','=')->set_color( 'blue' ),
    Field::create( 'section', 'predesigned2', 'Agregar 3 Editores de Texto, uno para la imagen y otros 2 para los textos' )->add_dependency('section','predesigned2','=')->set_color( 'blue' ),
    Field::create( 'section', 'predesigned3', 'Agregar 2 Editores de Texto, uno para la imagen y otro para el texto' )->add_dependency('section','predesigned3','=')->set_color( 'blue' ),
    $components_repeater,
))
->add_fields($settings_fields_container->get_fields());