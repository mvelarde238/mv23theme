<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class OCE_Modal_Content extends Component {

    public function __construct() {
		parent::__construct(
			'oce_modal_content',
			__( 'OCE Modal Content', 'mv23theme' ),
            array( 'add_common_settings' => false )
		);
	}

	public static function get_icon() {
        return 'dashicons-text-page';
    }

	public static function get_fields() {
		$fields = array();

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');
        
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

new OCE_Modal_Content();