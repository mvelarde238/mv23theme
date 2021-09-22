<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

// $componentes_field->add_group( $columnas_internas );

$item_fields = array( Field::create( 'tab', 'Contenido' ) );
array_push($item_fields, $componentes_field);

$fields = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'repeater', 'grid_items', '' )->set_add_text('Agregar')
        ->add_group('Item', array(
            'edit_mode' => 'popup',
            'fields' => array_merge($item_fields,$settings_fields,$bordes,$box_shadow,$animation)
        ))
);

$items_grid_margenes = $margenes;
$items_grid_margenes[] = Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(0);

$responsive_settings = array(
    Field::create( 'tab', 'Items por fila' ),
    Field::create( 'number', 'items_in_desktop', 'En desktop' )->set_width( 33 )->enable_slider( 1, 12 )->set_default_value(3),
    Field::create( 'number', 'items_in_tablet', 'En tablet' )->set_width( 33 )->enable_slider( 1, 12 )->set_default_value(2),
    Field::create( 'number', 'items_in_mobile', 'En móviles' )->set_width( 33 )->enable_slider( 1, 12 )->set_default_value(1),
);


$items_grid = Repeater_Group::create( 'Grid de Items' )
    ->set_title( 'Grid de Items' )
    ->set_edit_mode( 'popup' )
    ->add_fields( 
       array_merge($fields, $responsive_settings, $settings_fields, $items_grid_margenes, $bordes, $box_shadow, $animation)
);