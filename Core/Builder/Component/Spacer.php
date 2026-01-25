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
		$fields = array();
		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'][] = 'component';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Spacer();