<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Single_Page_Settings extends Component {

    public function __construct() {
		parent::__construct(
			'single_page_settings',
			__( 'Single_Page_Settings', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'checkbox', 'some_single_setting')->fancy()
        );
		return $fields;
	}
}

new Single_Page_Settings();