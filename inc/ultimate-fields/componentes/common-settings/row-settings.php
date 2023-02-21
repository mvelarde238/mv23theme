<?php
use Ultimate_Fields\Field;

$row_settings = array(
	Field::create( 'tab', 'Row Settings', 'Cantidad'),
    Field::create( 'select', 'nth_columnas', 'Cantidad de Columnas' )->set_width( 25 )->add_options( array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ) )->set_default_value( '2' ),
    Field::create( 'select', 'special_widths','Ancho de las columnas')->set_width( 25 )->add_options( array(
            '' => '1/2 + 1/2',
            'columnas-2de3-1de3' => '2/3 + 1/3',
            'columnas-1de3-2de3' => '1/3 + 2/3',
            'columnas-1de4-3de4' => '1/4 + 3/4',
            'columnas-3de4-1de4' => '3/4 + 1/4',
        ))->add_dependency('nth_columnas','2','='),
    Field::create( 'select', 'tablet_widths','Ancho en Tablets')->set_width( 25 )->add_options( array(
            '' => '100%',
            'tablet-1de2-1de2' => '1/2 + 1/2',
            'tablet-2de3-1de3' => '2/3 + 1/3',
            'tablet-1de3-2de3' => '1/3 + 2/3',
            'tablet-1de4-3de4' => '1/4 + 3/4',
            'tablet-3de4-1de4' => '3/4 + 1/4',
        ))->add_dependency('nth_columnas','2','='),
    Field::create( 'select', 'mobile_widths','Ancho en Móviles')->set_width( 25 )->add_options( array(
            '' => '100%',
            'mobile-1de2-1de2' => '1/2 + 1/2',
            'mobile-2de3-1de3' => '2/3 + 1/3',
            'mobile-1de3-2de3' => '1/3 + 2/3',
            'mobile-1de4-3de4' => '1/4 + 3/4',
            'mobile-3de4-1de4' => '3/4 + 1/4',
        ))->add_dependency('nth_columnas','2','='),
    Field::create( 'select', 'special_widths_3', 'Ancho de las columnas')->set_width( 25 )->add_options( array(
            '' => '1/3 + 1/3 + 1/3',
            'columnas-1de4-2de4-1de4' => '1/4 + 2/4 + 1/4',
            'columnas-1de2-1de4-1de4' => '1/2 + 1/4 + 1/4',
            'columnas-1de4-1de4-1de2' => '1/4 + 1/4 + 1/2',
        ))->add_dependency('nth_columnas','3','='),
    Field::create( 'select', 'tablet_widths_3', 'Ancho en Tablets')->set_width( 25 )->add_options( array(
            '' => '100%',
            'tablet-1de3-1de3-1de3' => '1/3 + 1/3 + 1/3',
            'tablet-1-1de2-1de2' => '1 + 1/2 + 1/2',
            'tablet-1de2-1de2-1' => '1/2 + 1/2 + 1',
            'tablet-1de2-1-1de2' => '1/2 + 1 + 1/2',
        ))->add_dependency('nth_columnas','3','='),
    Field::create( 'select', 'mobile_widths_3', 'Ancho en Móviles')->set_width( 25 )->add_options( array(
            '' => '100%',
            'mobile-1de3-1de3-1de3' => '1/3 + 1/3 + 1/3',
            // 'mobile-1de2-1-1de2' => '1/2 + 1 + 1/2',
            // 'mobile-1-1de2-1de2' => '1 + 1/2 + 1/2',
            // 'mobile-1de2-1de2-1' => '1/2 + 1/2 + 1',
        ))->add_dependency('nth_columnas','3','='),
    Field::create( 'select', 'tablet_widths_4', 'Ancho en Tablets')->set_width( 25 )->add_options( array(
            '' => '100%',
            'tablet-1de2-1de2-1de2-1de2' => '1/2 + 1/2 + 1/2 + 1/2',
        ))->add_dependency('nth_columnas','4','='),
    Field::create( 'select', 'mobile_widths_4', 'Ancho en Móviles')->set_width( 25 )->add_options( array(
            '' => '100%',
            'mobile-1de2-1de2-1de2-1de2' => '1/2 + 1/2 + 1/2 + 1/2',
        ))->add_dependency('nth_columnas','4','='),
);