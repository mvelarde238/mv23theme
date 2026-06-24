<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core;

class Postcard extends Component {

    public function __construct() {
		parent::__construct(
			'postcard',
			__( 'Postcard', 'mv23theme' ),
			array(
				'common_settings' => array(
					'settings'
				),
			)
		);

        add_action( 'init', array( $this, 'change_datastore' ) );
	}

	public static function get_icon() {
        return 'bi-credit-card-2-front';
    }

	public static function get_builder_data() {
        return array(
			'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array();

        # Add post types
		$default_connected_posttype = get_post_meta( $_GET['post'] ?? null, 'connected_posttype', true );
		$fields[] = Field::create( 'radio', 'connected_posttype' )
            ->set_description( __('Choose a post type to preview how this postcard will look when rendering posts of that type. Save the template to see the changes reflected.', 'mv23theme') )
			->set_orientation( 'horizontal' )
			->set_default_value( $default_connected_posttype ? $default_connected_posttype : 'post' )
            ->set_options_callback( function() {
                return Core::get_post_types(array(
                    'get_post_type_args' => array( 'public'=>true, 'exclude_from_search'=>false ),
                ));
            });

		return $fields;
	}

    public function change_datastore() {
        // on read component, create a new datastore to read the fields
        add_filter( 'uf.ultimate_builder.group_datastore', function( $datastore, $component, $repeater ) {
            if ( $component['__type'] == 'postcard' && isset( $_GET['post'] ) ) {
                $datastore = new \Ultimate_Fields\Datastore\Post_Meta;
        	    $datastore->set_id( $_GET['post'] ?? null );
            }
            return $datastore;
        }, 10, 3 );
        
        // on save component, create a new datastore and save the fields
        add_action( 'uf.ultimate_builder.save_component', function( $processed_values, $component, $group, $ultimate_builder ) {
            if ( $component['__type'] == 'postcard' && isset( $_GET['post'] ) ) {
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

    public static function display( $args ){
        global $post;
        $args['additional_attributes']['data-id'] = esc_attr($post->ID);

        $postcard_settings = $args['postcard_settings'] ?? array();
        if (!empty($postcard_settings['on_click_post'])) {
           $args['additional_attributes']['data-action'] = esc_attr($postcard_settings['on_click_post']);
        }
        if (!empty($postcard_settings['on_click_scroll_to'])) {
            $args['additional_attributes']['data-scroll-to'] = esc_attr($postcard_settings['on_click_scroll_to']);
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Postcard();