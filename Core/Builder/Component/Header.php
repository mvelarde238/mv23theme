<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Header extends Component {

    public function __construct() {
		parent::__construct(
			'header',
			__( 'Header', 'mv23theme' ),
			array(
				'common_settings' => array(),
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
        ob_start();
        get_template_part('partials/header');
        return ob_get_clean();
    }
}

new Header();