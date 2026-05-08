<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Icon_List extends Component {

    public function __construct() {
		parent::__construct(
			'icon-list',
			__( 'Icon List', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'bi-list-check';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'icon', 'default_icon', 'Default Icon' )
                ->set_description( __( 'Select the default icon for the list items. This icon will be used for all items in the list unless they are individually customized.', 'mv23theme' ) )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-check-lg' )
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
		
        $attributes = Template_Engine::generate_attributes( $args );
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        echo Template_Engine::check_components( $args );
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Icon_List();