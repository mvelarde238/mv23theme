<?php
/**
 * Migration class for migrating from version 3.6.X to 3.7.0
 * 1. Removes the togglebox-wrapper component, promoting its child togglebox.
 * 2. Renames "v23-" prefixed classes on all togglebox-related components
 *    (v23-togglebox → togglebox, v23-togglebox__nav → togglebox__nav, etc.)
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_6_X_to_3_7_0 extends Migrate_Components_Settings_v3 {
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
        $title           = 'Migrate 3.6.X to 3.7.0 ( Remove togglebox-wrapper, rename v23- classes )';
        $slug            = 'migrate_3_6_x_to_3_7_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array(
            'togglebox-wrapper',
            'togglebox',
            'togglebox-nav',
            'togglebox-items',
            'togglebox-item',
            'togglebox-button',
        );
    }

    protected function migrate_component( &$component, &$datastore ) {
        $type = isset( $component['type'] ) ? $component['type'] : '';

        // 1. Unwrap togglebox-wrapper
        if ( $type === 'togglebox-wrapper' ) {
            $this->unwrap_togglebox_wrapper( $component, $datastore );
        }

        // 2. Rename v23- prefixed classes on every matched component
        $this->rename_v23_classes( $component );
    }

    /**
     * Unwraps a togglebox-wrapper by replacing it in-place with its child togglebox.
     */
    private function unwrap_togglebox_wrapper( &$component, &$datastore ) {
        if ( ! isset( $component['components'] ) || ! is_array( $component['components'] ) ) {
            return;
        }

        // Find the child togglebox
        $togglebox_child = null;
        foreach ( $component['components'] as $child ) {
            if ( isset( $child['type'] ) && $child['type'] === 'togglebox' ) {
                $togglebox_child = $child;
                break;
            }
        }

        if ( ! $togglebox_child ) {
            return;
        }

        // Collect IDs
        $wrapper_attr_id  = isset( $component['attributes']['id'] ) ? $component['attributes']['id'] : null;
        $wrapper_cmp_id   = isset( $component['__id'] ) ? $component['__id'] : null;
        $togglebox_cmp_id = isset( $togglebox_child['__id'] ) ? $togglebox_child['__id'] : null;

        // Build new classes: togglebox's classes + 'component'
        $new_classes   = isset( $togglebox_child['classes'] ) ? $togglebox_child['classes'] : array();
        $new_classes[] = 'component';

        // Build new attributes: togglebox's attributes + wrapper's id (for style continuity)
        $new_attributes = isset( $togglebox_child['attributes'] ) ? $togglebox_child['attributes'] : array();
        if ( $wrapper_attr_id ) {
            $new_attributes['id'] = $wrapper_attr_id;
        }

        // Overwrite the wrapper node with the togglebox child's core properties
        $component['type']       = 'togglebox';
        $component['classes']    = $new_classes;
        $component['attributes'] = $new_attributes;
        $component['components'] = isset( $togglebox_child['components'] ) ? $togglebox_child['components'] : array();

        // Keep the wrapper's __id — it owns the datastore entry (togglebox had none).
        if ( $wrapper_cmp_id && isset( $datastore[ $wrapper_cmp_id ] ) ) {
            $datastore[ $wrapper_cmp_id ]['__type'] = 'togglebox';
        }

        // Clean up togglebox child's datastore entry if it existed
        if ( $togglebox_cmp_id && $togglebox_cmp_id !== $wrapper_cmp_id && isset( $datastore[ $togglebox_cmp_id ] ) ) {
            unset( $datastore[ $togglebox_cmp_id ] );
        }
    }

    /**
     * Removes the "v23-" prefix from togglebox-related class names.
     * Handles both plain string classes and associative array classes with a "name" key.
     */
    private function rename_v23_classes( &$component ) {
        if ( ! isset( $component['classes'] ) || ! is_array( $component['classes'] ) ) {
            return;
        }

        // add .components-wrapper to togglebox-item to fix styles after the unwrapping
        $type = isset( $component['type'] ) ? $component['type'] : '';
        if ( $type === 'togglebox-item' ) {
            $component['classes'][] = 'components-wrapper';
        }

        foreach ( $component['classes'] as &$class_entry ) {
            if ( is_string( $class_entry ) && strpos( $class_entry, 'v23-togglebox' ) === 0 ) {
                $class_entry = substr( $class_entry, 4 ); // remove "v23-"
            } elseif ( is_array( $class_entry ) && isset( $class_entry['name'] ) && strpos( $class_entry['name'], 'v23-togglebox' ) === 0 ) {
                $class_entry['name'] = substr( $class_entry['name'], 4 ); // remove "v23-"
            }
        }
        unset( $class_entry );
    }
}