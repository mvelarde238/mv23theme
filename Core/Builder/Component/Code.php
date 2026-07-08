<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

class Code extends Component {

    public function __construct() {
		parent::__construct(
			'code',
			__( 'Code', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-editor-code';
    }

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'textarea', 'content' )
				->add_dynamic_data_selector()
				->set_codemirror('text/html')
				->hide_label()->set_rows( 20 )->set_attr(array(
                	'data-type' => 'html'
            	)),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;
		
		$args['additional_classes'][] = 'component';
		$content = Handlebars::parse($args['content']) ?? '';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($content) echo $content;
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Code();