<?php
namespace Core\Migrator\Migration\V4;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_4_1_X_to_4_2_0 extends Migrate_Components_Settings_v3 {
    private static $instance = null;

    private $vertical_filter_ids = array();

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
        $title           = 'Migrate 4.1.X to 4.2.0 (Listing Filter Components Implementation)';
        $slug            = 'migrate_4_1_x_to_4_2_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'listing-filter' );
    }

    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        $this->vertical_filter_ids = array();

        $result = parent::migrate_page_content_data( $old_data, $old_datastore );

        if ( ! empty( $this->vertical_filter_ids ) && isset( $result['page_content'] ) && is_array( $result['page_content'] ) ) {
            if ( ! isset( $result['page_content']['styles'] ) || ! is_array( $result['page_content']['styles'] ) ) {
                $result['page_content']['styles'] = array();
            }

            $unique_ids = array_unique( $this->vertical_filter_ids );

            foreach ( $unique_ids as $elem_id ) {
                $selector = '#' . ltrim( $elem_id, '#' );
                $found = false;

                foreach ( $result['page_content']['styles'] as &$style_rule ) {
                    if ( isset( $style_rule['selectors'] ) && is_array( $style_rule['selectors'] ) && in_array( $selector, $style_rule['selectors'], true ) ) {
                        if ( ! isset( $style_rule['style'] ) || ! is_array( $style_rule['style'] ) ) {
                            $style_rule['style'] = array();
                        }
                        $style_rule['style']['flex-direction'] = 'column';
                        $found = true;
                        break;
                    }
                }
                unset( $style_rule );

                if ( ! $found ) {
                    $result['page_content']['styles'][] = array(
                        'selectors' => array( $selector ),
                        'style'     => array(
                            'flex-direction' => 'column',
                        ),
                    );
                }
            }
        }

        return $result;
    }

    protected function migrate_component( &$component, &$datastore ) {
        $component_id = $component['__id'] ?? null;
        if ( ! $component_id || ! isset( $datastore[ $component_id ] ) || ! is_array( $datastore[ $component_id ] ) ) {
            return;
        }

        if ( $component['type'] !== 'listing-filter' ) {
            return;
        }

        $component['__needsSetup'] = false;

        if ( ! isset( $component['attributes'] ) || ! is_array( $component['attributes'] ) ) {
            $component['attributes'] = array();
        }

        // If template was vertical, preserve/generate element id and register for style update
        $template = $datastore[ $component_id ]['template'] ?? '';
        if ( $template === 'vertical' ) {
            if ( empty( $component['attributes']['id'] ) ) {
                $component['attributes']['id'] = $this->generate_elem_id();
            }
            $this->vertical_filter_ids[] = $component['attributes']['id'];
        }

        if ( ! isset( $component['components'] ) || ! is_array( $component['components'] ) ) {
            $component['components'] = array();
        }

        // Convert legacy filters to child components
        if ( isset( $datastore[ $component_id ]['filters'] ) && is_array( $datastore[ $component_id ]['filters'] ) ) {
            foreach ( $datastore[ $component_id ]['filters'] as $filter ) {
                $child_component = $this->convert_filter_to_component( $filter, $datastore );
                if ( $child_component ) {
                    $component['components'][] = $child_component;
                }
            }
        }

        // Clean up legacy datastore keys
        unset( $datastore[ $component_id ]['filters'] );
        unset( $datastore[ $component_id ]['template'] );
        $datastore[ $component_id ]['__type']   = 'listing-filter';
        $datastore[ $component_id ]['__hidden'] = false;
    }

    private function convert_filter_to_component( $filter, &$datastore ) {
        if ( ! is_array( $filter ) ) {
            return null;
        }

        $type = $filter['__type'] ?? '';

        // Omit break filters according to migration specifications
        if ( $type === 'break' ) {
            return null;
        }

        $child_cmp_id  = $this->generate_cmp_id();
        $child_elem_id = $this->generate_elem_id();

        if ( $type === 'search' || $type === 'search-filter' ) {
            $datastore[ $child_cmp_id ] = array(
                'label'      => $filter['label'] ?? '',
                'hide_label' => ! empty( $filter['hide_label'] ),
                '__type'     => 'search-filter',
                '__hidden'   => false,
            );

            return array(
                'type'       => 'search-filter',
                'classes'    => array( 'search-filter', 'field-wrapper' ),
                'attributes' => array( 'id' => $child_elem_id ),
                '__id'       => $child_cmp_id,
            );
        }

        if ( $type === 'month' || $type === 'month-filter' ) {
            $datastore[ $child_cmp_id ] = array(
                'label'      => $filter['label'] ?? '',
                'all_text'   => $filter['all_text'] ?? '',
                'hide_label' => ! empty( $filter['hide_label'] ),
                '__type'     => 'month-filter',
                '__hidden'   => false,
            );

            return array(
                'type'       => 'month-filter',
                'classes'    => array( 'month-filter', 'field-wrapper' ),
                'attributes' => array( 'id' => $child_elem_id ),
                '__id'       => $child_cmp_id,
            );
        }

        if ( $type === 'year' || $type === 'year-filter' ) {
            $datastore[ $child_cmp_id ] = array(
                'label'         => $filter['label'] ?? '',
                'all_text'      => $filter['all_text'] ?? '',
                'hide_label'    => ! empty( $filter['hide_label'] ),
                'initial_value' => isset( $filter['initial_value'] ) ? (int) $filter['initial_value'] : 0,
                'first_year'    => isset( $filter['first_year'] ) ? (int) $filter['first_year'] : 2012,
                '__type'        => 'year-filter',
                '__hidden'      => false,
            );

            return array(
                'type'       => 'year-filter',
                'classes'    => array( 'year-filter', 'field-wrapper' ),
                'attributes' => array( 'id' => $child_elem_id ),
                '__id'       => $child_cmp_id,
            );
        }

        if ( $type === 'submit' || $type === 'submit-filter' ) {
            $label = ! empty( $filter['label'] ) ? $filter['label'] : ( ! empty( $filter['text'] ) ? $filter['text'] : 'FILTER' );
            $show_reset = isset( $filter['show_reset_button'] ) ? (bool) $filter['show_reset_button'] : true;
            $reset_text = ! empty( $filter['reset_text'] ) ? $filter['reset_text'] : 'RESET';

            $datastore[ $child_cmp_id ] = array(
                'label'             => $label,
                'show_reset_button' => $show_reset,
                'reset_text'        => $reset_text,
                '__type'            => 'submit-filter',
                '__hidden'          => false,
            );

            return array(
                'type'       => 'submit-filter',
                'classes'    => array( 'submit-filter', 'field-wrapper' ),
                'attributes' => array( 'id' => $child_elem_id ),
                '__id'       => $child_cmp_id,
            );
        }

        if ( strpos( $type, 'taxfilter__' ) === 0 || $type === 'taxonomy' || $type === 'taxonomy-filter' ) {
            $taxonomy = '';
            if ( strpos( $type, 'taxfilter__' ) === 0 ) {
                $parts = explode( '__', $type, 3 );
                $taxonomy = isset( $parts[2] ) ? $parts[2] : ( isset( $parts[1] ) ? $parts[1] : '' );
            } else {
                $taxonomy = $filter['taxonomy'] ?? '';
            }

            $initial_val = $filter['initial_value'] ?? '';
            if ( $initial_val === 0 || $initial_val === '0' || empty( $initial_val ) ) {
                $initial_val = '';
            } else {
                $initial_val = (string) $initial_val;
            }

            $datastore[ $child_cmp_id ] = array(
                'label'         => $filter['label'] ?? '',
                'all_text'      => $filter['all_text'] ?? '',
                'hide_label'    => ! empty( $filter['hide_label'] ),
                'taxonomy'      => $taxonomy,
                'initial_value' => $initial_val,
                'display_type'  => $filter['display_type'] ?? 'select',
                'hide_empty'    => ! empty( $filter['hide_empty'] ),
                '__type'        => 'taxonomy-filter',
                '__hidden'      => false,
            );

            return array(
                'type'       => 'taxonomy-filter',
                'classes'    => array( 'taxonomy-filter', 'field-wrapper' ),
                'attributes' => array( 'id' => $child_elem_id ),
                '__id'       => $child_cmp_id,
            );
        }

        if ( $type === 'postmeta' || $type === 'postmeta-filter' ) {
            $datastore[ $child_cmp_id ] = array(
                'label'        => $filter['label'] ?? '',
                'all_text'     => $filter['all_text'] ?? '',
                'hide_label'   => ! empty( $filter['hide_label'] ),
                'meta_key'     => $filter['meta_key'] ?? '',
                'display_type' => $filter['display_type'] ?? 'text',
                'options'      => isset( $filter['options'] ) && is_array( $filter['options'] ) ? $filter['options'] : array(),
                '__type'       => 'postmeta-filter',
                '__hidden'     => false,
            );

            return array(
                'type'       => 'postmeta-filter',
                'classes'    => array( 'postmeta-filter', 'field-wrapper' ),
                'attributes' => array( 'id' => $child_elem_id ),
                '__id'       => $child_cmp_id,
            );
        }

        return null;
    }

    protected function generate_elem_id() {
        return 'i' . substr( md5( uniqid( (string) mt_rand(), true ) ), 0, 4 );
    }
}
