<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$contenido_settings = array(
    Field::create( 'tab', 'Contenido' )
);

for ($i=1; $i <= $nth_columnas; $i++) { 
    $col = clone $columna;
    $col->set_name('columna_'.$i);
    if($i > 1) $col->add_dependency('nth_columnas',($i-1),'>');
    $contenido_settings[] = $col;
}

$columnas_internas_margenes = array(
    Field::create( 'tab', 'Márgenes' ),
    Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20)
);

$columnas_internas = Repeater_Group::create( 'Columnas Internas' )
    ->set_title( 'Columnas Internas' )
    ->set_edit_mode( 'popup' )
    ->add_fields( 
        array_merge($contenido_settings, $row_settings, $settings_fields, $columns_settings, $columnas_internas_margenes, $bordes, $box_shadow, $animation)
);