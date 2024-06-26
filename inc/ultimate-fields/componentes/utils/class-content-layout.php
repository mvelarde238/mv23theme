<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

class Content_Layout{
    function __construct(){}
    
	public static function the_field($args){
        $defaults = array(
            'slug' => 'theme_layout_field',
            'title' => 'Theme Layout Field',
            'columns' => 12,
            'placeholder' => 'Arrastre un componente aquí',
            'hide_label' => true,
            'dependencies' => array(),
            'components' => CONTENT_BUILDER_COMPONENTS,
            'override' => array(),
        );
        $args = wp_parse_args( $args, $defaults );
        
        $componentes = $GLOBALS['componentes'];
        $args['groups'] = array(); 

        if( is_array($args['components']) && count($args['components']) > 0 ){
            foreach($args['components'] as $component_name){    
                if( $component_name === 'Layout Column' ){
                    $layout_column_components = CONTENT_BUILDER_COMPONENTS;

                    if( 
                        is_array($args['override']) && count($args['override']) > 0 
                        && key_exists('layout-column', $args['override']) && key_exists('components', $args['override']['layout-column']) 
                        && is_array($args['override']['layout-column']['components']) ){
                        $layout_column_components = $args['override']['layout-column']['components'];
                    }

                    $inner_content_layout = Content_Layout::the_field(array(
                        'slug' => 'content_layout', 
                        'title'=>'Layout',
                        'components' => $layout_column_components
                    ));

                    if( 
                        is_array($args['override']) && count($args['override']) > 0 
                        && key_exists('layout-column', $args['override']) && key_exists('add_group', $args['override']['layout-column']) 
                        && is_array($args['override']['layout-column']['add_group']) ){
                        $groups = $args['override']['layout-column']['add_group'];
                        foreach ($groups as $group) {
                            $inner_content_layout->add_group($group);
                        }
                    }

                    $layout_column = Repeater_Group::create( 'Layout Column', array(
                        'title' => 'Columna',
                        'fields' => array( $inner_content_layout )
                    ));

                    $group = $layout_column;
                } else {
                    // Look for component key in $components array
                    $found_key = array_search($component_name, array_column($componentes, 'name'));
                    if($found_key >= 0) $group = $componentes[$found_key]['variable'];
                }
                array_push( $args['groups'], $group );
            }
        }

        $field = Field::create( 'layout', $args['slug'], $args['title'] )->set_columns( $args['columns'] )
            ->set_placeholder_text( $args['placeholder'] );

        if($args['hide_label']) $field->hide_label();
        if(is_array($args['dependencies']) && count($args['dependencies']) > 0){
            foreach ($args['dependencies'] as $dep) {
                $field->add_dependency( $dep['field'],$dep['value'],$dep['compare'] );
            }
        }

        if(is_array($args['groups']) && count($args['groups']) > 0){
            foreach ($args['groups'] as $component) {
                $options = array(
                    'min_width' => 3,
                    'title' => $component->get_title(),
                    'fields' => $component->get_fields(),
                    'edit_mode' => $component->get_edit_mode(),
                    'layout' => $component->get_layout()
                );

                if( is_array($args['override']) && count($args['override']) > 0 && key_exists($component->get_id(), $args['override'])){
                    $options = wp_parse_args( $args['override'][$component->get_id()], $options );
                }
                
                $field->add_group( $component->get_id(), $options);
            }
        }
    
		return $field;
	}

	public static function the_content($layouts){
        
        ob_start();
        echo '<div>';
        for ($i=0; $i < count($layouts); $i++) { 
            $row = $layouts[$i];
            for ($it=0; $it < count($row); $it++){
                $componente = $row[$it];
                $type = $componente['__type'];
                $type_class = $type;
                if($type == 'button') $type_class = 'button-comp';
                $width = $componente['__width'];
                $componente['layout'] = 'layout1';
                echo '<div class="columnas-simples__item '.$type_class.' width-'.$width.'">';
                set_query_var('componente', $componente);
                $path = '/inc/ultimate-fields/componentes/views/'.$type;
                get_template_part($path);
                echo '</div>';
            };
        };
        echo '</div>';
        return $html = ob_get_clean();
	}
}