<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$columns_settings = array(
	Field::create( 'tab', 'Columnas Settings' )
);

Container::create( '_column_fields' )
    ->add_fields( array(
        Field::create( 'select', 'tablet_order')->set_width( 50 )->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
        )),
        Field::create( 'select', 'mobile_order')->set_width( 50 )->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
        )),
        Field::create( 'select', 'content_alignment',__('Content alignment','default'))->add_options( array(
            'flex-start' => __('Top','default'),
            'center' => __('Center','default'),
            'flex-end' => __('Bottom','default'),
            'space-between' => __('Distribute','default'),
            'pinned' => __('Fixed','default'),
        )),
        Field::create( 'common_settings_control', 'settings' )->set_container( 'common_settings_container' )
    ));


for ($i=1; $i <= COLUMNS_QUANTITY; $i++) { 
    $column_settings = Field::create( 'complex', 'column_'.$i.'_settings', 'Column '.$i )
        ->load_from_container( '_column_fields' )
        ->set_layout( 'row' )
        ->set_width( 25 );

    if($i > 1) $column_settings->add_dependency('quantity',($i-1),'>');
    array_push($columns_settings, $column_settings);
}

return Container::create( '_columns-settings' )->add_fields( $columns_settings );