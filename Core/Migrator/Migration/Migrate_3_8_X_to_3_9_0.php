<?php
/**
 * Migration class for migrating from version 3.8.X to 3.9.0
 * Adds carousel parts to existing carousel components
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_8_X_to_3_9_0 extends Migrate_Components_Settings_v3 {
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
        $delete_old_data = false;
        $title           = 'Migrate 3.8.X to 3.9.0 ( Carousel Parts Implementation )';
        $slug            = 'migrate_3_8_x_to_3_9_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'carousel-wrapper' );
    }

    protected function migrate_component( &$component, &$datastore ) {
        $cmp_id = isset( $component['__id'] ) ? $component['__id'] : null;
        if ( ! $cmp_id || ! isset( $datastore[ $cmp_id ] ) ) {
            return;
        }

        $old_entry = $datastore[ $cmp_id ];

        // Build carousel-controls inner node
        $controls_id = $this->generate_cmp_id();
        $prev_button_id = $this->generate_cmp_id();
        $next_button_id = $this->generate_cmp_id();
        $controls_node = array(
            'type'       => 'carousel-controls',
            '__id'       => $controls_id,
            'classes'    => array( 'tns-controls', 'carousel__controls' ),
            'components' => array(
                array( 'type' => 'icon-box', '__id' => $prev_button_id, 'attributes' => array( 'data-controls' => 'prev' ) ),
                array( 'type' => 'icon-box', '__id' => $next_button_id, 'attributes' => array( 'data-controls' => 'next' ) ),
            )
        );
        $datastore[ $prev_button_id ] = array(
            '__type' => 'icon-box',
            'source' => 'icon',
            'icon' => PREV_CAROUSEL_ICON,
            'image' => '',
        );
        $datastore[ $next_button_id ] = array(
            '__type' => 'icon-box',
            'source' => 'icon',
            'icon' => NEXT_CAROUSEL_ICON,
            'image' => '',
        );

        // Build carousel-nav inner node
        $nav_id = $this->generate_cmp_id();
        $nav_node = array(
            'type'       => 'carousel-nav',
            '__id'       => $nav_id,
            'classes'    => array( 'tns-nav', 'carousel__nav' )
        );

        // Insert new inner nodes into component structure
        if ( ! isset( $component['components'] ) || ! is_array( $component['components'] ) ) {
            $component['components'] = array();
        }

        // Append new inner nodes to component structure
        $component['components'][] = $controls_node;

        // nav node position depends on datastore.nav_settings.position (default: 'bottom')
        $nav_position = 'bottom'; 
        if ( isset( $old_entry['nav_settings'] ) && isset( $old_entry['nav_settings']['position'] ) ) {
            $nav_position = $old_entry['nav_settings']['position'];
        }
        if ( $nav_position === 'top' ) {
            array_unshift( $component['components'], $nav_node );
        } else {
            $component['components'][] = $nav_node;
        }

        // remove legacy entries from datastore
        unset( $datastore[ $cmp_id ]['customize_icons'] );
    }
}
