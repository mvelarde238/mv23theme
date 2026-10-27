<?php
namespace Core\Migrator\Migration\V4;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_4_2_X_to_4_3_0 extends Migrate_Components_Settings_v3 {
    private static $instance = null;

    private $current_post_type = '';
    private $moved_style_components = array();
    private $should_filter_moved_styles = false;

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
        $title           = 'Migrate 4.2.X to 4.3.0 (Editable Areas Implementation)';
        $slug            = 'migrate_4_2_x_to_4_3_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, array( 'page_content' ), $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'button', 'components-wrapper' );
    }

    protected function migrate_component( &$component, &$datastore ) {
        if ( ( $component['type'] ?? '' ) === 'button' ) {
            $component_id = $component['__id'] ?? null;
            if ( $component_id && isset( $datastore[ $component_id ] ) && is_array( $datastore[ $component_id ] ) ) {
                if ( ( $datastore[ $component_id ]['url_type'] ?? '' ) === 'interna' ) {
                    $datastore[ $component_id ]['url_type'] = 'internal';
                } elseif ( ( $datastore[ $component_id ]['url_type'] ?? '' ) === 'externa' ) {
                    $datastore[ $component_id ]['url_type'] = 'external';
                }
            }
        }

        if ( ( $component['type'] ?? '' ) === 'components-wrapper' && isset( $component['classes'] ) ) {
            $classes = is_array( $component['classes'] )
                ? $component['classes']
                : preg_split( '/\s+/', trim( (string) $component['classes'] ) );

            $component['classes'] = array_values( array_filter( $classes, function( $class ) {
                return $class !== 'component';
            } ) );
        }
    }

    public function process_page_data_batch( $batch_size, $offset ) {
        global $wpdb;

        $query = "SELECT pm.post_id, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = %s
            AND p.post_type != 'revision'
            LIMIT %d OFFSET %d";

        $pages = $wpdb->get_results(
            $wpdb->prepare( $query, array( 'page_content', $batch_size, $offset ) )
        );

        $control = array();

        foreach ( $pages as $page ) {
            $this->current_post_type = $page->post_type;
            $old_data = maybe_unserialize( $page->meta_value );
            $old_datastore = get_post_meta( $page->post_id, 'page_content_datastore', true );

            error_log( 'Migrating page: ' . get_the_title( $page->post_id ) . ' (ID: ' . $page->post_id . ') - Meta Key: page_content' );

            $new_data = $this->migrate_page_content_data( $old_data, $old_datastore );

            if ( $this->do_the_update ) {
                update_post_meta( $page->post_id, 'page_content', $new_data['page_content'] );
                update_post_meta( $page->post_id, 'page_content_datastore', $new_data['page_content_datastore'] );
                $this->after_page_migration( $page->post_id );
            }

            $control[] = array(
                'title'    => get_the_title( $page->post_id ),
                'id'       => $page->post_id,
                'posttype' => $page->post_type,
                'meta'     => 'page_content',
                'old_data' => array(
                    'page_content'           => $old_data,
                    'page_content_datastore' => $old_datastore,
                ),
                'new_data' => $new_data,
            );
        }

        $this->current_post_type = '';

        return array(
            'quantity' => count( $pages ),
            'control'  => $control,
        );
    }

    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        $this->moved_style_components = array();
        $this->should_filter_moved_styles = false;

        $content_structure = is_array( $old_data ) ? $old_data : maybe_unserialize( $old_data );
        $datastore = is_array( $old_datastore ) ? $old_datastore : maybe_unserialize( $old_datastore );

        if ( ! is_array( $content_structure ) ) {
            return array(
                'page_content'           => $old_data,
                'page_content_datastore' => $old_datastore,
            );
        }

        if ( ! is_array( $datastore ) ) {
            $datastore = array();
        }

        if ( isset( $content_structure['pages'] ) && is_array( $content_structure['pages'] ) ) {
            foreach ( $content_structure['pages'] as &$page ) {
                if ( ! isset( $page['frames'] ) || ! is_array( $page['frames'] ) ) {
                    continue;
                }

                foreach ( $page['frames'] as &$frame ) {
                    if ( ! isset( $frame['component'] ) || ! is_array( $frame['component'] ) ) {
                        continue;
                    }

                    $this->process_component_tree( $frame['component'], $datastore );
                }
                unset( $frame );
            }
            unset( $page );
        }

        if ( $this->should_filter_moved_styles ) {
            $this->retain_moved_component_styles( $content_structure, $this->moved_style_components );
        }

        return array(
            'page_content'           => $content_structure,
            'page_content_datastore' => $datastore,
        );
    }

    private function process_component_tree( &$component, &$datastore ) {
        if ( ! is_array( $component ) ) {
            return;
        }

        $this->migrate_component( $component, $datastore );

        if ( ( $component['type'] ?? '' ) === 'page-content' ) {
            if ( $this->current_post_type === 'single_template' ) {
                $has_main_slot = $this->tree_has_main_slot( $component, $datastore );
                if ( ! $has_main_slot ) {
                    $this->insert_slot_after_first_post_content( $component, $datastore );
                }
            } elseif ( $this->is_public_content_post_type( $this->current_post_type ) ) {
                $this->move_page_content_to_editable_area( $component, $datastore );
            }
        }

        if ( isset( $component['components'] ) && is_array( $component['components'] ) ) {
            foreach ( $component['components'] as &$child ) {
                $this->process_component_tree( $child, $datastore );
            }
            unset( $child );
        }
    }

    private function is_public_content_post_type( $post_type ) {
        if ( ! $post_type || $post_type === 'page' || $post_type === 'single_template' ) {
            return false;
        }

        $post_type_object = get_post_type_object( $post_type );
        return $post_type_object && ! empty( $post_type_object->public );
    }

    private function move_page_content_to_editable_area( &$page_content, &$datastore ) {
        if ( ! isset( $page_content['components'] ) || ! is_array( $page_content['components'] ) ) {
            return;
        }

        if ( $this->tree_has_editable_area( $page_content, 'main-slot' ) ) {
            return;
        }

        $moved_components = array();
        $discarded_ids = array();
        $this->unwrap_legacy_layout( $page_content['components'], $moved_components, $discarded_ids );

        if ( empty( $moved_components ) ) {
            return;
        }

        $this->moved_style_components = array_merge( $this->moved_style_components, $moved_components );
        $this->should_filter_moved_styles = true;

        foreach ( $discarded_ids as $component_id ) {
            unset( $datastore[ $component_id ] );
        }

        $page_content['components'] = array(
            array(
                'type'       => 'editable-area',
                'classes'    => array( 'editable-area', 'components-wrapper' ),
                'slotId'     => 'main-slot',
                'components' => $moved_components,
            ),
        );
    }

    private function unwrap_legacy_layout( $components, &$moved_components, &$discarded_ids ) {
        foreach ( $components as $component ) {
            if ( ! is_array( $component ) ) {
                $moved_components[] = $component;
                continue;
            }

            $type = $component['type'] ?? '';
            if ( $type === 'section' ) {
                $this->remember_discarded_component( $component, $discarded_ids );
                foreach ( $component['components'] ?? array() as $section_child ) {
                    if ( is_array( $section_child ) && ( $section_child['type'] ?? '' ) === 'container' ) {
                        $this->remember_discarded_component( $section_child, $discarded_ids );
                        foreach ( $section_child['components'] ?? array() as $container_child ) {
                            $moved_components[] = $container_child;
                        }
                    } else {
                        $moved_components[] = $section_child;
                    }
                }
            } elseif ( $type === 'container' ) {
                $this->remember_discarded_component( $component, $discarded_ids );
                foreach ( $component['components'] ?? array() as $container_child ) {
                    $moved_components[] = $container_child;
                }
            } else {
                $moved_components[] = $component;
            }
        }
    }

    private function remember_discarded_component( $component, &$discarded_ids ) {
        if ( ! empty( $component['__id'] ) ) {
            $discarded_ids[] = $component['__id'];
        }
    }

    private function retain_moved_component_styles( &$page_content, $components ) {
        if ( ! isset( $page_content['styles'] ) || ! is_array( $page_content['styles'] ) ) {
            return;
        }

        $ids = array();
        $classes = array();
        foreach ( $components as $component ) {
            $this->collect_style_targets( $component, $ids, $classes );
        }

        $page_content['styles'] = array_values( array_filter( $page_content['styles'], function( $rule ) use ( $ids, $classes ) {
            if ( ! is_array( $rule ) || ! isset( $rule['selectors'] ) ) {
                return false;
            }

            $selectors = is_array( $rule['selectors'] ) ? $rule['selectors'] : array( $rule['selectors'] );
            foreach ( $selectors as $selector ) {
                if ( ! is_string( $selector ) ) {
                    continue;
                }

                foreach ( $ids as $id ) {
                    $id_selector = '/(?<![A-Za-z0-9_-])#' . preg_quote( $id, '/' ) . '(?![A-Za-z0-9_-])/';
                    if ( preg_match( $id_selector, $selector ) ) {
                        return true;
                    }
                }

                foreach ( $classes as $class ) {
                    $class_selector = '/(?<![A-Za-z0-9_-])\\.' . preg_quote( $class, '/' ) . '(?![A-Za-z0-9_-])/';
                    if ( preg_match( $class_selector, $selector ) ) {
                        return true;
                    }
                }
            }

            return false;
        } ) );
    }

    private function collect_style_targets( $component, &$ids, &$classes ) {
        if ( ! is_array( $component ) ) {
            return;
        }

        $id = $component['attributes']['id'] ?? '';
        if ( $id !== '' ) {
            $ids[] = $id;
        }

        $component_classes = $component['classes'] ?? array();
        if ( ! is_array( $component_classes ) ) {
            $component_classes = preg_split( '/\s+/', trim( (string) $component_classes ) );
        }

        foreach ( $component_classes as $class ) {
            if ( is_string( $class ) && $class !== '' ) {
                $classes[] = $class;
            }
        }

        foreach ( $component['components'] ?? array() as $child ) {
            $this->collect_style_targets( $child, $ids, $classes );
        }
    }

    private function tree_has_editable_area( $component, $slot_id ) {
        if ( ! is_array( $component ) ) {
            return false;
        }

        if ( ( $component['type'] ?? '' ) === 'editable-area' && ( $component['slotId'] ?? '' ) === $slot_id ) {
            return true;
        }

        foreach ( $component['components'] ?? array() as $child ) {
            if ( $this->tree_has_editable_area( $child, $slot_id ) ) {
                return true;
            }
        }

        return false;
    }

    private function tree_has_main_slot( $component, $datastore ) {
        if ( ! is_array( $component ) ) {
            return false;
        }

        if ( ( $component['type'] ?? '' ) === 'editable-area-slot' ) {
            $component_id = $component['__id'] ?? '';
            $slot_id = $datastore[ $component_id ]['slot_id'] ?? ( $component['slot_id'] ?? '' );
            if ( $slot_id === 'main-slot' ) {
                return true;
            }
        }

        foreach ( $component['components'] ?? array() as $child ) {
            if ( $this->tree_has_main_slot( $child, $datastore ) ) {
                return true;
            }
        }

        return false;
    }

    private function insert_slot_after_first_post_content( &$component, &$datastore ) {
        if ( ! isset( $component['components'] ) || ! is_array( $component['components'] ) ) {
            return false;
        }

        foreach ( $component['components'] as $index => &$child ) {
            if ( is_array( $child ) && ( $child['type'] ?? '' ) === 'post-content' ) {
                $slot_id = $this->generate_cmp_id();
                array_splice( $component['components'], $index + 1, 0, array(
                    array(
                        'type'    => 'editable-area-slot',
                        'classes' => array( 'editable-area-slot' ),
                        '__id'    => $slot_id,
                    ),
                ) );
                $datastore[ $slot_id ] = array(
                    'slot_id' => 'main-slot',
                    '__type'  => 'editable-area-slot',
                );
                unset( $child );
                return true;
            }

            if ( is_array( $child ) && $this->insert_slot_after_first_post_content( $child, $datastore ) ) {
                unset( $child );
                return true;
            }
        }
        unset( $child );

        return false;
    }
}
