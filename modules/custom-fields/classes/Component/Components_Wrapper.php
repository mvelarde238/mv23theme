<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Blocks_Layout;
use Blocks_Layout_Settings;

class Components_Wrapper extends Component {

    public function __construct() {
		parent::__construct(
			'components_wrapper',
			__( 'Components Wrapper', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-text';
    }

	public static function get_title_template() {
		$template = '<% if ( blocks_layout.length < 1 ){ %>
            This item is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Contenido','default') ),
            Blocks_Layout::the_field( array( 'exclude' => array('inner_columns') ) ),
			Blocks_Layout_Settings::the_field()
        );

		return $fields;
	}

	public static function display( $args ){
        $blocks_layout_data = $args['blocks_layout'];
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Blocks_Layout::the_content( $blocks_layout_data, array('component_args' => $args) );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}