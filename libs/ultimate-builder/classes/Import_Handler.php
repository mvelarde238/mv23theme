<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Core\Includes\Theme_Version;

/**
 * Handles AJAX import of a previously exported page_content payload.
 *
 * @since 1.0
 */
class Import_Handler {

	/**
	 * AJAX: validate and persist an imported page_content payload.
	 */
	public static function ajax_import() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ultimate_builder_import' ) ) {
			wp_send_json_error( 'nonce_invalid' );
		}

		$post_id  = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$meta     = isset( $_POST['meta'] ) ? sanitize_text_field( $_POST['meta'] ) : '';
		$data_raw = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
		$payload  = json_decode( $data_raw, true );

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( 'no_permissions' );
		}

		if (
			! is_array( $payload ) ||
			! isset( $payload['theme_version'], $payload['post_type'], $payload['page_content'], $payload['page_content_datastore'] )
		) {
			wp_send_json_error( 'invalid_payload' );
		}

		if ( ! self::versions_are_compatible( $payload['theme_version'], Theme_Version::getInstance()->get_current_version() ) ) {
			wp_send_json_error( 'version_mismatch' );
		}

		if ( $payload['post_type'] !== get_post_type( $post_id ) ) {
			wp_send_json_error( 'post_type_mismatch' );
		}

		list( $field_instance, $datastore ) = self::get_field_and_datastore( $post_id, $meta );
		if ( ! $field_instance ) {
			wp_send_json_error( 'field_not_found' );
		}

		$field_instance->save( array(
			$meta => array(
				'builder_data'     => $payload['page_content'],
				'components_data'  => $payload['page_content_datastore'],
			),
		) );

		// Field::save() only stages values in memory, so the datastore must be committed explicitly.
		$datastore->commit();

		wp_send_json_success();
	}

	/**
	 * Looks up the builder field for a post and attaches a fresh post meta datastore to it.
	 *
	 * @return array{0: Field|null, 1: \Ultimate_Fields\Datastore\Post_Meta|null}
	 */
	private static function get_field_and_datastore( $post_id, $meta_name ) {
		// Trigger Ultimate Fields initialization in post context
		do_action( 'uf.init' );

		$containers = \Ultimate_Fields\Container::get_registered();

		foreach ( $containers as $container ) {
			if ( $container->get_id() !== 'page_content_container' ) {
				continue;
			}

			foreach ( $container->get_fields() as $field ) {
				if ( $field->get_name() === $meta_name && $field instanceof Field ) {
					$datastore = new \Ultimate_Fields\Datastore\Post_Meta();
					$datastore->set_id( $post_id );
					$field->set_datastore( $datastore );

					return array( $field, $datastore );
				}
			}
		}

		return array( null, null );
	}

	/**
	 * Compares only the major.minor (aa.bb) segments of two version strings.
	 */
	private static function versions_are_compatible( $imported_version, $current_version ) {
		$imported = self::get_major_minor( $imported_version );
		$current  = self::get_major_minor( $current_version );

		return ( null !== $imported && $imported === $current );
	}

	private static function get_major_minor( $version ) {
		if ( ! is_string( $version ) || ! preg_match( '/^(\d+\.\d+)/', $version, $matches ) ) {
			return null;
		}

		return $matches[1];
	}
}
