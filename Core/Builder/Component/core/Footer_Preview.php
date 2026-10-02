<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Component\Footer;

class Footer_Preview extends Component {

    public function __construct() {
		parent::__construct(
			'footer-preview',
			__( 'Footer Preview', 'mv23theme' ),
			array(
				'common_settings' => array()
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
        echo Footer::display( $args );
        return ob_get_clean();
    }
}

new Footer_Preview();