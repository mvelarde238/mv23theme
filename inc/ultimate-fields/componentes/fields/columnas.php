<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$fields = array( Field::create( 'tab', 'Contenido' ) );

// $columna->add_group( $content_slider );
// $columna->add_group( $columnas_simples );
$columna->add_group( $columnas_internas );
// $columna->add_group( $items_grid );
// $columna->add_group( $predesigned_section );

for ($i=1; $i <= $nth_columnas; $i++) { 
    $col = clone $columna;
    $col->set_name('columna_'.$i);
    if($i > 1) $col->add_dependency('nth_columnas',($i-1),'>');
    $fields[] = $col;
}

$columnas_settings_fields = $settings_fields;
$columnas_settings_fields[] = Field::create( 'checkbox', 'add_video_bg' )->set_text( 'Agregar video de fondo' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' );
$columnas_settings_fields[] = Field::create( 'video', 'bgvideo', 'Video de Fondo' )->set_width( 25 )->add_dependency('add_video_bg');
$columnas_settings_fields[] = Field::create( 'number', 'video_opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width( 75 )->add_dependency('add_video_bg');

$columnas = Repeater_Group::create( 'Columnas' )
    ->set_title( 'Columnas' )
    // ->set_edit_mode( 'popup' )
    ->add_fields( 
       array_merge($fields, $row_settings, $columns_settings, $columnas_settings_fields, $margenes, $bordes, $box_shadow, $animation)
);