<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class OCE_Modal_Content extends Component {

    public function __construct() {
		parent::__construct(
			'oce-modal-content',
			__( 'OCE Modal Content', 'mv23theme' ),
            array( 'add_common_settings' => false )
		);
	}

	public static function get_icon() {
        return 'dashicons-text-page';
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
		if( Template_Engine::is_restricted( $args ) ) return;
		
		$args['additional_classes'][] = 'component';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new OCE_Modal_Content();