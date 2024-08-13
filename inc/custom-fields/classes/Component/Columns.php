<?php
namespace Theme_Custom_Fields\Component;

use Theme_Custom_Fields\Component;
use Ultimate_Fields\Field;
use Content_Selector;
use Theme_Custom_Fields\Template_Engine;

class Columns extends Component {

    public function __construct() {
		parent::__construct(
			'columns',
			__( 'Columns', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-columns';
    }

	public static function get_layout(){
        return 'grid';
    }

	public static function get_title_template() {
		$template = '<%= quantity %> columns';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( Field::create( 'tab', __('Content','default') ) );

        for ($i=1; $i <= COLUMNS_QUANTITY; $i++) { 
            $col = Content_Selector::the_field( 'column_'.$i, __('Column','default') )
				// ->set_width(25) not working
				->set_attr( 'style', 'width:25%' );
	
            if($i > 1) $col->add_dependency('quantity',($i-1),'>');
            $fields[] = $col;
        }

		return $fields;
	}

    public static function get_common_settings() {
		return array( 'row', 'columns', 'all' );
	}

	public static function display( $args ){
		$args['additional_classes'] = array('columns-wrapper');
		$args['__type'] = array('');

		$columns_quantity = $args['quantity'];
			
		$columns_classes = array( 'columns' );
		$columns_styles = array();
		$device = array( 'l','t','m' );
		foreach ($device as $key) {
			$width_meta = $key.'_grid_'.$columns_quantity;
			if( isset($args[$width_meta]) && !empty($args[$width_meta]) ){
				if( str_starts_with( $args[$width_meta] , 'tablet') || str_starts_with( $args[$width_meta] , 'mobile') ){
					// add special width to class
					$columns_classes[] = $args[$width_meta];
				} else {
					$columns_styles[] = '--'.$key.'-grid:'.$args[$width_meta];
				}
			}
		}

		$columns = array();
		array_push($columns, $args['column_1']);
		array_push($columns, $args['column_2']);
		array_push($columns, $args['column_3']);
		array_push($columns, $args['column_4']);

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo '<div class="'.implode(' ', $columns_classes).'" style="'.implode(';', $columns_styles).'">';
			// COLUMN START
		    for ($i=0; $i < $columns_quantity ; $i++) { 
		        $col_args = $args['column_'.($i+1).'_settings'];
			
		        $col_args['__type'] = 'column';
		        $col_args['additional_classes'] = array();
			
		        if( $col_args['content_alignment'] != 'flex-start' && !empty($col_args['content_alignment']) ){
					$col_args['additional_classes'][] = 'alignment-'.$col_args['content_alignment'];
				} 
			
				$col_args['additional_styles'] = array();
		        $mobile_order = $col_args['mobile_order'];
		        if(!empty($mobile_order)) $col_args['additional_styles'][] = '--m-order:'.$mobile_order;
		        $tablet_order = $col_args['tablet_order'];
		        if(!empty($tablet_order)) $col_args['additional_styles'][] = '--t-order:'.$tablet_order;
			
		        echo Template_Engine::component_wrapper('start', $col_args);
		        if ($col_args['content_alignment'] == 'pinned') echo '<div class="pinned-block">';
		        foreach ($columns[$i] as $component_inside) {
		            $component_inside['layout'] = 'layout1';
					echo Template_Engine::getInstance()->handle( $component_inside['__type'], $component_inside );
					// echo '<div class="component">TABLET ORDER'.$tablet_order.'</div>';
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