<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core as Builder_Core;

class Row extends Component {

    public function __construct() {
		parent::__construct(
			'row-component',
			__( 'Rows', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'bi-layout-three-columns';
    }

	public static function get_builder_data() {
        return array(
			'block_name' => 'Columns',
			'block_category' => 'structure',
			'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {
		$fields = array(
			Field::create( 'select', 'columns', __('Select the number of columns for this row', 'mv23theme') )
				->set_attr( 'style', 'display: none;' )
				->add_options(array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				)),
			Field::create( 'image_select', '2_columns_width_presets', __('Select a predefined columns width preset', 'mv23theme') )
				->add_dependency( 'columns', '2' )
				->use_buttons()
                ->show_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
                '1/2-1/2' => array(
                    'label' => '2 Columns',
                    'image' => BUILDER_PATH.'/assets/images/columns/2/1fr-1fr.png',
                ),
				'1/3-2/3' => array(
					'label' => '1/3 + 2/3',
					'image' => BUILDER_PATH.'/assets/images/columns/2/1fr-2fr.png',
				),
				'2/3-1/3' => array(
					'label' => '2/3 + 1/3',
					'image' => BUILDER_PATH.'/assets/images/columns/2/2fr-1fr.png',
				),
				'1/4-3/4' => array(
					'label' => '1/4 + 3/4',
					'image' => BUILDER_PATH.'/assets/images/columns/2/1fr-3fr.png',
				),
				'3/4-1/4' => array(
					'label' => '3/4 + 1/4',
					'image' => BUILDER_PATH.'/assets/images/columns/2/3fr-1fr.png',
				),
				// these keywords need more testing for a better integration with the resizing mechanism, 
                // so for now we will keep them out of the presets
				// 'stretch-auto' => array(
				// 	'label' => 'Stretch + Auto',
				// 	'image' => BUILDER_PATH.'/assets/images/columns/2/stretch-auto.png',
				// ),
				// 'auto-stretch' => array(
				// 	'label' => 'Auto + Stretch',
				// 	'image' => BUILDER_PATH.'/assets/images/columns/2/auto-stretch.png',
				// ),
				'full-br-full' => array(
					'label' => 'Full Width',
					'image' => BUILDER_PATH.'/assets/images/columns/2/fullwidth.png',
				),
            )),
			Field::create( 'image_select', '3_columns_width_presets', __('Select a predefined columns width preset', 'mv23theme') )
				->add_dependency( 'columns', '3' )
				->use_buttons()
				->show_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
				'1/3-1/3-1/3' => array(
					'label' => '3 Columns',
					'image' => BUILDER_PATH.'/assets/images/columns/3/1fr-1fr-1fr.png',
				),
				'1/4-1/2-1/4' => array(
					'label' => '1/4 + 1/2 + 1/4',
					'image' => BUILDER_PATH.'/assets/images/columns/3/1fr-2fr-1fr.png',
				),
				'1/2-1/4-1/4' => array(
					'label' => '1/2 + 1/4 + 1/4',
					'image' => BUILDER_PATH.'/assets/images/columns/3/2fr-1fr-1fr.png',
				),
				'1/4-1/4-1/2' => array(
					'label' => '1/4 + 1/4 + 1/2',
					'image' => BUILDER_PATH.'/assets/images/columns/3/1fr-1fr-2fr.png',
				),
				'1/8-3/4-1/8' => array(
					'label' => '1/8 + 6/8 + 1/8',
					'image' => BUILDER_PATH.'/assets/images/columns/3/1fr-6fr-1fr.png',
				),
				'full-br-full-br-full' => array(
					'label' => 'Full Width',
					'image' => BUILDER_PATH.'/assets/images/columns/3/fullwidth.png',
				),
				'full-br-1/2-1/2' => array(
					'label' => 'Full Width + 1/2 + 1/2',
					'image' => BUILDER_PATH.'/assets/images/columns/3/1fr--1fr-1fr.png',
				),
				'1/2-1/2-br-full' => array(
					'label' => '1/2 + 1/2 + Full Width',
					'image' => BUILDER_PATH.'/assets/images/columns/3/1fr-1fr--1fr.png',
				),
			)),
			Field::create( 'image_select', '4_columns_width_presets', __('Select a predefined columns width preset', 'mv23theme') )
				->add_dependency( 'columns', '4' )
				->use_buttons()
				->show_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
				'1/4-1/4-1/4-1/4' => array(
					'label' => '4 Columns',
					'image' => BUILDER_PATH.'/assets/images/columns/4/1fr-1fr-1fr-1fr.png',
				),
				'full-br-full-br-full-br-full' => array(
					'label' => 'Full Width',
					'image' => BUILDER_PATH.'/assets/images/columns/4/fullwidth.png',
				),
				'1/2-1/2-br-1/2-1/2' => array(
					'label' => '1/2 + 1/2',
					'image' => BUILDER_PATH.'/assets/images/columns/4/1fr-1fr--1fr-1fr.png',
				),
			)),
			Field::create( 'image_select', '5_columns_width_presets', __('Select a predefined columns width preset', 'mv23theme') )
				->add_dependency( 'columns', '5' )
				->use_buttons()
				->show_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
				'1/5-1/5-1/5-1/5-1/5' => array(
					'label' => '5 Columns',
					'image' => BUILDER_PATH.'/assets/images/columns/5/1fr-1fr-1fr-1fr-1fr.png',
				),
				'full-br-full-br-full-br-full-br-full' => array(
					'label' => 'Full Width',
					'image' => BUILDER_PATH.'/assets/images/columns/5/fullwidth.png',
				),
			)),
			Field::create( 'image_select', '6_columns_width_presets', __('Select a predefined columns width preset', 'mv23theme') )
				->add_dependency( 'columns', '6' )
				->use_buttons()
				->show_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
				'1/6-1/6-1/6-1/6-1/6-1/6' => array(
					'label' => '6 Columns',
					'image' => BUILDER_PATH.'/assets/images/columns/6/1fr-1fr-1fr-1fr-1fr-1fr.png',
				),
				'full-br-full-br-full-br-full-br-full-br-full' => array(
					'label' => 'Full Width',
					'image' => BUILDER_PATH.'/assets/images/columns/6/fullwidth.png',
				),
			)),
		);

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['__type'] = array('row-component');
		$args['additional_classes'][] = 'row-component';
		$args['additional_classes'][] = 'component';

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Row();