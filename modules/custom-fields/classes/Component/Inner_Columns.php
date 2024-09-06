<?php
namespace Theme_Custom_Fields\Component;

use Theme_Custom_Fields\Component;
use Ultimate_Fields\Field;
use Content_Selector;
use Theme_Custom_Fields\Template_Engine;

class Inner_Columns extends Component{

    public function __construct() {
		parent::__construct(
			'inner_columns',
			__( 'Inner Columns', 'default' )
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
        echo Template_Engine::getInstance()->handle( 'columns', $args );
    }
}

new Inner_Columns();