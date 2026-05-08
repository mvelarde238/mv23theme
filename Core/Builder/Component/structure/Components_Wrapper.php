<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Components_Wrapper extends Component {

    public function __construct() {
		parent::__construct(
			'components-wrapper',
			__( 'Wrapper', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-text';
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;
		
		$args['additional_classes'][] = 'component';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Components_Wrapper();