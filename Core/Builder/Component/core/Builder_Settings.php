<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;

class Builder_Settings extends Component {

    public function __construct() {
		parent::__construct(
			'builder-settings',
			__( 'Builder Settings', 'mv23theme' ),
			array(
				'common_settings' => array(),
			)
		);

		add_action( 'admin_init', array( $this, 'change_datastore' ) );
	}

	public function change_datastore() {
		// on save component, create a new datastore and save the fields
		add_action( 'uf.ultimate_builder.save_component', function( $processed_values, &$component_data, $group, $ultimate_builder ) {
		    $in_post_context = isset( $_GET['post'] ) || ( wp_doing_ajax() && isset( $_POST['post_id'] ) );
	    	if ( $component_data['__type'] == 'builder-settings' && $in_post_context ) {
		        // create a new datastore for global settings
		        $datastore = new \Ultimate_Fields\Datastore\Options;

				// Associate the datastore with the group so that the values are saved correctly
		        $group->set_datastore( $datastore );

				// save() returns an array of errors if there are validation issues, so we can check that before committing to the database
		        $errors = $group->save( $component_data );

		        // If there are no errors, commit the datastore. If there are errors, they will be displayed in the builder interface and the datastore will not be committed, preventing invalid data from being saved.
		        if ( empty( $errors ) ) {
		            $datastore->commit();
					// Mark that this group has a custom datastore to prevent it from being saved in page_content_datastore as well
					$component_data['has_custom_datastore'] = true;
		        }
		    }
		}, 10, 4 );
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
        $builder_settings_default = get_option( 'builder_settings', array(
			'on_select_component_behavior' => 'component-settings',
			'default_sections_padding' => array(
				'top' => 40,
				'left' => 0,
				'right' => 0,
				'bottom' => 40
			),
			'default_columns_gap' => 2,
			'preset_icon_styles' => array(
				'padding' => '20px',
				'color' => '#ffffff',
				'borderWidth' => '2px',
				'rounded' => '12px',
				'backgroundColor' => 'var(--primary-color)',
				'gradient' => 'linear-gradient(135deg, #00BCD4 0%, #3858e9 100%)'
			)
		) );
		// *Note* If these default values are changed, 
		// make sure to update the corresponding defaults in the JavaScript handler 
		// as well to keep them in sync: (src\js\builder\plugins\handle-builder-settings.js).

		$width_style = 'width: 50%; min-width: initial;';

		$fields = array(
			Field::create( 'section', 'builder_settings_section', __('Builder Settings', 'mv23theme') ),
            Field::create( 'complex', 'builder_settings' )->add_fields(array(
				Field::create( 'tab', 'general_settings', __('General Settings', 'mv23theme')),
				Field::create( 'select', 'on_select_component_behavior', __('On select component','mv23theme'))->add_options(array(
					'component-settings' => __('Open Component Settings', 'mv23theme'),
                    'styles' => __('Open Style Manager', 'mv23theme'),
					'blocks' => __('Open Components Tab', 'mv23theme'),
                    'none' => __('Do Nothing', 'mv23theme'),
				))->set_description(__('Choose what happens when a component is selected.', 'mv23theme')),

				Field::create( 'tab', 'sections_customizations', __('Sections', 'mv23theme')),
				Field::create( 'complex', 'default_sections_padding', __('Default Sections Padding (pixels)', 'mv23theme') )->add_fields(array(
            		Field::create( 'number', 'top' )->hide_label()->set_prefix(__('Top', 'mv23theme'))->set_placeholder('40')->set_minimum(0)->set_attr( 'data-input-alignment', 'center'),
            		Field::create( 'number', 'left' )->hide_label()->set_prefix(__('Left', 'mv23theme'))->set_placeholder('0')->set_minimum(0)->set_attr( 'style', $width_style ),
            		Field::create( 'number', 'right' )->hide_label()->set_prefix(__('Right', 'mv23theme'))->set_placeholder('0')->set_minimum(0)->set_attr( 'style', $width_style ),
            		Field::create( 'number', 'bottom' )->hide_label()->set_prefix(__('Bottom', 'mv23theme'))->set_placeholder('40')->set_minimum(0)->set_attr( 'data-input-alignment', 'center')
        		)),

				Field::create( 'tab', 'columns_customizations', __('Columns', 'mv23theme')),
				Field::create( 'number', 'default_columns_gap' )
					->hide_label()->set_prefix( __('Default Columns Gap', 'mv23theme'))
					->set_minimum(0)->set_placeholder("2")->set_suffix('%'),

				Field::create( 'tab', 'icon_styles_customizations', __('Preset Icon Styles', 'mv23theme')),
				Field::create( 'complex', 'preset_icon_styles' )->hide_label()->add_fields(array(
            		Field::create( 'text', 'padding' )->set_placeholder('20px')->set_attr( 'style', $width_style ),
            		Field::create( 'text', 'color' )->set_placeholder('#ffffff')->set_attr( 'style', $width_style ),
            		Field::create( 'text', 'borderWidth', __('Border Width', 'mv23theme'))->set_placeholder('2px')->set_attr( 'style', $width_style ),
            		Field::create( 'text', 'rounded' )->set_placeholder('12px')->set_attr( 'style', $width_style ),
            		Field::create( 'text', 'backgroundColor', __('Background Color', 'mv23theme'))->set_placeholder('var(--primary-color)'),
            		Field::create( 'textarea', 'gradient' )->set_rows(2)->set_placeholder('linear-gradient(135deg, #00BCD4 0%, #3858e9 100%)'),
        		)),
			))->hide_label()->set_default_value( $builder_settings_default ),
			// End of builder settings complex field, no more fields can be added after this.
        );

		return $fields;
	}

	public static function display( $args ){
		return '';
	}
}

new Builder_Settings();