<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$row_componentes_field = clone $componentes_field;
$row_componentes_field->add_group( $columnas );
// $row_componentes_field->add_group( $content_slider );
// $row_componentes_field->add_group( $items_grid );

$row_fields = array(
    Field::create( 'tab', 'Contenido' ),
    $row_componentes_field,
);
     
$row_settings_fields = $settings_fields;
$row_settings_fields[] = Field::create( 'select', 'layout')->set_width( 25 )->add_options( array(
    'layout1' => 'Estándar',
    'layout2' => 'Fondo extendido / Contenido centrado',
    'layout3' => 'Todo extendido',
));
$row_settings_fields[] = Field::create( 'checkbox', 'parallax', 'Parallax' )->set_width( 25 );

$row_video_settings = array(
    Field::create( 'tab', 'Video de fondo' ),
    Field::create( 'video', 'bgvideo', 'Video de Fondo' )
);

$row = Repeater_Group::create( 'Fila' )
    ->set_title( 'Fila' )
    ->set_edit_mode( 'popup' )
    ->add_fields( 
       array_merge($row_fields, $row_settings_fields, $row_video_settings, $box_shadow, $animation)
);