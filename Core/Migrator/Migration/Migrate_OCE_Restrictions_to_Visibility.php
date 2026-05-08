<?php
/**
 * Migration: offcanvas_element restrictions → visibility_settings
 *
 * Reads the old `offcanvas_element_restrictions` post meta (UF repeater of
 * Restriction objects) and writes the equivalent data into the `oce-element`
 * component's `visibility_settings` key inside `page_content_datastore`, which
 * is the format consumed by Template_Engine::is_restricted() / Conditional_Rendering.
 *
 * Schema mapping (old restriction __type → new visibility rule __type):
 *  - page   → page   (no field changes)
 *  - device → device (no field changes)
 *  - plugin → plugin (no field changes)
 *  - user   → user   + restriction_type: 'role' added
 *
 * After a successful write the old `offcanvas_element_restrictions` meta key is
 * removed when $delete_old_data is true.
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_OCE_Restrictions_to_Visibility extends Migrate_Components_Settings_v3 {

    private static $instance = null;

    /**
     * Holds the post ID being processed so migrate_component() can read post meta.
     * @var int|null
     */
    private $current_post_id = null;

    public static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        parent::__construct(
            /* batch_size    */ 10,
            /* do_the_update */ true,
            /* title         */ 'Migrate OCE Restrictions → Visibility Settings',
            /* slug          */ 'migrate_oce_restrictions_to_visibility',
            /* is_top_level  */ false,
            /* meta_keys     */ array( 'page_content' ),
            /* delete_old_data */ true
        );
    }

    /**
     * Target only oce-element components in the GJS tree.
     */
    protected function get_target_component_types() {
        return array( 'oce-element' );
    }

    /**
     * Overrides the batch processor to:
     * 1. Query only `offcanvas_element` posts.
     * 2. Expose the current post ID via $this->current_post_id for migrate_component().
     */
    public function process_page_data_batch( $batch_size, $offset ) {
        global $wpdb;

        // Only process offcanvas_element posts that still have the old restrictions meta.
        $pages = $wpdb->get_results( $wpdb->prepare(
            "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
             FROM {$wpdb->postmeta} pm
             JOIN {$wpdb->posts} p ON pm.post_id = p.ID
             WHERE pm.meta_key = 'page_content'
               AND p.post_type = 'offcanvas_element'
               AND p.post_status != 'trash'
               AND EXISTS (
                   SELECT 1 FROM {$wpdb->postmeta} pm2
                   WHERE pm2.post_id = pm.post_id
                     AND pm2.meta_key = 'offcanvas_element_restrictions'
               )
             LIMIT %d OFFSET %d",
            $batch_size,
            $offset
        ) );

        $general_control = array();
        $do_the_update   = $this->do_the_update;

        foreach ( $pages as $page ) {
            $old_data = maybe_unserialize( $page->meta_value );

            $page_control = array(
                'title'    => get_the_title( $page->post_id ),
                'id'       => $page->post_id,
                'posttype' => $page->post_type,
                'meta'     => $page->meta_key,
                'old_data' => $old_data,
            );

            error_log( 'Migrate_OCE_Restrictions_to_Visibility: processing post ID ' . $page->post_id . ' – ' . get_the_title( $page->post_id ) );

            // Expose post_id so migrate_component() can read post meta.
            $this->current_post_id = (int) $page->post_id;

            $old_datastore = get_post_meta( $page->post_id, 'page_content_datastore', true );
            $new_data      = $this->migrate_page_content_data( $old_data, $old_datastore );

            if ( $do_the_update ) {
                update_post_meta( $page->post_id, 'page_content',           $new_data['page_content'] );
                update_post_meta( $page->post_id, 'page_content_datastore', $new_data['page_content_datastore'] );
                $this->after_page_migration( $page->post_id );
            }

            $page_control['new_data'] = $new_data;
            $general_control[]        = $page_control;
        }

        $this->current_post_id = null;

        return array(
            'quantity' => count( $pages ),
            'control'  => $general_control,
        );
    }

    /**
     * Migrates a single oce-element component node.
     *
     * Reads `offcanvas_element_restrictions` from post meta, converts each
     * restriction into the equivalent Visibility_Rule data shape, and writes
     * `visibility_settings` directly into the component's datastore entry.
     */
    protected function migrate_component( &$component, &$datastore ) {
        $cmp_id = $component['__id'] ?? null;
        if ( ! $cmp_id || ! $this->current_post_id ) {
            return;
        }

        // Skip if this datastore entry already has visibility_settings written.
        if ( isset( $datastore[ $cmp_id ]['visibility_settings'] ) ) {
            return;
        }

        $restrictions = get_post_meta( $this->current_post_id, 'offcanvas_element_restrictions', true );
        if ( ! is_array( $restrictions ) || empty( $restrictions ) ) {
            return;
        }

        $mapped_rules = array();
        foreach ( $restrictions as $restriction ) {
            $mapped_rules[] = $this->map_restriction_to_rule( $restriction );
        }

        // Filter out any nulls from unrecognised restriction types.
        $mapped_rules = array_values( array_filter( $mapped_rules ) );

        if ( empty( $mapped_rules ) ) {
            return;
        }

        if ( ! isset( $datastore[ $cmp_id ] ) ) {
            $datastore[ $cmp_id ] = array();
        }

        // Write at top-level of the datastore entry so merge_component_datastore()
        // places it at $component['visibility_settings'] — which is where
        // Template_Engine::is_restricted() reads it.
        $datastore[ $cmp_id ]['visibility_settings'] = array(
            'rule_operator' => 'all',
            'rules'         => $mapped_rules,
        );
    }

    /**
     * Converts a single old Restriction array to the equivalent Visibility_Rule array.
     *
     * @param  array      $restriction
     * @return array|null Null for unrecognised types.
     */
    private function map_restriction_to_rule( $restriction ) {
        $type = $restriction['__type'] ?? '';

        switch ( $type ) {
            case 'page':
            case 'device':
            case 'plugin':
                // Field schemas are identical — direct copy.
                return $restriction;

            case 'user':
                // Old User restriction only stored `roles` (no restriction_type field).
                // New User rule requires restriction_type = 'role'.
                $rule = $restriction;
                if ( ! isset( $rule['restriction_type'] ) ) {
                    $rule['restriction_type'] = 'role';
                }
                return $rule;

            default:
                return null;
        }
    }

    /**
     * After the page has been saved, remove the old restrictions meta key.
     */
    protected function after_page_migration( $post_id ) {
        if ( $this->delete_old_data ) {
            delete_post_meta( $post_id, 'offcanvas_element_restrictions' );
        }
    }
}
