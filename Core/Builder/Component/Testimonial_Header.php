<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Testimonial_Header extends Component {

    public function __construct() {
		parent::__construct(
			'testimonial-header',
			__( 'Testimonial Header', 'mv23theme' )
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
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Testimonial_Header();