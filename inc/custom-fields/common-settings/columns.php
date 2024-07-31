<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Common_Settings;

$columns_settings = array(
	Field::create( 'tab', 'Columnas Settings' )
);

Container::create( '_column_fields' )
    ->add_fields( array(
        Field::create( 'tab', 'Fondo' )
    ))
    ->add_fields( Common_Settings::get_fields('background-image') )
    ->add_fields( Common_Settings::get_fields('video-background') )
    ->add_fields( array(
        Field::create( 'tab', 'Colores' ),
        Field::create( 'complex', 'color_de_fondo' )->add_fields(array(
            Field::create( 'checkbox', 'add_bgc')->set_text('Activar Color de Fondo')->hide_label(),
            Field::create( 'color', 'bgc', 'Color' )->add_dependency('add_bgc')->set_default_value('#ffffff')->hide_label(),
        ))->hide_label(),
        Field::create( 'select', 'color_scheme')->add_options( array(
            '' => 'Seleccionar color del texto',
            'default-scheme' => 'Negro',
            'dark-scheme' => 'Blanco',
        ))->hide_label(),
    ))
    ->add_fields( Common_Settings::get_fields('borders') )
    ->add_fields( Common_Settings::get_fields('box-shadow') )
    ->add_fields( array(
        Field::create( 'tab', 'Otros' ),
        Field::create( 'text', 'module_id', 'ID' )->set_width( 50 )->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
        ->set_description( 'Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )' ),
        Field::create( 'text', 'class', 'Clases' )->set_width( 50 )
        ->set_description( 'Clases de la sección, usar solo minúsculas y guiones ( -/_ )' ),
        Field::create( 'multiselect', 'theme_clases', 'Helpers' )->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options(array(
            'hide-br' => 'Ocultar saltos de línea en tablet y móviles',
            'hide-br-tablet' => 'Ocultar saltos de línea en tablet',
            'hide-br-mobile' => 'Ocultar saltos de línea en móviles',
        )),
        Field::create( 'select', 'tablet_order')->set_width( 50 )->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
        )),
        Field::create( 'select', 'mobile_order')->set_width( 50 )->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
        )),
        Field::create( 'select', 'content_alignment','Alineación del Contenido')->add_options( array(
            'flex-start' => 'Arriba',
            'center' => 'Al centro',
            'flex-end' => 'Abajo',
            'space-between' => 'Distribuir',
            'pinned' => 'Fijar Contenido'
        )),
    ));


for ($i=1; $i <= COLUMNS_QUANTITY; $i++) { 
    $column_settings = Field::create( 'complex', 'columna_'.$i.'_settings', 'Columna '.$i )->set_width( 25 )->load_from_container( '_column_fields' );
    if($i > 1) $column_settings->add_dependency('nth_columnas',($i-1),'>');
    array_push($columns_settings, $column_settings);
}

return Container::create( '_columns-settings' )->add_fields( $columns_settings );