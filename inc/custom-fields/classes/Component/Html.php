<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;

class Html extends Component {

    public function __construct() {
		parent::__construct(
			'HTML',
			__( 'Code', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-editor-code';
    }

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', 'Contenido' ),
            Field::create( 'textarea', 'content' )->hide_label()->set_rows( 20 )->set_attr(array(
                'data-type' => 'html'
            )),
        );

		return $fields;
	}

	public static function display( $args ){
		$args['additional_classes'] = array('componente');
		$content = $args['content'];
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($content) echo $content;
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Html();