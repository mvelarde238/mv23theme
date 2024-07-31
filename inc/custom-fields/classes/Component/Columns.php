<?php
namespace Theme_Custom_Fields\Component;

use Theme_Custom_Fields\Component;
use Ultimate_Fields\Field;
use Content_Selector;
use Theme_Custom_Fields\Template_Engine;

class Columns extends Component {

    public function __construct() {
		parent::__construct(
			'Columnas',
			__( 'Columns', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-columns';
    }

	public static function get_title_template() {
		$template = '<%= nth_columnas %> columns';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( Field::create( 'tab', __('Content','default') ) );

        for ($i=1; $i <= COLUMNS_QUANTITY; $i++) { 
            $col = Content_Selector::the_field( 'columna_'.$i, __('Column','default') )
				// ->set_width(25) not working
				->set_attr( 'style', 'width:25%' );
	
            if($i > 1) $col->add_dependency('nth_columnas',($i-1),'>');
            $fields[] = $col;
        }

		return $fields;
	}

    public static function get_common_settings() {
		return array( 'row', 'columns', 'actions', 'all' );
	}

	public static function display( $args ){
		$args['additional_classes'] = array();

		$cols_qty = $args['nth_columnas'];

		$column_width_classes = array( 'columnas-'.$cols_qty );

		if( $cols_qty == 2 ){
			if ( !empty($args['special_widths']) ) $column_width_classes[] = $args['special_widths'];
			if ( !empty($args['tablet_widths']) ) $column_width_classes[] = $args['tablet_widths'];
			if ( !empty($args['mobile_widths']) ) $column_width_classes[] = $args['mobile_widths'];
		} else {
			if ( isset($args['special_widths_'.$cols_qty]) && !empty($args['special_widths_'.$cols_qty]) ){
				$column_width_classes[] = $args['special_widths_'.$cols_qty];
			} 
			if ( isset($args['special_widths_'.$cols_qty]) && !empty($args['tablet_widths_'.$cols_qty]) ){
				$column_width_classes[] = $args['tablet_widths_'.$cols_qty];
			} 
			if ( isset($args['special_widths_'.$cols_qty]) && !empty($args['mobile_widths_'.$cols_qty]) ){
				$column_width_classes[] = $args['mobile_widths_'.$cols_qty];
			} 
		}

		$columnas = array();
		array_push($columnas, $args['columna_1']);
		array_push($columnas, $args['columna_2']);
		array_push($columnas, $args['columna_3']);
		array_push($columnas, $args['columna_4']);

		$layout = (isset($args['layout'])) ? $args['layout'] : 'layout1';
		if( $layout == 'layout4' ) $args['additional_classes'][] = 'expand-columns';

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

		echo '<div class="'.implode(' ', $column_width_classes).'">';
			// COLUMN START
		    for ($i=0; $i < $cols_qty ; $i++) { 
		        $col_args = $args['columna_'.($i+1).'_settings'];
			
		        $col_args['additional_classes'] = array();
			
		        if( $col_args['content_alignment'] != 'flex-start' && !empty($col_args['content_alignment']) ){
					$col_args['additional_classes'][] = 'alignment-'.$col_args['content_alignment'];
				} 
			
		        $mobile_order = $col_args['mobile_order'];
		        if($mobile_order != 0) $col_args['additional_classes'][] = 'mobile-order-'.$mobile_order;
		        $tablet_order = $col_args['tablet_order'];
		        if($tablet_order != 0) $col_args['additional_classes'][] = 'tablet-order-'.$tablet_order;
			
		        echo Template_Engine::component_wrapper('start', $col_args);
		        if ($col_args['content_alignment'] == 'pinned') echo '<div class="pinned-block">';
		        foreach ($columnas[$i] as $component_inside) {
		            $component_inside['layout'] = 'layout1';
					echo Template_Engine::getInstance()->handle( $component_inside['__type'], $component_inside );
		        }
		        if ($col_args['content_alignment'] == 'pinned') echo '</div>';
				echo Template_Engine::component_wrapper('end', $col_args);
		    }
			// COLUMN END
		echo '</div>';

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Columns();