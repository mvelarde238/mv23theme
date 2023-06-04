<?php
use Ultimate_Fields\Field;

class Content_Layout{
    private static $componentes;

	function __construct($componentes){
        self::$componentes = $componentes;
    }
    
	public static function the_field($args){
        $defaults = array(
            'slug' => 'theme_layout_field',
            'title' => 'Theme Layout Field',
            'columns' => 12,
            'placeholder' => 'Arrastre un componente aquí',
            'hide_label' => true,
            'dependencies' => array(),
            'components' => array(),
            'override' => array(),
        );
        $args = wp_parse_args( $args, $defaults );
        
        $args['groups'] = array(); 
        foreach(self::$componentes as $component){
            if( is_array($args['components']) && count($args['components']) > 0 ){
                if( !in_array($component['name'], $args['components']) ) continue;
            }
            array_push( $args['groups'], $component['variable'] );
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
                    'max_width' => $component->get_maximum(),
                    'fields' => $component->get_fields(),
                    'edit_mode' => $component->get_edit_mode()
                );

                if( is_array($args['override']) && count($args['override']) > 0 && key_exists($component->get_id(), $args['override'])){
                    $options = wp_parse_args( $args['override'][$component], $options );
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
                $width = $componente['__width'];
                $componente['layout'] = 'layout1';
                echo '<div class="columnas-simples__item '.$type.' width-'.$width.'">';
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