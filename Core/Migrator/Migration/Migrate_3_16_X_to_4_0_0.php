<?php
/**
 * Migration class for migrating from version 3.16.X to 4.0.0
 * Standardizes structure to use the new Page_Content component.
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_16_X_to_4_0_0 extends Migrate_Components_Settings_v3 {
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
        $title           = 'Migrate 3.16.X to 4.0.0 ( Page Content Component Implementation )';
        $slug            = 'migrate_3_16_x_to_4_0_0';
        $is_top_level    = true;

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
                            $this->process_components_tree($frame['component'], $frame['component']['components'], $datastore );
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
     * Recursively walks the GJS component tree. When a targeted component type is found,
     * it delegates to migrate_component(). Always recurses into children.
     */
    private function process_components_tree( &$parent_component, &$components, &$datastore ) {
        // $target_types = $this->get_target_component_types();

        foreach ( $components as &$component ) {
            $type = isset($component['type']) ? $component['type'] : '';

            // if ( in_array( $type, $target_types, true ) ) {
                $this->custom_migrate_component( $parent_component, $component, $datastore );
            // }

            // Always recurse into children
            if ( isset($component['components']) && is_array($component['components']) ) {
                $this->process_components_tree( $component, $component['components'], $datastore );
            }
        }
        unset($component);
    }

    protected function custom_migrate_component( &$parent_component, &$component, &$datastore ) {
        $comp_id = isset( $component['__id'] ) ? $component['__id'] : null;
        $old_comp_datastore = $datastore[ $comp_id ] ?? array();

        if( $component['type'] === 'container' ) {
            // if parent is type "wrapper" rename to "page-content"
            if ( isset( $parent_component['type'] ) && $parent_component['type'] === 'wrapper' ) {
                $component['type'] = 'page-content';
                $component['classes'] = ['page-content'];
            }
        }

        if( $component['type'] === 'section' ) {
            $settings = isset( $old_comp_datastore['settings'] ) ? $old_comp_datastore['settings'] : array();
            $layout_arr = isset( $settings['layout'] ) ? $settings['layout'] : array('use'=>0, 'key'=>'__default' );
            $layout = isset( $layout_arr['key'] ) ? $layout_arr['key'] : '__default';

            if ( $layout != 'layout3' ) {
                
                // ensure a container exists for default layout or layout1/layout2
                // move all existing children into a new container if necessary
                // remove settings.layout from datastore
                // remove property: droppable

                if ( ! isset( $component['components'] ) || ! is_array( $component['components'] ) ) {
                    $component['components'] = array();
                }
                
                $container_exists = false;
                $old_container = null;

                // Check if a container already exists
                foreach ( $component['components'] as &$child ) {
                    if ( isset($child['type']) && $child['type'] === 'container' ) {
                        $container_exists = true;
                        $old_container = $child;
                        break;
                    }
                }
                unset($child);

                // Move existing children into a brand new container
                $new_components = ($container_exists) ? $old_container['components'] : $component['components'];
                $component['components'] = array(
                    array(
                        'type' => 'container',
                        'classes' => ['container'],
                        'components' => $new_components,
                    ),
                );
            }

            // Remove settings.layout from datastore
            if ( isset( $old_comp_datastore['settings']['layout'] ) ) {
                unset( $old_comp_datastore['settings']['layout'] );
            }

            // Remove property: droppable
            if ( isset( $component['droppable'] ) ) {
                unset( $component['droppable'] );
            }
        }

        if ( $component['type'] === 'main-content' && ! isset( $component['____migrated_to_v_4_0_0'] ) ) {
            // if parent is not a section, wrap main-content in section>container>main-content
            if ( ! isset( $parent_component['type'] ) || $parent_component['type'] !== 'section' ) {

                $main_content = $component;
                // mark the component as migrated to avoid infinite loops
                $main_content['____migrated_to_v_4_0_0'] = true;

                $component = array(
                    'type' => 'section',
                    'classes' => ['page-module'],
                    'components' => array(
                        array(
                            'type' => 'container',
                            'classes' => ['container'],
                            'components' => array(
                                $main_content
                            ),
                        ),
                    ),
                );
            }
        }

        if ( $component['type'] === 'button' ){
            $button_type = $old_comp_datastore['button_type'];

            $datastore[$comp_id]['download_file'] = ($button_type === 'download') ? 1 : 0;
            unset($datastore[$comp_id]['button_type']);
        }
    }
}