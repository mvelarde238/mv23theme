<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'column_settings_container' ) 
    // ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        // DESKTOP -----------------------------------------------------------------------------------------------------------------------------------------------
        Field::create('tab','Desktop')->set_icon( 'dashicons-desktop' ),
        Field::create( 'select', 'l_order', __('Order','mv23theme'))->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'
        )),
        Field::create( 'select', 'l_content_alignment',__('Content Alignment','mv23theme'))->add_options( array(
            'flex-start' => __('Top','mv23theme'),
            'center' => __('Center','mv23theme'),
            'flex-end' => __('Bottom','mv23theme'),
            'space-between' => __('Distribute','mv23theme'),
            'pinned' => __('Fixed','mv23theme'),
        )),

        // TABLET -----------------------------------------------------------------------------------------------------------------------------------------------
        Field::create('tab','Tablet')->set_icon( 'dashicons-tablet' ),
        Field::create( 'select', 't_order', __('Order','mv23theme'))->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'
        )),
        Field::create( 'select', 't_content_alignment',__('Content Alignment','mv23theme'))->add_options( array(
            'flex-start' => __('Top','mv23theme'),
            'center' => __('Center','mv23theme'),
            'flex-end' => __('Bottom','mv23theme'),
            'space-between' => __('Distribute','mv23theme')
        )),

        // MOBILE -----------------------------------------------------------------------------------------------------------------------------------------------
        Field::create('tab','Mobile')->set_icon( 'dashicons-smartphone' ),
        Field::create( 'select', 'm_order', __('Order','mv23theme'))->add_options( array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'
        )),
        Field::create( 'select', 'm_content_alignment',__('Content Alignment','mv23theme'))->add_options( array(
            'flex-start' => __('Top','mv23theme'),
            'center' => __('Center','mv23theme'),
            'flex-end' => __('Bottom','mv23theme'),
            'space-between' => __('Distribute','mv23theme')
        ))
    ));