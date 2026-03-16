<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Theme_Options\Fields\Global_Settings;
use Core\Theme_Options\Fields\Colors;
use Core\Theme_Options\Fields\Typography;
use Core\Theme_Options\Fields\Page_Container;

class Theme_Options extends Component {

    public function __construct() {
		parent::__construct(
			'theme-options',
			__( 'Theme Options', 'mv23theme' ),
			array(
				'common_settings' => array(),
			)
		);

		add_action( 'admin_init', array( $this, 'change_datastore' ) );
	}

	public function change_datastore() {
		// ************************************************************************************************************************************
		// This is not working for theme options because is loading dinamically. To handle this, all fields have a default value
		// that is used when the field is rendered, so even if the datastore is not working, the fields will have a value to display and save.
		// ************************************************************************************************************************************
		// on read component, create a new datastore to read the fields
		// add_filter( 'uf.ultimate_builder.group_datastore', function( $datastore, $component, $repeater ) {
		// 	error_log( print_r( $component['__type'], true ) );
		//     if ( $component['__type'] == 'theme-options' && isset( $_GET['post'] ) ) {
		//         $datastore = new \Ultimate_Fields\Datastore\Options;
		// 		error_log( 'Theme Options: '.print_r( $component, true ) );
		//     }
		//     return $datastore;
		// }, 10, 3 );

		// on save component, create a new datastore and save the fields
		add_action( 'uf.ultimate_builder.save_component', function( $processed_values, &$component_data, $group, $ultimate_builder ) {
		    if ( $component_data['__type'] == 'theme-options' && isset( $_GET['post'] ) ) {
		        // create a new datastore for theme options
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
            'display_gjs_block' => false,
			'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {
		$fields = array();
        $fields = array_merge( $fields, Colors::get_fields() );
        $fields = array_merge( $fields, Typography::get_fields() );
        $fields = array_merge( $fields, Page_Container::get_fields() );
        $fields = array_merge( $fields, Global_Settings::get_fields() );

		return $fields;
	}
}

new Theme_Options();