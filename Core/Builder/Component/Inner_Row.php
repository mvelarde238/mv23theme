<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core;

class Inner_Row extends Component {

    public function __construct() {
		parent::__construct(
			'inner_row',
			__( 'Inner Columns', 'mv23theme' )
		);
	}

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
        
        echo Template_Engine::getInstance()->handle( 'row', $args );
	}
}

new Inner_Row();