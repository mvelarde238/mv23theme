<?php
namespace Ultimate_Fields\Ultimate_Builder;

/**
 * Handles preview functionality for the Ultimate Builder.
 * 
 * This class manages the preview system including:
 * - AJAX save handler for preview data
 * - Field instance retrieval
 * - Frontend preview injection via transients
 *
 * @since 1.0
 */
class Preview_Handler {

	/**
	 * AJAX: save preview data and return preview URL
	 */
	public static function ajax_preview_save(){
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ultimate_builder_preview' ) ) {
			wp_send_json_error( 'nonce_invalid' );
		}
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$meta    = isset( $_POST['meta'] ) ? sanitize_text_field( $_POST['meta'] ) : '';
		$data_raw = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
		$data = json_decode( $data_raw, true );

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( 'no_permissions' );
		}

		// Obtain the builder field instance
		$field_instance = self::get_field_instance( $post_id, $meta );
		
		if ( ! $field_instance ) {
			wp_send_json_error( 'field_not_found' );
		}

		// Proccess the data to save and send components correctly for preview
		$components_data = array();
		$components_data_raw = isset( $data['components_data'] ) ? $data['components_data'] : array();
		$field_instance->save( array(
			$meta => array(
				'builder_data' => isset( $data['builder_data'] ) ? $data['builder_data'] : array(),
				'components_data' => $components_data_raw,
				'css' => isset( $data['css'] ) ? $data['css'] : '',
			)
		) );
		$components_data = $field_instance->get_value( $meta . '_components' );

		// Save the data in a transient for previewing
		$token = function_exists( 'wp_generate_uuid4' ) ? wp_generate_uuid4() : uniqid( 'ubp_', true );
		$transient_key = 'ub_preview_' . $token;
		
		set_transient( $transient_key, array(
			'post_id' => $post_id,
			'meta_base' => $meta,
			'data' => array(
						$meta . '_components' => $components_data,
						$meta . '_styles'     => isset( $data['css'] ) ? $data['css'] : '',
			)
		), 10 * MINUTE_IN_SECONDS );

		$preview_url = get_permalink( $post_id );
		$preview_url = add_query_arg( 'ub_preview', $token, $preview_url );

		wp_send_json_success( array( 'preview_url' => $preview_url ) );
	}

	/**
	 * Obtains the field instance for a given post ID and meta name.
	 */
	private static function get_field_instance( $post_id, $meta_name ) {
		// Trigger Ultimate Fields initialization in post context
		do_action( 'uf.init' );
		
		// Search for the field in registered containers
		$containers = \Ultimate_Fields\Container::get_registered();
		
		foreach ( $containers as $container ) {
            // Verify the container id is "page_content_container"
            if ( $container->get_id() !== 'page_content_container' ) {
                continue;
            }

			// Search for the specific field by name and type
			// We don't verify locations here because we're in an AJAX context
			// and the post_id has already been validated with permissions in ajax_preview_save()
			$fields = $container->get_fields();
			foreach ( $fields as $field ) {
				if ( $field->get_name() === $meta_name && $field instanceof Field ) {
					// Configure the datastore for the field
					$datastore = new \Ultimate_Fields\Datastore\Post_Meta();
					$datastore->set_id( $post_id );
					$field->set_datastore( $datastore );
					return $field;
				}
			}
		}

		return null;
	}

	/**
	 * If there is ?ub_preview=TOKEN in the frontend, injects the meta saved in the transient
	 */
	public static function maybe_apply_preview(){
		if ( ! is_admin() && isset( $_GET['ub_preview'] ) ) {
			$token = sanitize_text_field( $_GET['ub_preview'] );
			$t = get_transient( 'ub_preview_' . $token );
			if ( $t && is_array( $t ) && isset( $t['post_id'], $t['data'] ) ) {
				// Static cache to avoid repeated reads
				static $cache = array();
				
				add_filter( 'get_post_metadata', function( $null, $object_id, $meta_key, $single ) use ( $t, &$cache ) {
					if ( intval( $object_id ) === intval( $t['post_id'] ) && isset( $t['data'][ $meta_key ] ) ) {
						$cache_key = $object_id . '_' . $meta_key . '_' . ($single ? '1' : '0');
						if ( !isset( $cache[ $cache_key ] ) ) {
							// CRITICAL: WordPress expects an array when $single=true to extract [0]
							// Therefore, we always return array( value )
							$cache[ $cache_key ] = array( $t['data'][ $meta_key ] );
						}
						return $cache[ $cache_key ];
					}
					return null;
				}, 10, 4 );
			}
		}
	}
}
