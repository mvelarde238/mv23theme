<?php
/**
 * Migration class for migrating from version 3.15.X to 3.16.0
 * Standardizes components by adding their `type` as a class inside `classes` when missing.
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_15_X_to_3_16_0 extends Migrate_Components_Settings_v3 {
    private static $instance = null;

    // component types that must never get their `type` added as a class
    protected $ignored_component_types = array( 'wrapper', 'theme-options', 'global-styles' );

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
        $title           = 'Migrate 3.15.X to 3.16.0 ( Standardize Component Type Classes )';
        $slug            = 'migrate_3_15_x_to_3_16_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    /**
     * No specific component type targeting — we walk the full tree instead.
     */
    protected function get_target_component_types() {
        return array();
    }

    /**
     * No-op: required by the abstract base class.
     * All work is done in migrate_page_content_data() via our own tree walk.
     */
    protected function migrate_component( &$component, &$datastore ) {}

    protected function get_ignored_component_types() {
        return $this->ignored_component_types;
    }

    /**
     * Overrides parent to walk every component regardless of type.
     */
    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        $content_structure = is_array($old_data) ? $old_data : maybe_unserialize($old_data);
        $datastore         = is_array($old_datastore) ? $old_datastore : maybe_unserialize($old_datastore);

        if ( ! is_array($content_structure) ) {
            return array(
                'page_content'           => $old_data,
                'page_content_datastore' => $old_datastore,
            );
        }

        if ( ! is_array($datastore) ) {
            $datastore = array();
        }

        // Walk every frame's component tree
        if ( isset($content_structure['pages']) && is_array($content_structure['pages']) ) {
            foreach ( $content_structure['pages'] as &$page ) {
                if ( isset($page['frames']) && is_array($page['frames']) ) {
                    foreach ( $page['frames'] as &$frame ) {
                        if ( isset($frame['component']['components']) && is_array($frame['component']['components']) ) {
                            $this->process_components_tree( $frame['component']['components'] );
                        }
                    }
                    unset($frame);
                }
            }
            unset($page);
        }

        return array(
            'page_content'           => $content_structure,
            'page_content_datastore' => $datastore,
        );
    }

    /**
     * Recursively walks the GJS component tree, standardizing each component's classes.
     */
    private function process_components_tree( &$components ) {
        foreach ( $components as &$component ) {
            $this->ensure_type_class( $component );

            if ( isset($component['components']) && is_array($component['components']) ) {
                $this->process_components_tree( $component['components'] );
            }
        }
        unset($component);
    }

    /**
     * Adds the component's `type` to its `classes` array if it's not already present,
     * unless the type is in the ignore list or the component has no `classes` array.
     */
    private function ensure_type_class( &$component ) {
        $type = isset($component['type']) ? $component['type'] : '';

        if ( $type === '' || in_array( $type, $this->get_ignored_component_types(), true ) ) {
            return;
        }

        if ( ! isset($component['classes']) || ! is_array($component['classes']) ) {
            return;
        }

        foreach ( $component['classes'] as $class_entry ) {
            if ( is_string($class_entry) && $class_entry === $type ) {
                return;
            }
            if ( is_array($class_entry) && isset($class_entry['name']) && $class_entry['name'] === $type ) {
                return;
            }
        }

        $component['classes'][] = $type;
    }
}
