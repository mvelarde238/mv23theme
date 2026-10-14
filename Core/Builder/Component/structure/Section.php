<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core as Builder_Core;

class Section extends Component {

    public function __construct() {
		parent::__construct(
			'section',
			__( 'Section', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-align-wide';
    }

	public static function get_fields() {
		$fields = array();

		return $fields;
	}

	public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;

		$args['additional_classes'][] = 'page-module';
		$args['html_tag'] = 'section';
        
        $attributes = Template_Engine::generate_attributes( $args );

		ob_start();
		echo Template_Engine::component_wrapper( 'start', $args );
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper( 'end', $args );
		return ob_get_clean();
	}
}

new Section();