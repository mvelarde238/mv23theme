<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Html extends Component {

    public function __construct() {
		parent::__construct(
			'html',
			__( 'Code', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-editor-code';
    }

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Contenido','default') ),
            Field::create( 'textarea', 'content' )->hide_label()->set_rows( 20 )->set_attr(array(
                'data-type' => 'html'
            )),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');
		$content = $args['content'];
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($content) echo $content;
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Html();