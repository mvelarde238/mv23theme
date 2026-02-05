<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Posttype\Archive_Page;

class Archive_Page_Structure extends Component {

    public function __construct() {
		parent::__construct(
			'archive-page-structure',
			__( 'Archive Structure', 'mv23theme' ),
			array(
				'common_settings' => array(),
			)
		);

		add_action( 'admin_init', array( $this, 'change_datastore' ) );
	}

	public function change_datastore() {
		// on export component, create a new datastore to read the fields
		add_filter( 'uf.ultimate_builder.group_datastore', function( $datastore, $component, $repeater ) {
		    if ( $component['__type'] == 'archive-page-structure' && isset( $_GET['post'] ) ) {
                $datastore = new \Ultimate_Fields\Datastore\Post_Meta;
        	    $datastore->set_id( $_GET['post'] ?? null );
		    }
		    return $datastore;
		}, 10, 3 );

		// on save component, create a new datastore and save the fields
		add_action( 'uf.ultimate_builder.save_component', function( $processed_values, $component, $group, $ultimate_builder ) {
		    if ( $component['__type'] == 'archive-page-structure' && isset( $_GET['post'] ) ) {
		        // create a new datastore for post meta
		        $datastore = new \Ultimate_Fields\Datastore\Post_Meta;
                $datastore->set_id( $_GET['post'] ?? null );

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
        $fields = Archive_Page::get_fields();
		return $fields;
	}
}

new Archive_Page_Structure();