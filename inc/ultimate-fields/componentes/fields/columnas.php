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
    
$columnas = Repeater_Group::create( 'Columnas' )
->set_title( 'Columnas' )
->add_fields( $fields )
->add_fields( $row_settings )
->add_fields( $columns_settings )
->add_fields($settings_fields_container->get_fields());