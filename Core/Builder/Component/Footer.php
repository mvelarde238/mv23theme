<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Footer extends Component {

    public function __construct() {
		parent::__construct(
			'footer',
			__( 'Footer', 'mv23theme' ),
			array(
				'common_settings' => array(
					'settings',
                    'scroll_animations_settings'
				),
			)
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
        $args['additional_classes'][] = 'footer';
		$footer_content = $args;

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo '<div class="footer-content container">';
		echo Template_Engine::check_components( $footer_content );
		echo '</div>';
        echo '<a class="subir-btn" href="#content" data-state="hidden"><i class="fa fa-angle-up"></i></a>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Footer();