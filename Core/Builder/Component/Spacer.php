<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Spacer extends Component {

    public function __construct() {
		parent::__construct(
			'spacer',
			__( 'Spacer', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-image-flip-vertical';
    }

    public static function get_title_template() {
		$template = '<%= spacer_options_wrapper.height %><%= spacer_options_wrapper.unit %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Content','mv23theme') ),
			Field::create( 'complex', 'spacer_options_wrapper', __( 'Spacer', 'mv23theme' ) )->merge()->add_fields(array(
				Field::create( 'number', 'height', __( 'Height', 'mv23theme' ) )
					->set_default_value( '30' )
					->set_attr( 'style', 'flex-grow:initial;' ),
				Field::create( 'text', 'unit', __( 'Unit (px,%,vh..)', 'mv23theme' ) )
					->set_default_value( 'px' )
					->add_suggestions( array('px', '%', 'vh', 'vw', 'rem', 'em') )
					->set_attr( 'style', 'flex-grow:initial;' )
			))
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');

        $unit = ( isset($args['unit']) ) ? $args['unit'] : 'px';
		$height = ( isset($args['height']) ) ? $args['height'] : 30;
        $args['additional_styles'] = array( 'height:'.$height.$unit );
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Spacer();