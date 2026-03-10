<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Template_Placeholder extends Component {

    public function __construct() {
		parent::__construct(
			'template-placeholder',
			__( 'Templates', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-welcome-widgets-menus';
    }

	public static function get_builder_data() {
        return array(
			'block_category' => 'Structure'
		);
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}
}

new Template_Placeholder();
