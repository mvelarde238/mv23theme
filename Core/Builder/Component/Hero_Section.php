<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Hero_Section extends Component {

    public function __construct() {
		parent::__construct(
			'hero-section',
			__( 'Hero Section', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-welcome-widgets-menus';
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
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Hero_Section();
