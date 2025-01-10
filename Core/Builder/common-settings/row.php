<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

return Container::create( '_row-settings' )->add_fields( array(
	Field::create( 'tab', __('Row Settings','mv23theme') ),
    Field::create( 'select', 'quantity', __('Columns quantity','mv23theme') )->set_attr( 'style', 'width:25%;background:#fafafa;' )->add_options( array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ) )->set_default_value( '2' )->set_width( 25 ),

    Field::create( 'select', 'l_grid_2',__('Columns Width','mv23theme'))->add_options( array(
            'repeat(2, 1fr)' => '1/2 + 1/2',
            '1fr 2fr' => '1/3 + 2/3',
            '2fr 1fr' => '2/3 + 1/3',
            '1fr 3fr' => '1/4 + 3/4',
            '3fr 1fr' => '3/4 + 1/4',
            'auto 1fr' => 'auto + fluid',
            '1fr auto' => 'fluid + auto',
        ))->add_dependency('quantity','2','=')->set_width( 25 ),
    
    // Field::create( 'image_select', 'l_grid_2',__('Columns Width','mv23theme'))->set_width( 25 )->show_label()->add_options(array(
    //     'repeat(2, 1fr)'  => array(
    //         'label' => '1/2 + 1/2',
    //         'image' => BUILDER_PATH.'/assets/images/1fr-1fr.png'
    //     ),
    //     '1fr 2fr'  => array(
    //         'label' => '1/3 + 2/3',
    //         'image' => BUILDER_PATH.'/assets/images/1fr-2fr.png'
    //     ),
    //     '2fr 1fr'  => array(
    //         'label' => '2/3 + 1/3',
    //         'image' => BUILDER_PATH.'/assets/images/2fr-1fr.png'
    //     ),
    //     '1fr 3fr'  => array(
    //         'label' => '1/4 + 3/4',
    //         'image' => BUILDER_PATH.'/assets/images/1fr-3fr.png'
    //     ),
    //     '3fr 1fr'  => array(
    //         'label' => '3/4 + 1/4',
    //         'image' => BUILDER_PATH.'/assets/images/3fr-1fr.png'
    //     ),
    //     'auto 1fr'  => array(
    //         'label' => 'Auto + Fluid',
    //         'image' => BUILDER_PATH.'/assets/images/auto-1fr.png'
    //     ),
    //     '1fr auto'  => array(
    //         'label' => 'Fluid + Auto',
    //         'image' => BUILDER_PATH.'/assets/images/1fr-auto.png'
    //     ),
    // ))->add_dependency('quantity','2','='),

    Field::create( 'select', 't_grid_2',__('Width on tablet','mv23theme'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr' => '1/2 + 1/2',
            '2fr 1fr' => '2/3 + 1/3',
            '1fr 2fr' => '1/3 + 2/3',
            '1fr 3fr' => '1/4 + 3/4',
            '3fr 1fr' => '3/4 + 1/4',
            'auto 1fr' => 'auto + fluid',
        ))->add_dependency('quantity','2','='),
    Field::create( 'select', 'm_grid_2',__('Width on mobile','mv23theme'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr' => '1/2 + 1/2',
            '2fr 1fr' => '2/3 + 1/3',
            '1fr 2fr' => '1/3 + 2/3',
            '1fr 3fr' => '1/4 + 3/4',
            '3fr 1fr' => '3/4 + 1/4',
            'auto 1fr' => 'auto + fluid',
        ))->add_dependency('quantity','2','='),

    Field::create( 'select', 'l_grid_3', __('Columns Width','mv23theme'))->set_width( 25 )->add_options( array(
            'repeat(3, 1fr)' => '1/3 + 1/3 + 1/3',
            '1fr 2fr 1fr' => '1/4 + 2/4 + 1/4',
            '2fr 1fr 1fr' => '1/2 + 1/4 + 1/4',
            '1fr 1fr 2fr' => '1/4 + 1/4 + 1/2',
            '1fr 6fr 1fr' => '1/8 + 6/8 + 1/8',
        ))->add_dependency('quantity','3','='),
    Field::create( 'select', 't_grid_3', __('Width on tablet','mv23theme'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr 1fr' => '1/3 + 1/3 + 1/3',
            'tablet-1-1de2-1de2' => '1 + 1/2 + 1/2',
            'tablet-1de2-1de2-1' => '1/2 + 1/2 + 1',
            'tablet-1de2-1-1de2' => '1/2 + 1 + 1/2',
        ))->add_dependency('quantity','3','='),
    Field::create( 'select', 'm_grid_3', __('Width on mobile','mv23theme'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr 1fr' => '1/3 + 1/3 + 1/3',
            'mobile-1-1de2-1de2' => '1 + 1/2 + 1/2'
        ))->add_dependency('quantity','3','='),

    Field::create( 'select', 'l_grid_4', __('Columns Width','mv23theme'))->set_width( 25 )->add_options( array(
            'repeat(4, 1fr)' => '1/4 + 1/4 + 1/4 + 1/4'
        ))->add_dependency('quantity','4','='),
    Field::create( 'select', 't_grid_4', __('Width on tablet','mv23theme'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr' => '1/2 + 1/2 + 1/2 + 1/2',
        ))->add_dependency('quantity','4','='),
    Field::create( 'select', 'm_grid_4', __('Width on mobile','mv23theme'))->set_width( 25 )->add_options( array(
            '1f' => '100%',
            '1fr 1fr' => '1/2 + 1/2 + 1/2 + 1/2',
        ))->add_dependency('quantity','4','='),

    Field::create( 'message', 'gap_wrapper', __('Space between columns','mv23theme') )->set_attr( 'style', 'width:25%;background:#fafafa;' )->add_dependency('quantity','1','!=')->set_width( 25 ),
    Field::create( 'number', 'l_gap', __('Gap on desktop','mv23theme') )->set_placeholder('20')->set_default_value('20')->set_suffix('px')->add_dependency('quantity','1','!=')->set_width( 25 ),
    Field::create( 'number', 't_gap', __('Gap on tablet','mv23theme') )->set_placeholder('20')->set_default_value('20')->set_suffix('px')->add_dependency('quantity','1','!=')->set_width( 25 ),
    Field::create( 'number', 'm_gap', __('Gap on mobile','mv23theme') )->set_placeholder('20')->set_default_value('20')->set_suffix('px')->add_dependency('quantity','1','!=')->set_width( 25 ),
));