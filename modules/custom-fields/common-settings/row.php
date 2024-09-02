<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

return Container::create( '_row-settings' )->add_fields( array(
	Field::create( 'tab', 'Row Settings', 'Cantidad'),
    Field::create( 'select', 'quantity', 'Cantidad de Columnas' )->set_width( 25 )->add_options( array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ) )->set_default_value( '2' ),

    Field::create( 'select', 'l_grid_2',__('Columns Width','default'))->set_width( 25 )->add_options( array(
            'repeat(2, 1fr)' => '1/2 + 1/2',
            '2fr 1fr' => '2/3 + 1/3',
            '1fr 2fr' => '1/3 + 2/3',
            '1fr 3fr' => '1/4 + 3/4',
            '3fr 1fr' => '3/4 + 1/4',
            'auto 1fr' => 'auto + fluid',
        ))->add_dependency('quantity','2','='),
    Field::create( 'select', 't_grid_2',__('Width on tablet','default'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr' => '1/2 + 1/2',
            '2fr 1fr' => '2/3 + 1/3',
            '1fr 2fr' => '1/3 + 2/3',
            '1fr 3fr' => '1/4 + 3/4',
            '3fr 1fr' => '3/4 + 1/4',
            'auto 1fr' => 'auto + fluid',
        ))->add_dependency('quantity','2','='),
    Field::create( 'select', 'm_grid_2',__('Width on mobile','default'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr' => '1/2 + 1/2',
            '2fr 1fr' => '2/3 + 1/3',
            '1fr 2fr' => '1/3 + 2/3',
            '1fr 3fr' => '1/4 + 3/4',
            '3fr 1fr' => '3/4 + 1/4',
            'auto 1fr' => 'auto + fluid',
        ))->add_dependency('quantity','2','='),

    Field::create( 'select', 'l_grid_3', __('Columns Width','default'))->set_width( 25 )->add_options( array(
            'repeat(3, 1fr)' => '1/3 + 1/3 + 1/3',
            '1fr 2fr 1fr' => '1/4 + 2/4 + 1/4',
            '2fr 1fr 1fr' => '1/2 + 1/4 + 1/4',
            '1fr 1fr 2fr' => '1/4 + 1/4 + 1/2',
            '1fr 6fr 1fr' => '1/8 + 6/8 + 1/8',
        ))->add_dependency('quantity','3','='),
    Field::create( 'select', 't_grid_3', __('Width on tablet','default'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr 1fr' => '1/3 + 1/3 + 1/3',
            'tablet-1-1de2-1de2' => '1 + 1/2 + 1/2',
            'tablet-1de2-1de2-1' => '1/2 + 1/2 + 1',
            'tablet-1de2-1-1de2' => '1/2 + 1 + 1/2',
        ))->add_dependency('quantity','3','='),
    Field::create( 'select', 'm_grid_3', __('Width on mobile','default'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr 1fr' => '1/3 + 1/3 + 1/3'
        ))->add_dependency('quantity','3','='),

    Field::create( 'select', 'l_grid_4', __('Columns Width','default'))->set_width( 25 )->add_options( array(
            'repeat(4, 1fr)' => '1/4 + 1/4 + 1/4 + 1/4'
        ))->add_dependency('quantity','4','='),
    Field::create( 'select', 't_grid_4', __('Width on tablet','default'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr 1fr 1fr' => '1/2 + 1/2 + 1/2 + 1/2',
        ))->add_dependency('quantity','4','='),
    Field::create( 'select', 'm_grid_4', __('Width on mobile','default'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr 1fr 1fr' => '1/2 + 1/2 + 1/2 + 1/2',
        ))->add_dependency('quantity','4','='),
));