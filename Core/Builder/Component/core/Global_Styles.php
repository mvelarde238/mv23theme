<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Theme_Options\Fields\Device_Switch;
use Core\Theme_Options\Fields\Global_Styles as Global_Styles_Options;

class Global_Styles extends Component {

    public function __construct() {
		parent::__construct(
			'global-styles',
			__( 'Global Styles', 'mv23theme' ),
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
	    	if ( $component_data['__type'] == 'global-styles' && $in_post_context ) {
		        // create a new datastore for global styles
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
        $fields = array_merge( $fields, Device_Switch::get_fields() );
        $fields = array_merge( $fields, Global_Styles_Options::get_fields() );

		return $fields;
	}
}

new Global_Styles();