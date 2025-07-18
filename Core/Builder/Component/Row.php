<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core;

class Row extends Component {

    public function __construct() {
		parent::__construct(
			'row',
			__( 'Columns', 'mv23theme' )
		);
	}

	// public static function get_edit_mode() {
	// 	return 'inline';
	// }

	public static function get_icon() {
        return 'dashicons-columns';
    }

	public static function get_layout(){
        return 'grid';
    }

    public static function get_title_template() {
		$template = '<% if ( row.content.length < 1 ){ %>
            This item is empty
        <% } else if ( row.content.length == 1 ) { %>
			1 Column
		<% } else { %>
			<%= row.content.length %> Columns
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$columns_layout = Field::create( 'columns_layout', 'row')->hide_label();

		$components = Core::getInstance()->get_components();
		if(is_array($components) && count($components) > 0){
            foreach ($components as $component) {

				$exclude = array();
				if( in_array($component->get_id(), $exclude) ) continue;

                $options = array(
                    'min_width' => 1,
                    'title' => $component->get_title(),
                    'icon' => $component->get_icon(),
                    'title_template' => $component->get_title_template(),
                    'fields' => $component->get_fields(),
                    'edit_mode' => $component->get_edit_mode(),
                    'layout' => $component->get_layout()
                );

				$columns_layout->add_group( $component->get_id(), $options);
            }
        }

		$fields = array( 
            Field::create( 'tab', __('Content','mv23theme') ),
            $columns_layout
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		if( !isset($args['row']) ) return;
		
		$args['additional_classes'] = array('columns-wrapper', 'component');
		$columns_classes = array( 'columns' );
		$args['__type'] = array('');
		$columns_styles = array();

		$columns_quantity = count( $args['row']['content'] );
		$devices = array( 'l','t','m' );
		$row_settings = $args['row']['row_settings'];
		$columns_settings = $args['row']['columns_settings'];
		$columns = $args['row']['content'];

		// columns widths
		foreach ($devices as $key) {
			$width_meta = $key.'_grid_'.$columns_quantity;
			if( isset($row_settings[$width_meta]) && !empty($row_settings[$width_meta]) ){
				if( str_starts_with( $row_settings[$width_meta] , 'tablet') || str_starts_with( $row_settings[$width_meta] , 'mobile') ){
					// add special width to class
					$columns_classes[] = $row_settings[$width_meta];
				} else {
					$columns_styles[] = '--'.$key.'-grid:'.$row_settings[$width_meta];
				}
			} else {
				// set default if there isnt data
				if( $key == 'l' ) $columns_styles[] = '--l-grid:repeat('.$columns_quantity.', 1fr)';
				if( $key != 'l' ) $columns_styles[] = '--'.$key.'-grid:1fr';
			}
		}

		// gap
		foreach ($devices as $key) {
			$gap_meta = $key.'_gap';
			if( isset($row_settings[$gap_meta]) ){
				if( $row_settings[$gap_meta] != '' ){
					// format gap value: numeric values will be converted to px
					$formatted_gap = ( preg_match('/^[0-9]+$/', $row_settings[$gap_meta]) ) ? $row_settings[$gap_meta].'px' : $row_settings[$gap_meta];
					// add gap to styles
					$columns_styles[] = '--'.$key.'-gap:'.$formatted_gap;
				}
			}
		}

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo '<div class="'.implode(' ', $columns_classes).'" style="'.implode(';', $columns_styles).'">';
			// COLUMN START
		    for ($i=0; $i < $columns_quantity ; $i++) { 
		        $col_args = [];
		        $col_args['__type'] = 'column';
				$col_args['additional_styles'] = array();
		        $col_settings = $columns_settings[$i] ?? [];
		        $col_args['settings'] = $col_settings;
				$l_content_alignment = '';
			
				// column aligment and order
				foreach ($devices as $device) {
					$alignment_meta = $device.'_content_alignment';
					if( isset($col_settings[$alignment_meta]) && !empty($col_settings[$alignment_meta]) && $col_settings[$alignment_meta] != 'flex-start' ){
						$col_args['additional_styles'][] = '--'.$device.'-alignment:'.$col_settings[$alignment_meta];
						if($device == 'l') $l_content_alignment = $col_settings[$alignment_meta];
					}

					$order_meta = $device.'_order';
					if( isset($col_settings[$order_meta]) && !empty($col_settings[$order_meta]) ){
						$col_args['additional_styles'][] = '--'.$device.'-order:'.$col_settings[$order_meta];
					}
				}
			
		        echo Template_Engine::component_wrapper('start', $col_args);
		        if ($l_content_alignment == 'pinned') echo '<div class="pinned-block">';
		        foreach ($columns[$i] as $component_inside) {
		            $component_inside['layout'] = 'layout1';
					echo Template_Engine::getInstance()->handle( $component_inside['__type'], $component_inside );
		        }
		        if ($l_content_alignment == 'pinned') echo '</div>';
				echo Template_Engine::component_wrapper('end', $col_args);
		    }
			// COLUMN END
		echo '</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Row();