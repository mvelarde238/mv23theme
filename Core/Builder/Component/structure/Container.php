<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Container extends Component {

    public function __construct() {
		parent::__construct(
			'container',
			__( 'Container', 'mv23theme' )
		);

		add_action( 'uf.ultimate_builder.save_component', array( $this, 'on_save_component' ), 10, 4 );
	}

    public function on_save_component( $processed_values, &$component_data, $group, $ultimate_builder ) {
        if ( $component_data['__type'] == 'container' ){
            // Mark this component to prevent it from being saved in page_content_datastore
			$component_data['skip_datastore_saving'] = true;
        }
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

		$args['additional_classes'][] = 'container';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Container();
