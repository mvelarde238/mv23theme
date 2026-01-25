<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Hero_Section extends Component {

    public function __construct() {
		parent::__construct(
			'hero_section',
			__( 'Hero Section', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-welcome-widgets-menus';
    }

	public static function get_builder_data() {
        return array(
			'connected_gjs_type' => 'hero-section'
		);
    }

	public static function get_fields() {
		$fields = array(            
            Field::create( 'radio', 'template', __( 'Template', 'mv23theme' ) )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'hero1' => __( 'Hero 1', 'mv23theme' ),
                    'hero2' => __( 'Hero 2', 'mv23theme' ),
                    'hero3' => __( 'Hero 3', 'mv23theme' ),
                ) )
                ->set_default_value( 'hero1' ),
        );

		return $fields;
	}

    public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'][] = 'component';
        
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

new Hero_Section();
