<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Content_Layout;

class Components_Wrapper extends Component {

    public function __construct() {
		parent::__construct(
			'Components Wrapper',
			__( 'Components Wrapper', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-text';
    }

	public static function get_title_template() {
		$template = '<% if ( content_layout.length < 1 ){ %>
            This item is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', 'Contenido' ),
            Content_Layout::the_field( array( 'exclude' => array('columnas-internas') ) )
        );

		return $fields;
	}

	public static function display( $args ){
        $content_layout_data = $args['content_layout'];
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Content_Layout::the_content( $content_layout_data );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}