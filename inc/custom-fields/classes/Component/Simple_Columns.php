<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Content_Layout;

class Simple_Columns extends Component {

    public function __construct() {
		parent::__construct(
			'Columnas Simples',
			__( 'Simple Columns', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-columns';
    }

	public static function get_title_template() {
		$template = '<% if ( columnas_simples.length < 1 ){ %>
            This item is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', 'Contenido' ),
            Content_Layout::the_field(array('slug' => 'columnas_simples')),
            Field::create( 'tab', 'Márgenes' ),
            Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20)
        );

		return $fields;
	}

	public static function display( $args ){
        $columnas_simples = $args['columnas_simples'];

        $components_margin = (!empty($args['components_margin'])) ? $args['components_margin'] : null;
        if ( $components_margin && $components_margin != 20) $args['additional_attributes'] =  'data-setmargin="'.$components_margin.'"';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Content_Layout::the_content($columnas_simples);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}