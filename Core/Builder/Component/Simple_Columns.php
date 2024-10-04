<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Blocks_Layout;

class Simple_Columns extends Component {

    public function __construct() {
		parent::__construct(
			'simple_columns',
			__( 'Simple Columns', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-columns';
    }

	public static function get_title_template() {
		$template = '<% if ( columns.length < 1 ){ %>
            This item is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Contenido','default') ),
            Blocks_Layout::the_field(),
            Field::create( 'tab', 'Márgenes' ),
            Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(20)
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
        $columns = $args['blocks_layout'];

        $components_margin = (!empty($args['components_margin'])) ? $args['components_margin'] : null;
        if ( $components_margin && $components_margin != 20) $args['additional_attributes'] =  'data-setmargin="'.$components_margin.'"';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Blocks_Layout::the_content($columns);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}