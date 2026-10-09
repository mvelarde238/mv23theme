<?php
namespace Core\Migrator\Migration\V4;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_4_0_X_to_4_1_0 extends Migrate_Components_Settings_v3 {
    private static $instance = null;

    public static function getInstance() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $batch_size      = 3;
        $do_the_update   = true;
        $delete_old_data = true;
        $title           = 'Migrate 4.0.X to 4.1.0 (Listing Query Parameters implementation)';
        $slug            = 'migrate_4_0_x_to_4_1_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'listing' );
    }

    protected function migrate_component( &$component, &$datastore ) {
        $component_id = $component['__id'] ?? null;
        if ( ! $component_id || ! isset( $datastore[ $component_id ] ) || ! is_array( $datastore[ $component_id ] ) ) {
            return;
        }

        if ( $component['type'] === 'listing' ) {
            $datastore[ $component_id ] = $this->migrate_listing_datastore( $datastore[ $component_id ] );
            return;
        }
    }

    private function migrate_listing_datastore( $entry ) {
        $settings = $this->extract_legacy_listing_settings( $entry );

        $entry['query_parameters'] = $this->build_repeater_settings( $settings );

        unset( $entry['query_params']);
        unset( $entry['status_params'] );

        return $entry;
    }

    private function extract_legacy_listing_settings( $settings ) {
        $mapped = [];

        $query_params = $settings['query_params'] ?? array();

        if ( array_key_exists( 'posts_per_page', $query_params ) && is_numeric( $query_params['posts_per_page'] ) ) {
            $mapped[] = array(
                'key' => 'posts_per_page',
                'value' => (int) $query_params['posts_per_page'],
            );
        }

        if ( array_key_exists( 'order', $query_params ) ) {
            $mapped[] = array(
                'key' => 'order',
                'value' => $query_params['order'],
            );
        }

        if ( array_key_exists( 'orderby', $query_params ) ) {
            $mapped[] = array(
                'key' => 'orderby',
                'value' => $query_params['orderby'],
            );
        }

        if ( array_key_exists( 'offset', $query_params ) && is_numeric( $query_params['offset'] ) && $query_params['offset'] > 0 ) {
            $mapped[] = array(
                'key' => 'offset',
                'value' => (int) $query_params['offset'],
            );
        }

        if ( array_key_exists( 'status_params', $settings ) && is_array( $settings['status_params'] ) ) {
            $status_params = $settings['status_params'];
            $set_post_status = $status_params['set_post_status'] ?? false;
            $post_statuses = $status_params['post_status'] ?? array(); // array of post statuses

            if ( $set_post_status && ! empty( $post_statuses ) ) {
                $mapped[] = array(
                    'key' => 'post_status',
                    'value' => implode(',', $post_statuses),
                );
            }
        }

        return $mapped;
    }

    private function build_repeater_settings( $settings ) {
        if ( ! is_array( $settings ) ) {
            return array();
        }

        $repeater = array();
        foreach ( $settings as $parameter ) {
            if ( ! isset( $parameter['key'] ) || ! isset( $parameter['value'] ) ) {
                continue;
            }

            $repeater[] = array(
                '__type' => $parameter['key'],
                'value' => $parameter['value'],
            );
        }

        return $repeater;
    }
}