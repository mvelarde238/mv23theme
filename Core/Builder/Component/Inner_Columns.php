<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Content_Selector;
use Core\Builder\Template_Engine;
use Core\Builder\Utils\Row_Settings;
use Core\Builder\Utils\Columns_Settings;

class Inner_Columns extends Component{

    public function __construct() {
		parent::__construct(
			'inner_columns',
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
		$template = '<%= quantity %> columns';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( Field::create( 'tab', __('Content','mv23theme') ) );

        for ($i=1; $i <= COLUMNS_QUANTITY; $i++) { 
            $col = Content_Selector::the_field( 'column_'.$i, __('Column','mv23theme') )
                ->set_attr( 'style', 'width:25%' );

            if($i > 1) $col->add_dependency('quantity',($i-1),'>');
            $fields[] = $col;
        }

		return array_merge($fields, Row_Settings::get_fields(), Columns_Settings::get_fields());
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
        echo Template_Engine::getInstance()->handle( 'columns', $args );
    }
}

new Inner_Columns();