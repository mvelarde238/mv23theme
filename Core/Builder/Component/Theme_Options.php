<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Theme_Options\Fields\Logos;
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
		// on read component, create a new datastore to read the fields
		add_filter( 'uf.ultimate_builder.group_datastore', function( $datastore, $component, $repeater ) {
		    if ( $component['__type'] == 'theme-options' && isset( $_GET['post'] ) ) {
		        $datastore = new \Ultimate_Fields\Datastore\Options;
		    }
		    return $datastore;
		}, 10, 3 );

		// on save component, create a new datastore and save the fields
		add_action( 'uf.ultimate_builder.save_component', function( $processed_values, $component, $group, $ultimate_builder ) {
		    if ( $component['__type'] == 'theme-options' && isset( $_GET['post'] ) ) {
		        // create a new datastore for post meta
		        $datastore = new \Ultimate_Fields\Datastore\Options;

		        // Asociar el datastore al grupo para que los valores se guarden correctamente
		        $group->set_datastore( $datastore );

		        // save() procesa y valida todos los campos
		        $errors = $group->save( $component );

		        // Guardar en la base de datos si no hay errores
		        if ( empty( $errors ) ) {
		            $datastore->commit();
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
        $fields = array_merge( $fields, Logos::get_fields() );
        $fields = array_merge( $fields, Colors::get_fields() );
        $fields = array_merge( $fields, Typography::get_fields() );
        $fields = array_merge( $fields, Page_Container::get_fields() );

		return $fields;
	}
}

new Theme_Options();