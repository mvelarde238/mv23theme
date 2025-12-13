<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Row extends Component {

    public function __construct() {
		parent::__construct(
			'row',
			__( 'Row', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-columns';
    }

	public static function get_fields() {
		$fields = array();

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['__type'] = array('row-component');
		$args['additional_classes'] = array('row-component', 'component');

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

		if( isset($args['components']) ){
			foreach ($args['components'] as $component) {
				echo Template_Engine::getInstance()->handle( $component['__type'], $component );
			}
		}

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Row();