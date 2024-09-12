<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;

class Spacer extends Component {

    public function __construct() {
		parent::__construct(
			'spacer',
			__( 'Spacer', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-image-flip-vertical';
    }

    public static function get_title_template() {
		$template = '<%= height %><%= unit %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Contenido','default') ),
            Field::create( 'number', 'height', 'Tamaño de alto' )->set_default_value( '30' )->set_width( 50 ),
            Field::create( 'text', 'unit', 'Medida (px,%,vh..)' )->set_default_value( 'px' )->set_width( 50 ),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');

        $unit = ( isset($args['unit']) ) ? $args['unit'] : 'px';
        $args['additional_styles'] = array( 'height:'.$args['height'].$unit );
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Spacer();