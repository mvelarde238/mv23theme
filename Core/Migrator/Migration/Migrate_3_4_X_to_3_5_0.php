<?php
/**
 * Migration class for migrating from version 3.4.X to 3.5.0
 * This migration will handle the changes related to the Icon Box component implementation.
 * Additionally, it will clean up old plain styles meta
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_4_X_to_3_5_0 extends Migrate_Components_Settings_v3{
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $batch_size = 3;
        $do_the_update = true;
        $delete_old_data = true;
        $title = 'Migrate 3.4.X to 3.5.0 ( Icon Box Implementation )';
        $slug = 'migrate_3_4_x_to_3_5_0';
        $is_top_level = false;
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'icon-and-text' );
    }

    /**
     * Migrates a single icon-and-text component:
     * 1. Renames ialignment → icon_alignment, iposition → icon_position in the datastore entry.
     * 2. Extracts isource/iname/iimage from the datastore entry into a new icon-box entry.
     * 3. Replaces the old 'icon' child inside 'icon-wrapper' with a new 'icon-box' node.
     * 4. Cleans up old 'icon' __id from the datastore if it existed.
     */
    protected function migrate_component( &$component, &$datastore ) {
        $cmp_id = isset($component['__id']) ? $component['__id'] : null;
        if ( ! $cmp_id || ! isset($datastore[$cmp_id]) ) {
            return;
        }

        $entry = &$datastore[$cmp_id];

        // Skip condition: if isource doesn't exist, component is already migrated
        if ( ! isset($entry['isource']) ) {
            return;
        }

        // Extract icon data before removing
        $old_isource = isset($entry['isource']) ? $entry['isource'] : 'icon';
        $old_iname   = isset($entry['iname'])   ? $entry['iname']   : 'bi-box-seam';
        $old_iimage  = isset($entry['iimage'])  ? $entry['iimage']  : '';

        // Rename fields
        if ( isset($entry['ialignment']) ) {
            $entry['icon_alignment'] = $entry['ialignment'];
            unset($entry['ialignment']);
        }
        if ( isset($entry['iposition']) ) {
            $entry['icon_position'] = $entry['iposition'];
            unset($entry['iposition']);
        }

        // Remove icon fields from icon-and-text entry
        unset($entry['isource']);
        unset($entry['iname']);
        unset($entry['iimage']);

        // Find the icon-wrapper child and its icon child
        $old_icon_id  = null;
        $old_icon_attr_id = null;

        if ( isset($component['components']) && is_array($component['components']) ) {
            foreach ( $component['components'] as &$child ) {
                $child_type = isset($child['type']) ? $child['type'] : '';

                if ( $child_type === 'icon-wrapper' ) {
                    if ( isset($child['components']) && is_array($child['components']) ) {
                        foreach ( $child['components'] as $idx => $icon_child ) {
                            $icon_child_type = isset($icon_child['type']) ? $icon_child['type'] : '';

                            if ( $icon_child_type === 'icon' ) {
                                // Capture old IDs for cleanup and reuse
                                $old_icon_id = isset($icon_child['__id']) ? $icon_child['__id'] : null;
                                $old_icon_attr_id = isset($icon_child['attributes']['id']) ? $icon_child['attributes']['id'] : null;

                                // Generate new __id for the icon-box component
                                $new_icon_box_id = $this->generate_cmp_id();

                                // Build the new icon-box GJS node, preserving the old attribute id
                                $new_icon_box_node = array(
                                    'type'       => 'icon-box',
                                    '__id'       => $new_icon_box_id,
                                    'attributes' => array(),
                                    'classes'    => array('icon-box', 'component'),
                                    'components' => array(),
                                );
                                if ( $old_icon_attr_id ) {
                                    $new_icon_box_node['attributes']['id'] = $old_icon_attr_id;
                                }

                                // Replace the old icon node with the new icon-box node
                                $child['components'][$idx] = $new_icon_box_node;

                                // Create datastore entry for the new icon-box
                                $datastore[$new_icon_box_id] = array(
                                    '__type'  => 'icon-box',
                                    'source'  => $old_isource,
                                    'icon'    => $old_iname,
                                    'image'   => $old_iimage,
                                );

                                // Cleanup: remove old icon __id from datastore if it existed
                                if ( $old_icon_id && isset($datastore[$old_icon_id]) ) {
                                    unset($datastore[$old_icon_id]);
                                }

                                break; // Only process the first icon child
                            }
                        }
                    }
                    break; // Only process the first icon-wrapper child
                }
            }
            unset($child);
        }

        unset($entry);
    }

    protected function after_page_migration( $post_id ) {
        if ( $this->delete_old_data ) {
            delete_post_meta( $post_id, 'page_content_styles' );
        }
    }
}