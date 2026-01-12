<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

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
        if( Template_Engine::is_private( $args ) ) return;

		$args['additional_classes'] = array('page-module');
        
	    $components = $args['components'] ?? array();
        $attributes = Template_Engine::generate_attributes( $args );

		ob_start();
        echo Template_Engine::check_full_width('start', $args);
        echo '<section '.$attributes.'>';
        do_action( 'after_component_wrapper_start', $args );
        echo Template_Engine::check_video_background( $args );
        echo Template_Engine::check_slider_background( $args );
        echo Template_Engine::check_layout('start', $args);

		foreach ($components as $component) {
			echo Template_Engine::getInstance()->handle( $component['__type'], $component );
		}
		
        echo Template_Engine::check_layout('end', $args);
        do_action( 'before_component_wrapper_end', $args );
        echo '</section>';
        echo Template_Engine::check_full_width('end', $args);
		return ob_get_clean();
	}
}

new Section();