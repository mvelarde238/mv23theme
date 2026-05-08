<?php
/**
 * Migration class for migrating from version 3.10.X to 3.11.0
 * Migrates settings in the GJS datastore to the new field schema:
 *   - visibility -> visibility_settings
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_10_X_to_3_11_0 extends Migrate_Components_Settings_v3 {
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
        $title           = 'Migrate 3.10.X to 3.11.0 ( Migrate Common Settings )';
        $slug            = 'migrate_3_10_x_to_3_11_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    /**
     * No specific component type targeting — we walk the full datastore instead.
     */
    protected function get_target_component_types() {
        return array();
    }

    /**
     * No-op: required by the abstract base class.
     * All work is done in migrate_page_content_data() via the datastore loop.
     */
    protected function migrate_component( &$component, &$datastore ) {}

    /**
     * Overrides parent to iterate every datastore entry and migrate
     * settings wherever it exists.
     */
    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        $result    = parent::migrate_page_content_data( $old_data, $old_datastore );
        $datastore = $result['page_content_datastore'];

        if ( is_array( $datastore ) ) {
            foreach ( $datastore as $cmp_id => &$entry ) {
                if ( isset( $entry['settings'] ) && is_array( $entry['settings'] ) ) {
                    $entry['settings'] = $this->migrate_common_settings( $entry['settings'] );
                }
            }
            unset( $entry );
        }

        $result['page_content_datastore'] = $datastore;

        return $result;
    }

    /**
     * Move settings.visibility to visibility_settings with the new rule schema.
     */
    private function migrate_common_settings( $settings ) {
        if ( isset( $settings['visibility'] ) && isset( $settings['visibility']['use'] ) && $settings['visibility']['use'] ) {
            $visibility = isset( $settings['visibility']['key'] ) ? $settings['visibility']['key'] : '';
            if ( $visibility && $visibility !== 'all' ) {
                $user_rule = array(
                    '__type' => 'user'
                );

                if ( $visibility === 'is_private' ) {
                    $user_rule['restriction_type'] = 'role';
                    $user_rule['roles'] = array( 'administrator' );
                } elseif ( $visibility === 'user_is_logged_in' ) {
                    $user_rule['restriction_type'] = 'status';
                    $user_rule['status'] = 'logged_in';
                } elseif ( $visibility === 'user_is_not_logged_in' ) {
                    $user_rule['restriction_type'] = 'status';
                    $user_rule['status'] = 'logged_out';
                }

                $settings['visibility_settings'] = array(
                    'rule_operator' => 'all',
                    'rules' => array( $user_rule ),
                );
            }
        }
        unset( $settings['visibility'] );

        // if dark-mode is enabled on helpers['list'] move it to settings['color_scheme']['key'] to keep all color scheme related settings together
        if ( isset( $settings['helpers']['list'] ) && is_array( $settings['helpers']['list'] ) && in_array( 'dark-mode', $settings['helpers']['list'] ) ) {
            $settings['color_scheme']['key'] = 'dark_scheme';
            // remove dark-mode from helpers
            $settings['helpers']['list'] = array_diff( $settings['helpers']['list'], array( 'dark-mode' ) );
        }

        // change settings['helpers'] = ['use'=>bool, 'list'=>array] to settings['utility_classes'] = array
        $utility_classes = array();
        if ( isset( $settings['helpers']['use'] ) && $settings['helpers']['use'] && isset( $settings['helpers']['list'] ) && is_array( $settings['helpers']['list'] ) ) {
            $utility_classes = $settings['helpers']['list'];
        }

        $settings['utility_classes'] = $utility_classes;
        unset( $settings['helpers'] );

        return $settings;
    }


    // JUST EXAMPLES:
    /**
     * Iterates animation groups and delegates each group's settings to migrate_group_settings().
     */
    // private function migrate_common_settings( $animations ) {
    //     if ( ! isset( $animations['groups'] ) || ! is_array( $animations['groups'] ) ) {
    //         return $animations;
    //     }

    //     foreach ( $animations['groups'] as &$group ) {
    //         if ( isset( $group['settings'] ) && is_array( $group['settings'] ) ) {
    //             $group['settings'] = $this->migrate_group_settings( $group['settings'] );
    //         }
    //     }
    //     unset( $group );

    //     return $animations;
    // }

    /**
     * Applies all field mapping transformations to a single animation group's settings array.
     */
    // private function migrate_group_settings( $settings ) {

    //     // 1. start_at → start
    //     if ( isset( $settings['start_at'] ) && ! isset( $settings['start'] ) ) {
    //         $settings['start'] = $settings['start_at'];
    //     }
    //     unset( $settings['start_at'] );

    //     // 2. end_at → set_end + end (hook / custom_hook)
    //     $end_at    = isset( $settings['end_at'] ) ? $settings['end_at'] : array( 'basic' => '', 'customize' => false, 'custom' => '' );
    //     $end_value = '';
    //     if ( ! empty( $end_at['basic'] ) ) {
    //         $end_value = $end_at['basic'];
    //     } elseif ( ! empty( $end_at['customize'] ) && ! empty( $end_at['custom'] ) ) {
    //         $end_value = $end_at['custom'];
    //     }

    //     if ( $end_value ) {
    //         $settings['set_end'] = true;
    //         $settings['end']     = array( 'hook' => 'custom', 'custom_hook' => $end_value );
    //     } else {
    //         $settings['set_end'] = false;
    //         $settings['end']     = array( 'hook' => 'bottom top', 'custom_hook' => '' );
    //     }
    //     unset( $settings['end_at'] );

    //     // 2b. scrub — old system had no scrub field; it was inferred from end being set.
    //     //     Preserve that behaviour: end_value set → scrub linked to scroll progress.
    //     if ( ! isset( $settings['scrub'] ) ) {
    //         $settings['scrub'] = array(
    //             'scrub_value'  => $end_value ? 'true' : '',
    //             'custom_value' => ''
    //         );
    //     }

    //     // 3. toggle actions 
    //     $settings['set_toggle_actions'] = true;
    //     $settings['toggle_actions'] = $settings['toggle_actions'] ?? 'play none none reverse';
            
    //     // 4. set_advanced_settings + toggle_class → set_toggle_class
    //     $has_advanced = $settings['set_advanced_settings'] ?? false;
    //     $has_toggle_class_data = $has_advanced && (
    //         ! empty( $settings['toggle_class']['classname'] ?? '' ) ||
    //         ! empty( $settings['toggle_class']['selector'] ?? '' )
    //     );
    //     $settings['set_toggle_class'] = $has_toggle_class_data ? true : false;

    //     unset( $settings['set_advanced_settings'] );

    //     // 5. add_indicators → show_markers
    //     $settings['show_markers'] = isset( $settings['add_indicators'] ) ? $settings['add_indicators'] : false;
    //     unset( $settings['add_indicators'] );

    //     // 6. disable_on_mobile / disable_everywhere → disable_settings + disable_on
    //     $disable_on_mobile  = ! empty( $settings['disable_on_mobile'] );
    //     $disable_everywhere = ! empty( $settings['disable_everywhere'] );

    //     if ( $disable_everywhere ) {
    //         $settings['disable_settings'] = true;
    //         $settings['disable_on']       = array( 'mobile' => true, 'desktop' => true );
    //     } elseif ( $disable_on_mobile ) {
    //         $settings['disable_settings'] = true;
    //         $settings['disable_on']       = array( 'mobile' => true, 'desktop' => false );
    //     } else {
    //         $settings['disable_settings'] = false;
    //         $settings['disable_on']       = array( 'mobile' => false, 'desktop' => false );
    //     }
    //     unset( $settings['disable_on_mobile'] );
    //     unset( $settings['disable_everywhere'] );

    //     return $settings;
    // }
}
