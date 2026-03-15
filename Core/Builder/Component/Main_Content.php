<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Main_Content extends Component {

    public function __construct() {
		parent::__construct(
			'main-content',
			__( 'Main Content', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'bi-layout-text-sidebar';
    }

    public static function get_builder_data() {
        return array(
			'block_category' => 'Structure',
            'posttypes' => array('single_template','archive_template'),
            'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {
		$fields = array();
		$fields[] = Field::create( 'tab', 'template_tab', __('Template','mv23theme') );
		$fields[] = Field::create( 'select', 'template')
			->hide_label()
			->set_default_value('main-content--sidebar-right')
			->add_options(array(
				'main-content--sidebar-left' => __('Left Sidebar','mv23theme'),
				'main-content--sidebar-right' => __('Right Sidebar','mv23theme'),
				'main-content--sidebarless' => __('No Sidebar','mv23theme')
			));

		return $fields;
	}

    public static function display($args){
        if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'][] = 'main-content';
        $page_template = $args['template'] ?? 'main-content--sidebar-right';
		$args['additional_classes'][] = $page_template;

        ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Template_Engine::check_components( $args );
        echo Template_Engine::component_wrapper('end', $args);
        return ob_get_clean();
    }
}

new Main_Content();