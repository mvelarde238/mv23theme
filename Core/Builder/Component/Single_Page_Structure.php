<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Single_Page_Structure extends Component {

    public function __construct() {
		parent::__construct(
			'single_page_structure',
			__( 'Single Structure', 'mv23theme' ),
			array(
				'common_settings' => array(),
			)
		);

		add_action( 'init', array( $this, 'change_datastore' ) );
	}

	public function change_datastore() {
		// on read component, create a new datastore to read the fields
		add_filter( 'uf.ultimate_builder.group_datastore', function( $datastore, $component, $repeater ) {
		    if ( $component['__type'] == 'single_page_structure' && isset( $_GET['post'] ) ) {
		        $datastore = new \Ultimate_Fields\Datastore\Options;
		    }
		    return $datastore;
		}, 10, 3 );

		// on save component, create a new datastore and save the fields
		add_action( 'uf.ultimate_builder.save_component', function( $processed_values, $component, $group, $ultimate_builder ) {
		    if ( $component['__type'] == 'single_page_structure' && isset( $_GET['post'] ) ) {
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
		$post_id = isset( $_REQUEST['post'] ) ? absint( $_REQUEST['post'] ) : 0;
		$post_type = get_post_type( $post_id );
		$setting_name = ($post_type) ? 'single_' . $post_type . '_settings' : 'single_settings';
		$default_values = get_option( $setting_name, array() );

		$fields = array(
			Field::create( 'message', 'hidden_info', __( 'This structure is global and will be used for all single pages of the same post type.', 'mv23theme' ) )
				->set_default_value(array( 'setting_name' => $setting_name)),
			Field::create( 'complex', $setting_name )->hide_label()->add_fields(array(
				Field::create( 'select', 'page_template', __( 'Page Template', 'mv23theme' ) )->add_options(array(
					'main-content--sidebar-right' => __('Sidebar Right','mv23theme'),
					'main-content--sidebar-left' => __('Sidebar Left','mv23theme'),
					'main-content--sidebarless' => __('Full Width','mv23theme')
				)),
				Field::create( 'checkbox', 'hide_post_title')->fancy()->hide_label()->set_text( __( 'Hide the post title', 'mv23theme' ) ),
				Field::create( 'checkbox', 'hide_social_share')->fancy()->hide_label()->set_text( __( 'Hide social share', 'mv23theme' ) ),
				Field::create( 'checkbox', 'hide_related_posts')->fancy()->hide_label()->set_text( __( 'Hide related posts', 'mv23theme' ) ),
			))->set_default_value( $default_values ),
        );
		return $fields;
	}
}

new Single_Page_Structure();