<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core as Builder_Core;

class Column extends Component {

    public function __construct() {
		parent::__construct(
			'column',
			__( 'Column', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-columns';
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
		
		$args['__type'] = array('column');
		$args['additional_classes'][] = 'column';

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Column();