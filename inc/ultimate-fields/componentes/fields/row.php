<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$row_componentes_field = clone $components_repeater;
$row_componentes_field->add_group( $columnas );
// $row_componentes_field->add_group( $content_slider );
// $row_componentes_field->add_group( $items_grid );

$row = Repeater_Group::create( 'Fila' )
->set_title( 'Fila' )
->set_edit_mode( 'popup' )
->add_fields(array(
    Field::create( 'tab', 'Contenido' ),
    $row_componentes_field,
    Field::create( 'tab', 'Video de fondo' ),
    Field::create( 'video', 'bgvideo', 'Video de Fondo' ),
    Field::create( 'number', 'video_opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )
    Field::create( 'checkbox', 'parallax', 'Parallax' )->set_width( 25 )
))
->add_fields($settings_fields_container->get_fields());