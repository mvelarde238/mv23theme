<?php
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Button;

class Postcard_Trigger extends Component {

    public function __construct() {
		parent::__construct(
			'postcard-trigger',
			__( 'Postcard Trigger', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-button';
    }

    public static function get_builder_data() {
        return array(
            'posttypes' => array('postcard'),
		);
    }

    public static function get_fields() {
        $fields = Button::get_fields();
		return $fields;
	}

    public static function display( $args ){
        $args['additional_classes'][] = 'trigger-post-action';
        return Button::display( $args );
    }
}

new Postcard_Trigger();