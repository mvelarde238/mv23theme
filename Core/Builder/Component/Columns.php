<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Content_Selector;
use Core\Builder\Template_Engine;

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

	// public static function get_edit_mode(){
    //     return 'inline';
	// }

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
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('columns-wrapper', 'component');
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
			} else {
				// set default if there isnt data
				if( $key == 'l' ) $columns_styles[] = '--l-grid:repeat('.$columns_quantity.', 1fr)';
				if( $key != 'l' ) $columns_styles[] = '--'.$key.'-grid:1fr';
			}
		}

		$columns = array();
		array_push($columns, $args['column_1']);
		array_push($columns, $args['column_2']);
		array_push($columns, $args['column_3']);
		array_push($columns, $args['column_4']);

		$devices = array('l-desktop','tablet','mobile');

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo '<div class="'.implode(' ', $columns_classes).'" style="'.implode(';', $columns_styles).'">';
			// COLUMN START
		    for ($i=0; $i < $columns_quantity ; $i++) { 
		        $col_args = $args['column_'.($i+1).'_settings'];
			
		        $col_args['__type'] = 'column';
				$col_args['additional_styles'] = array();
			
				// column aligment and order
				foreach ($devices as $device) {
					$alignment_meta = ($device == 'l-desktop') ? 'content_alignment' : $device.'_content_alignment';
					if( isset($col_args[$alignment_meta]) && !empty($col_args[$alignment_meta]) && $col_args[$alignment_meta] != 'flex-start' ){
						$col_args['additional_styles'][] = '--'.$device[0].'-alignment:'.$col_args[$alignment_meta];
					}

					$order_meta = ($device == 'l-desktop') ? 'order' : $device.'_order';
					if( isset($col_args[$order_meta]) && !empty($col_args[$order_meta]) ){
						$col_args['additional_styles'][] = '--'.$device[0].'-order:'.$col_args[$order_meta];
					}
				}
			
		        echo Template_Engine::component_wrapper('start', $col_args);
		        if ($col_args['content_alignment'] == 'pinned') echo '<div class="pinned-block">';
		        foreach ($columns[$i] as $component_inside) {
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