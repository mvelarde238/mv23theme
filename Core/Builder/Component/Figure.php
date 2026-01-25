<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Figure extends Component {

    public function __construct() {
		parent::__construct(
			'figure',
			__( 'Figure', 'mv23theme' )
		);
	}

	public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
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

		if( isset($args['components']) && is_array($args['components']) ){
			foreach ($args['components'] as $component) {
				echo Template_Engine::getInstance()->handle( $component['__type'], $component );
			}
		}

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Figure();