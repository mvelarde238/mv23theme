<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Editable_Area_Slot extends Component {

    public function __construct() {
		parent::__construct(
			'editable-area-slot',
			__( 'Editable Area Slot', 'mv23theme' ),
            array(
				'common_settings' => array()
			)
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template')
		);
    }

	public static function get_fields() {
		$fields = array(
			Field::create('text','slot_id')
				->set_default_value( uniqid('slot-') )
				->set_attr('style','opacity:.5;pointer-events:none;')
		);
		return $fields;
	}

    public static function display($args){
		$args['additional_attributes']['data-slot-id'] = $args['slot_id'];

        ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if(!is_admin()) echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
    }

    public static function get_view_template() {
		$message = __('This is an editable area slot.', 'mv23theme');

		$template = '<p>'.$message.'</p>';
        
        return $template;
    }
}

new Editable_Area_Slot();