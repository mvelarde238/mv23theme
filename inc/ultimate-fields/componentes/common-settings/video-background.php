<?php
use Ultimate_Fields\Field;

$video_background_fields = array(
    // Field::create( 'tab', 'Video Background'),
    Field::create( 'checkbox', 'add_video_bg' )->set_text( 'Agregar video de fondo' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
    Field::create( 'video', 'bgvideo', 'Video de Fondo' )->set_width( 20 )->add_dependency('add_video_bg'),
    Field::create( 'complex', 'video_settings' )->add_fields(array(
        Field::create( 'color', 'bgc', 'Color de Fondo' )->set_default_value('#000000')->set_width(20),
        Field::create( 'checkbox', 'autoplay', 'AutoPlay' )->set_text( 'Activar' )->set_width(20),
        Field::create( 'checkbox', 'muted', 'Muted' )->set_text( 'Activar' )->set_width(20),
        Field::create( 'checkbox', 'loop', 'Bucle' )->set_text( 'Activar' )->set_width(20),
        Field::create( 'number', 'opacity', 'Transparencia' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width(20)
    ))->add_dependency('add_video_bg')->set_width(75)
);