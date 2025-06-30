<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

function create_columns_width_settings($options){
    $settings = array();

    foreach ($options as $option) {
        $image = ( isset($option['image']) ) ? $option['image'] : str_replace(' ', '-', $option['value']);

        $settings[ $option['value'] ] = array(
            'label' => $option['label'],
            'image' => BUILDER_PATH.'/assets/images/'.$image.'.png'
        );
    }

    return $settings;
}

function get_gap_placeholder($prefix) {
    $typography_css_vars = get_option('typography_css_vars', array());
    return $typography_css_vars['--'.$prefix.'-columns-gap'] ? $typography_css_vars['--'.$prefix.'-columns-gap'] : '20px';
}

Container::create( 'row_settings_container' ) 
    // ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        // DESKTOP -----------------------------------------------------------------------------------------------------------------------------------------------
        Field::create('tab','Desktop')->set_icon( 'dashicons-desktop' ),
        Field::create( 'image_select', 'l_grid_2',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => 'repeat(2, 1fr)', 'label' => '1/2', 'image' => '1fr-1fr' ),
                array( 'value' => '1fr 2fr', 'label' => '1/3 + 2/3' ),
                array( 'value' => '2fr 1fr', 'label' => '2/3 + 1/3' ),
                array( 'value' => '1fr 3fr', 'label' => '1/4 + 3/4' ),
                array( 'value' => '3fr 1fr', 'label' => '3/4 + 1/4' ),
                array( 'value' => 'auto 1fr', 'label' => 'Auto + Fluid' ),
                array( 'value' => '1fr auto', 'label' => 'Fluid + Auto' )
            ))
        ),
        Field::create( 'image_select', 'l_grid_3',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => 'repeat(3, 1fr)', 'label' => '1/3', 'image' => '1fr-1fr-1fr' ),
                array( 'value' => '1fr 2fr 1fr', 'label' => '1/4 + 2/4 + 1/4' ),
                array( 'value' => '2fr 1fr 1fr', 'label' => '1/2 + 1/4 + 1/4' ),
                array( 'value' => '1fr 1fr 2fr', 'label' => '1/4 + 1/4 + 1/2' ),
                array( 'value' => '1fr 6fr 1fr', 'label' => '1/8 + 6/8 + 1/8' ),
                array( 'value' => '2fr 1fr 2fr', 'label' => '2/5 + 1/5 + 2/5' )
            ))
        ),
        Field::create( 'image_select', 'l_grid_4',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => 'repeat(4, 1fr)', 'label' => '1/4', 'image' => '1fr-1fr-1fr-1fr' )
            ))
        ),
        Field::create( 'image_select', 'l_grid_5',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => 'repeat(5, 1fr)', 'label' => '1/5', 'image' => '1fr-1fr-1fr-1fr-1fr' )
            ))
        ),
        Field::create( 'image_select', 'l_grid_6',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => 'repeat(6, 1fr)', 'label' => '1/6', 'image' => '1fr-1fr-1fr-1fr-1fr-1fr' )
            ))
        ),
        Field::create( 'text', 'l_gap', __('Space between columns','mv23theme') )->set_placeholder( get_gap_placeholder('l') ),

        // TABLET -----------------------------------------------------------------------------------------------------------------------------------------------
        Field::create('tab','Tablet')->set_icon( 'dashicons-tablet' ),
        Field::create( 'image_select', 't_grid_2',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '2-columns-1fr' ),
                array( 'value' => '1fr 1fr', 'label' => '1/2 + 1/2' ),
                array( 'value' => '1fr 2fr', 'label' => '1/3 + 2/3' ),
                array( 'value' => '2fr 1fr', 'label' => '2/3 + 1/3' ),
                array( 'value' => '1fr 3fr', 'label' => '1/4 + 3/4' ),
                array( 'value' => '3fr 1fr', 'label' => '3/4 + 1/4' ),
                array( 'value' => 'auto 1fr', 'label' => 'Auto + Fluid' ),
                array( 'value' => '1fr auto', 'label' => 'Fluid + Auto' )
            ))
        ),
        Field::create( 'image_select', 't_grid_3',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '3-columns-1fr' ),
                array( 'value' => '1fr 1fr 1fr', 'label' => '1/3 + 1/3 + 1/3' ),
                array( 'value' => 'tablet-1-1de2-1de2', 'label' => '1 + 1/2 + 1/2' ),
                array( 'value' => 'tablet-1de2-1de2-1', 'label' => '1/2 + 1/2 + 1' ),
                array( 'value' => 'tablet-1de2-1-1de2', 'label' => '1/2 + 1 + 1/2' )
            ))
        ),
        Field::create( 'image_select', 't_grid_4',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '4-columns-1fr' ),
                array( 'value' => '1fr 1fr', 'label' => '1/2 + 1/2 + 1/2 + 1/2', 'image' => '1fr-1fr-m' )
            ))
        ),
        Field::create( 'image_select', 't_grid_5',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '5-columns-1fr' )
            ))
        ),
        Field::create( 'image_select', 't_grid_6',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '6-columns-1fr' )
            ))
        ),
        Field::create( 'text', 't_gap', __('Space between columns','mv23theme') )->set_placeholder( get_gap_placeholder('t') ),

        // MOBILE -----------------------------------------------------------------------------------------------------------------------------------------------
        Field::create('tab','Mobile')->set_icon( 'dashicons-smartphone' ),
        Field::create( 'image_select', 'm_grid_2',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '2-columns-1fr' ),
                array( 'value' => '1fr 1fr', 'label' => '1/2 + 1/2' ),
                array( 'value' => '1fr 2fr', 'label' => '1/3 + 2/3' ),
                array( 'value' => '2fr 1fr', 'label' => '2/3 + 1/3' ),
                array( 'value' => '1fr 3fr', 'label' => '1/4 + 3/4' ),
                array( 'value' => '3fr 1fr', 'label' => '3/4 + 1/4' ),
                array( 'value' => 'auto 1fr', 'label' => 'Auto + Fluid' ),
                array( 'value' => '1fr auto', 'label' => 'Fluid + Auto' )
            ))
        ),
        Field::create( 'image_select', 'm_grid_3',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '3-columns-1fr' ),
                array( 'value' => '1fr 1fr 1fr', 'label' => '1/3 + 1/3 + 1/3' ),
                array( 'value' => 'mobile-1-1de2-1de2', 'label' => '1 + 1/2 + 1/2', 'image' => 'tablet-1-1de2-1de2' ),
                // array( 'value' => 'mobile-1de2-1de2-1', 'label' => '1/2 + 1/2 + 1' ),
                // array( 'value' => 'mobile-1de2-1-1de2', 'label' => '1/2 + 1 + 1/2' )
            ))
        ),
        Field::create( 'image_select', 'm_grid_4',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '4-columns-1fr' ),
                array( 'value' => '1fr 1fr', 'label' => '1/2 + 1/2 + 1/2 + 1/2', 'image' => '1fr-1fr-m' )
            ))
        ),
        Field::create( 'image_select', 'm_grid_5',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '5-columns-1fr' )
            ))
        ),
        Field::create( 'image_select', 'm_grid_6',__('Columns Width','mv23theme'))->show_label()->add_options(
            create_columns_width_settings(array(
                array( 'value' => '1fr', 'label' => '100%', 'image' => '6-columns-1fr' )
            ))
        ),
        Field::create( 'text', 'm_gap', __('Space between columns','mv23theme') )->set_placeholder( get_gap_placeholder('m') )
    ));