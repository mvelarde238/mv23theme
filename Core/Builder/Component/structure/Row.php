<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Row extends Component {

    public function __construct() {
		parent::__construct(
			'row-component',
			__( 'Row', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'bi-layout-three-columns';
    }

	public static function get_builder_data() {
        return array(
			'block_name' => 'Columns',
			'block_category' => 'structure'
		);
    }

	public static function get_fields() {		
		$fields = array(
			Field::create( 'select', 'columns')->set_attr( 'style', 'display: none;' )
				->add_options(array( '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' )),

			Field::create( 'actions_control', '2_columns_width_presets', __('Predefined columns sizes:', 'mv23theme') )
				->add_dependency( 'columns', '2' )
				->set_actions( self::columns_width_presets(array(
					array( 'label' => '2 Columns', 'preset' => '1/2-1/2', 'image' => '2/1fr-1fr.png' ),
					array( 'label' => '1/3 - 2/3', 'image' => '2/1fr-2fr.png' ),
					array( 'label' => '2/3 - 1/3', 'image' => '2/2fr-1fr.png' ),
					array( 'label' => '1/4 - 3/4', 'image' => '2/1fr-3fr.png' ),
					array( 'label' => '3/4 - 1/4', 'image' => '2/3fr-1fr.png' ),
					array( 'label' => 'Full Width', 'preset' => 'full-br-full', 'image' => '2/fullwidth.png' ),
				))),

			Field::create( 'actions_control', '3_columns_width_presets', __('Predefined columns sizes:', 'mv23theme') )
				->add_dependency( 'columns', '3' )
				->set_actions( self::columns_width_presets(array(
					array( 'label' => '3 Columns', 'preset' => '1/3-1/3-1/3', 'image' => '3/1fr-1fr-1fr.png' ),
					array( 'label' => '1/4 - 1/2 - 1/4', 'image' => '3/1fr-2fr-1fr.png' ),
					array( 'label' => '1/2 - 1/4 - 1/4', 'image' => '3/2fr-1fr-1fr.png' ),
					array( 'label' => '1/4 - 1/4 - 1/2', 'image' => '3/1fr-1fr-2fr.png' ),
					array( 'label' => '1/8 - 3/4 - 1/8', 'image' => '3/1fr-6fr-1fr.png' ),
					array( 'label' => 'Full Width', 'preset' => 'full-br-full-br-full', 'image' => '3/fullwidth.png' ),
					array( 'label' => 'Full Width + 1/2 + 1/2', 'preset' => 'full-br-1/2-1/2', 'image' => '3/1fr--1fr-1fr.png' ),
					array( 'label' => '1/2 + 1/2 + Full Width', 'preset' => '1/2-1/2-br-full', 'image' => '3/1fr-1fr--1fr.png' ),
				)) ),

			Field::create( 'actions_control', '4_columns_width_presets', __('Predefined columns sizes:', 'mv23theme') )
				->add_dependency( 'columns', '4' )
				->set_actions( self::columns_width_presets(array(
					array( 'label' => '4 Columns', 'preset' => '1/4-1/4-1/4-1/4', 'image' => '4/1fr-1fr-1fr-1fr.png' ),
					array( 'label' => 'Full Width', 'preset' => 'full-br-full-br-full-br-full', 'image' => '4/fullwidth.png' ),
					array( 'label' => '1/2 + 1/2', 'preset' => '1/2-1/2-br-1/2-1/2', 'image' => '4/1fr-1fr--1fr-1fr.png' ),
				)) ),

			Field::create( 'actions_control', '5_columns_width_presets', __('Predefined columns sizes:', 'mv23theme') )
				->add_dependency( 'columns', '5' )
				->set_actions( self::columns_width_presets(array(
					array( 'label' => '5 Columns', 'preset' => '1/5-1/5-1/5-1/5-1/5', 'image' => '5/1fr-1fr-1fr-1fr-1fr.png' ),
					array( 'label' => 'Full Width', 'preset' => 'full-br-full-br-full-br-full-br-full', 'image' => '5/fullwidth.png' ),
				)) ),

			Field::create( 'actions_control', '6_columns_width_presets', __('Predefined columns sizes:', 'mv23theme') )
				->add_dependency( 'columns', '6' )
				->set_actions( self::columns_width_presets(array(
					array( 'label' => '6 Columns', 'preset' => '1/6-1/6-1/6-1/6-1/6-1/6', 'image' => '6/1fr-1fr-1fr-1fr-1fr-1fr.png' ),
					array( 'label' => 'Full Width', 'preset' => 'full-br-full-br-full-br-full-br-full-br-full', 'image' => '6/fullwidth.png' ),
				)) ),

			Field::create( 'actions_control', 'common_actions' )
				->hide_label()
				->set_actions( array(
					array( 'label' => __('Add Column', 'mv23theme'), 'command' => 'add-col', 'class' => 'uf-button button-primary' ),
					array( 'label' => __('Lock inner components', 'mv23theme'), 'command' => 'locked-components-toggle' )
				))
		);

		return $fields;
	}

	private static function columns_width_presets( $presets_raw ){
		$presets = [];

		foreach( $presets_raw as $key => $preset ){
			$preset_value = $preset['preset'] ?? $preset['label'];

			$presets[$key] = array(
				'label' => $preset['label'],
				'command' => 'column-width-presets',
				'image' => BUILDER_PATH.'/assets/images/columns/'.$preset['image'],
				'args' => array( 'preset' => str_replace( ' ', '', $preset_value ) )
			);
		}

		return $presets;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;
		
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