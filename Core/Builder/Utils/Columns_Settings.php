<?php
namespace Core\Builder\Utils;

use Ultimate_Fields\Field;
use Ultimate_Fields\Container;

class Columns_Settings{
    public static function get_fields(){
        $columns_settings = array(
            Field::create( 'tab', __('Columns Settings','mv23theme') )
        );
        
        Container::create( '_column_fields' )->add_fields( array(
                Field::create( 'complex', 'order_wrapper', __('Order','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'select', 'order', __('Desktop','mv23theme'))->add_options( array(
                        '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                    ))->set_width(30),
                    Field::create( 'select', 'tablet_order', __('Tablet','mv23theme'))->add_options( array(
                        '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                    ))->set_width(30),
                    Field::create( 'select', 'mobile_order', __('Mobile','mv23theme'))->add_options( array(
                        '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
                    ))->set_width(30)
                )),
                Field::create( 'complex', 'alignment_wrapper', __('Content alignment','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'select', 'content_alignment',__('Desktop','mv23theme'))->add_options( array(
                        'flex-start' => __('Top','mv23theme'),
                        'center' => __('Center','mv23theme'),
                        'flex-end' => __('Bottom','mv23theme'),
                        'space-between' => __('Distribute','mv23theme'),
                        'pinned' => __('Fixed','mv23theme'),
                    ))->set_width(30),
                    Field::create( 'select', 'tablet_content_alignment',__('Tablet','mv23theme'))->add_options( array(
                        'flex-start' => __('Top','mv23theme'),
                        'center' => __('Center','mv23theme'),
                        'flex-end' => __('Bottom','mv23theme'),
                        'space-between' => __('Distribute','mv23theme')
                    ))->set_width(30),
                    Field::create( 'select', 'mobile_content_alignment',__('Mobile','mv23theme'))->add_options( array(
                        'flex-start' => __('Top','mv23theme'),
                        'center' => __('Center','mv23theme'),
                        'flex-end' => __('Bottom','mv23theme'),
                        'space-between' => __('Distribute','mv23theme')
                    ))->set_width(30)
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

        return $columns_settings;
    }
}