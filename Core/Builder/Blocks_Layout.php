<?php
namespace Core\Builder;

use Ultimate_Fields\Field;
use Core\Builder\Core;
use Core\Builder\Template_Engine;

class Blocks_Layout{
    function __construct(){}
    
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
            'slug' => 'blocks_layout',
            'title' => 'Blocks Layout',
            'hide_label' => true,
            'placeholder' => 'Arrastre un componente aquí',
            'components' => array(),
            'exclude' => array(),
            'columns' => 12,
            'override' => array()
        );

        $args = wp_parse_args( $args, $defaults );

        $components = Core::getInstance()->get_components();

        $components_layout = Field::create( 'layout', $args['slug'], $args['title'] )
            ->set_columns( $args['columns'] )
            ->set_placeholder_text( $args['placeholder'] );

        if($args['hide_label']) $components_layout->hide_label();
        
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
                $options = array(
                    'min_width' => 1,
                    'title' => $component->get_title(),
                    'title_template' => $component->get_title_template(),
                    'fields' => $component->get_fields(),
                    'edit_mode' => $component->get_edit_mode(),
                    'layout' => $component->get_layout()
                );

                if( is_array($args['override']) && count($args['override']) > 0 && key_exists($component->get_id(), $args['override'])){
                    $options = wp_parse_args( $args['override'][$component->get_id()], $options );
                }
                
                if( is_array($args['exclude']) && !in_array( $component->get_id(), $args['exclude'] ) ){
                    $components_layout->add_group( $component->get_id(), $options);
                }
            }
        }
    
		return $components_layout;
	}

	public static function the_content($layouts, $args = array() ){
        $defaults = array(
            '__type' => 'content-layout',
            'component_args' => array(),
            'additional_classes' => array(),
            'additional_attributes' => array()
        );
        $args = wp_parse_args( $args, $defaults );

        $layout = null;
        if( isset($args['component_args']['blocks_layout_settings']) && is_array($args['component_args']['blocks_layout_settings']) ){
            $layout = $args['component_args']['blocks_layout_settings']['layout'];
            $args['additional_classes'][] = 'jc-'.$args['component_args']['blocks_layout_settings']['justify_content'];
            $args['additional_classes'][] = 'ai-'.$args['component_args']['blocks_layout_settings']['align_items'];
        }
        $layout = ( empty($layout) ) ? 'layout-grid' : 'layout-'.$layout;
        $args['additional_classes'][] = $layout;
        
        ob_start();
        echo Template_Engine::component_wrapper('start',$args);
        for ($i=0; $i < count($layouts); $i++) { 
            $row = $layouts[$i];
            for ($it=0; $it < count($row); $it++){
                $component = $row[$it];
                $type = $component['__type'];
                $type_class = $type;
                if($type == 'button') $type_class = 'button-comp';
                $width = $component['__width'];
                $component['layout'] = 'layout1';
                echo '<div class="content-layout__item '.$type_class.' width-'.$width.'">';
                echo Template_Engine::getInstance()->handle( $component['__type'], $component );
                echo '</div>';
            };
        };
        echo Template_Engine::component_wrapper('end',$args);
        return ob_get_clean();
	}
}