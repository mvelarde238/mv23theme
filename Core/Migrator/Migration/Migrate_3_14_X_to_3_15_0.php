<?php
namespace Core\Migrator\Migration;

use Core\Builder\Slider_Settings; // pendiente, no usar esta clase aquí por que es un migrador!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_14_X_to_3_15_0 extends Migrate_Components_Settings_v3 {
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
        $title           = 'Migrate 3.14.X to 3.15.0 ( Slider Settings Repeater )';
        $slug            = 'migrate_3_14_x_to_3_15_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'listing', 'carousel-wrapper', 'gallery' );
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

        if ( $component['type'] === 'gallery' ) {
            $datastore[ $component_id ] = $this->migrate_gallery_datastore( $datastore[ $component_id ] );
            return;
        }

        if ( $component['type'] === 'carousel-wrapper' ) {
            $datastore[ $component_id ] = $this->migrate_carousel_datastore( $datastore[ $component_id ] );
        }
    }

    private function migrate_listing_datastore( $entry ) {
        $settings = [];

        if ( isset( $entry['carousel_settings'] ) ) {
            $settings = $this->extract_legacy_listing_settings( $entry['carousel_settings'] );
        }
        
        // add mouse drag / rewind to all carousels, as it was previously enabled by default
        $settings['mouse_drag'] = true;
        $settings['rewind'] = true;

        $entry['slider_settings'] = $this->build_repeater_settings( $settings );

        unset( $entry['carousel_settings']);

        return $entry;
    }

    private function migrate_gallery_datastore( $entry ) {
        $settings = [];
        
        $settings['slider_theme'] = $entry['carousel_theme'] ?? 'theme1';

        // add carousel settings defaults for galleries, as they were previously enabled by default
        $settings['controls'] = true;
        $settings['controls_position'] = 'center';
        $settings['nav'] = true;
        $settings['nav_position'] = 'bottom';
        $settings['touch'] = true;
        $settings['slider_uid'] = uniqid('slider_');

        // add mouse drag / rewind to all carousels, as it was previously enabled by default
        $settings['mouse_drag'] = true;
        $settings['rewind'] = true;

        $entry['slider_settings'] = $this->build_repeater_settings( $settings );

        unset( $entry['carousel_theme']);

        return $entry;
    }

    private function migrate_carousel_datastore( $entry ) {
        $settings = [];

        $settings = $this->extract_legacy_carousel_settings( $entry );
        // add mouse drag / rewind to all carousels, as it was previously enabled by default
        $settings['mouse_drag'] = true;
        $settings['rewind'] = true;
        $settings['slider_uid'] = $entry['slider_uid'] ?? uniqid('slider_');
        $settings['slider_theme'] = $entry['carousel_theme'] ?? 'theme1';

        $entry['slider_settings'] = $this->build_repeater_settings( $settings );

        unset( $entry['controls_settings'] );
        unset( $entry['nav_settings'] );
        unset( $entry['autoplay_settings'] );
        unset( $entry['carousel_mode'] );
        unset( $entry['start_index_settings'] );
        unset( $entry['auto_height'] );
        unset( $entry['touch'] );
        unset( $entry['slider_uid'] );
        unset( $entry['carousel_theme'] );

        return $entry;
    }

    private function extract_legacy_listing_settings( $settings ) {
        $mapped = [];

        if ( array_key_exists( 'show_controls', $settings ) ) {
            $mapped['controls'] = ! empty( $settings['show_controls'] );
        }

        if ( array_key_exists( 'show_nav', $settings ) ) {
            $mapped['nav'] = ! empty( $settings['show_nav'] );
        }

        if ( array_key_exists( 'autoplay', $settings ) ) {
            $mapped['autoplay'] = ! empty( $settings['autoplay'] );
        }

        if ( ! empty( $settings['mode'] ) ) {
            $mapped['mode'] = $settings['mode'];
        }

        if ( ! empty( $settings['carousel_id'] ) ) {
            $mapped['slider_uid'] = $settings['carousel_id'];
        } else {
            $mapped['slider_uid'] = uniqid('slider_');
        }

        return $mapped;
    }

    private function extract_legacy_carousel_settings( $entry ) {
        $mapped = [];

        if ( isset( $entry['controls_settings'] ) && is_array( $entry['controls_settings'] ) ) {
            if ( array_key_exists( 'show', $entry['controls_settings'] ) ) {
                $mapped['controls'] = ! empty( $entry['controls_settings']['show'] );
            }
            if ( ! empty( $entry['controls_settings']['position'] ) ) {
                $mapped['controls_position'] = $entry['controls_settings']['position'];
            }
        }

        if ( isset( $entry['nav_settings'] ) && is_array( $entry['nav_settings'] ) ) {
            if ( array_key_exists( 'show', $entry['nav_settings'] ) ) {
                $mapped['nav'] = ! empty( $entry['nav_settings']['show'] );
            }
            if ( ! empty( $entry['nav_settings']['position'] ) ) {
                $mapped['nav_position'] = $entry['nav_settings']['position'];
            }
        }

        if ( isset( $entry['autoplay_settings'] ) && is_array( $entry['autoplay_settings'] ) ) {
            if ( array_key_exists( 'active', $entry['autoplay_settings'] ) ) {
                $mapped['autoplay'] = ! empty( $entry['autoplay_settings']['active'] );
            }
            if ( isset( $entry['autoplay_settings']['timeout'] ) && $entry['autoplay_settings']['timeout'] !== '' ) {
                $mapped['autoplay_timeout'] = $entry['autoplay_settings']['timeout'];
            }
        }

        if ( isset( $entry['carousel_mode'] ) && is_array( $entry['carousel_mode'] ) ) {
            if ( ! empty( $entry['carousel_mode']['mode'] ) ) {
                $mapped['mode'] = $entry['carousel_mode']['mode'];
            }
            if ( ! empty( $entry['carousel_mode']['axis'] ) ) {
                $mapped['axis'] = $entry['carousel_mode']['axis'];
            }
            if ( isset( $entry['carousel_mode']['speed'] ) && $entry['carousel_mode']['speed'] !== '' ) {
                $mapped['speed'] = $entry['carousel_mode']['speed'];
            }
        }

        if ( array_key_exists( 'auto_height', $entry ) ) {
            $mapped['auto_height'] = ! empty( $entry['auto_height'] );
        }

        if ( array_key_exists( 'touch', $entry ) ) {
            $mapped['touch'] = ! empty( $entry['touch'] );
        }

        if ( isset( $entry['start_index_settings'] ) && is_array( $entry['start_index_settings'] ) && ! empty( $entry['start_index_settings']['active'] ) ) {
            $mapped['start_index'] = (string) ( $entry['start_index_settings']['index'] ?? '0' );
        }

        return $mapped;
    }

    private function build_repeater_settings( $settings ) {
        if ( ! is_array( $settings ) ) {
            return array();
        }

        $repeater = array();
        foreach ( Slider_Settings::getOptions() as $option ) {
            $key = $option['key'];
            if ( ! array_key_exists( $key, $settings ) ) {
                continue;
            }

            $repeater[] = array(
                '__type' => $key,
                'property' => $key,
                'value' => $settings[ $key ],
            );
        }

        return $repeater;
    }
}