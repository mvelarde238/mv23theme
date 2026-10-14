<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Accordion_Item extends Component {

    public function __construct() {
		parent::__construct(
			'togglebox-item',
			__( 'Accordion Item', 'mv23theme' )
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
        $args['additional_classes'][] = 'togglebox__item';
        $args['additional_classes'][] = 'components-wrapper';
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        echo Template_Engine::check_components( $args );
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Accordion_Item();