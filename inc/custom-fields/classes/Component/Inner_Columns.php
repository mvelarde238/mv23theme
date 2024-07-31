<?php
namespace Theme_Custom_Fields\Component;

use Theme_Custom_Fields\Component;
use Ultimate_Fields\Field;
use Content_Selector;
use Theme_Custom_Fields\Template_Engine;

class Inner_Columns extends Component{

    public function __construct() {
		parent::__construct(
			'Columnas Internas',
			__( 'Inner Columns', 'default' )
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
                ->set_attr( 'style', 'width:25%' );

            if($i > 1) $col->add_dependency('nth_columnas',($i-1),'>');
            $fields[] = $col;
        }

        $fields[] = Field::create( 'tab', 'Márgenes' );
        $fields[] = Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20);

		return $fields;
	}

    public static function get_common_settings() {
		return array( 'row', 'columns', 'all' );
	}

    public static function display( $args ){
        $components_margin = (!empty($args['components_margin'])) ? $args['components_margin'] : null;
        if ( $components_margin && $components_margin != 20){
            $args['additional_attributes'] = array('data-setmargin=="'.$components_margin.'"');
        } 

        echo Template_Engine::getInstance()->handle( 'columnas', $args );
    }
}

new Inner_Columns();