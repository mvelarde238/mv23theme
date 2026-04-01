<?php
/**
 * Migration class for migrating from version 3.5.X to 3.6.0
 * This migration renames the CSS "gap" property to "column-gap" in styles
 * targeting row-component elements, since row-components now use column-gap.
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_5_X_to_3_6_0 extends Migrate_Components_Settings_v3 {
    private static $instance = null;

    /**
     * Collects attribute IDs of row-component elements during the tree walk.
     * @var array
     */
    private $row_component_ids = array();

    public static function getInstance() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $batch_size     = 3;
        $do_the_update  = true;
        $delete_old_data = false;
        $title          = 'Migrate 3.5.X to 3.6.0 ( Row Component gap → column-gap )';
        $slug           = 'migrate_3_5_x_to_3_6_0';
        $is_top_level   = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'row-component' );
    }

    /**
     * Collects the HTML attribute ID of each row-component so we can
     * later match them against selectors in the styles array.
     */
    protected function migrate_component( &$component, &$datastore ) {
        if ( isset( $component['attributes']['id'] ) ) {
            $this->row_component_ids[] = $component['attributes']['id'];
        }
    }

    /**
     * Extends the parent migration to also process the styles array:
     * after collecting row-component IDs via the tree walk, iterates
     * through styles and renames "gap" → "column-gap" for selectors
     * that match a row-component ID.
     */
    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        // Reset collected IDs for this page
        $this->row_component_ids = array();

        // Parent walks the component tree, calling migrate_component() for each row-component
        $result = parent::migrate_page_content_data( $old_data, $old_datastore );

        $content_structure = $result['page_content'];

        if ( ! empty( $this->row_component_ids ) && isset( $content_structure['styles'] ) && is_array( $content_structure['styles'] ) ) {
            // Build a lookup set of selectors like "#id82853"
            $selector_set = array();
            foreach ( $this->row_component_ids as $attr_id ) {
                $selector_set[ '#' . $attr_id ] = true;
            }

            foreach ( $content_structure['styles'] as &$style_entry ) {
                if ( ! isset( $style_entry['style']['gap'] ) ) {
                    continue;
                }

                // Check if any selector matches a row-component ID
                if ( isset( $style_entry['selectors'] ) && is_array( $style_entry['selectors'] ) ) {
                    foreach ( $style_entry['selectors'] as $selector ) {
                        $selector_name = is_array( $selector ) ? ( isset( $selector['name'] ) ? $selector['name'] : '' ) : $selector;

                        if ( isset( $selector_set[ $selector_name ] ) ) {
                            $style_entry['style']['column-gap'] = $style_entry['style']['gap'];
                            unset( $style_entry['style']['gap'] );
                            break;
                        }
                    }
                }
            }
            unset( $style_entry );

            $result['page_content'] = $content_structure;
        }

        return $result;
    }
}