<?php
namespace Core\Builder;

use Ultimate_Fields\Field;
use Core\Builder\Core;

class Content_Selector{
    private function __construct(){}
    
	public static function the_field( $slug = '', $title = '', $args = array()){
        /* slug and title can be setted in $args as first parameter */
        if( is_array($slug) ){
            $args = $slug;
        } else {
            if( !empty($slug) ){
                $args['slug'] = $slug;
                $args['title'] = $title;
            }
        }

        $defaults = array(
            'slug' => 'components',
            'title' => 'Components',
            'hide_label' => true,
            'add_text' => __('Add', 'default'),
            'components' => array(),
            'exclude' => array()
        );
        $args = wp_parse_args( $args, $defaults );
        
        $components = Core::getInstance()->get_components();

        $components_repeater = Field::create( 'repeater', $args['slug'], $args['title'] )
            ->set_chooser_type( 'dropdown' )
            ->set_add_text( $args['add_text'] );

        if($args['hide_label']) $components_repeater->hide_label();

        $groups = array();

        if( is_array($args['components']) && count($args['components']) > 0 ){
            foreach($args['components'] as $component_name){
                // Look for component key in $components / Repeater_Groups array
                foreach($components as $component){
                    if( $component->get_id() == $component_name ) $groups[] = $component;
                }
            }
        } else {
            $groups = $components;
        }

        if(is_array($groups) && count($groups) > 0){
            foreach ($groups as $component) {
                if( is_array($args['exclude']) && !in_array( $component->get_id(), $args['exclude'] ) ){
                    $components_repeater->add_group( $component );
                }
            }
        }
    
		return $components_repeater;
	}
}