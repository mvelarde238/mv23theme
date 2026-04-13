<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Flip_Box;

class Flip_Box_Back extends Component {

    public function __construct() {
		parent::__construct(
			'flipbox-back',
			__( 'Flip Box Back', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false,
            'custom_datastore_change_callback' => true
		);
    }

	public static function get_icon() {
        return 'dashicons-image-flip-horizontal';
    }
    
	public static function get_fields() {
		return Flip_Box::get_fields();
	}

	public static function display( $args ){        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Flip_Box_Back();