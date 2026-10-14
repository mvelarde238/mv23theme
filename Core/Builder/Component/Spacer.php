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

	public static function get_builder_data() {
        return array(
            'block_render_type' => 'spacer'
		);
    }

	public static function get_fields() {
		$fields = array(
			Field::create( 'actions_control', 'common_actions', __( 'Set component height', 'mv23theme' ) )
				->set_actions( array(
					array( 'label' => '10px', 'command' => 'update-css-property', 'args' => array( 'property' => 'height', 'value' => '10px' ) ),
					array( 'label' => '20px', 'command' => 'update-css-property', 'args' => array( 'property' => 'height', 'value' => '20px' ) ),
					array( 'label' => '30px', 'command' => 'update-css-property', 'args' => array( 'property' => 'height', 'value' => '30px' ), 'class' => 'uf-button button-primary' ),
					array( 'label' => '40px', 'command' => 'update-css-property', 'args' => array( 'property' => 'height', 'value' => '40px' ) ),
					array( 'label' => '50px', 'command' => 'update-css-property', 'args' => array( 'property' => 'height', 'value' => '50px' ) )
				) )
		);
		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;
		
		$args['additional_classes'][] = 'component';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Spacer();