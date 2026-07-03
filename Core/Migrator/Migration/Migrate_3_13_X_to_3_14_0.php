<?php
/**
 * Migration class for migrating from version 3.13.X to 3.14.0
 * Extracts filter settings from the listing component and creates a new listing filter component with those settings
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_13_X_to_3_14_0 extends Migrate_Components_Settings_v3 {
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
        $title           = 'Migrate 3.13.X to 3.14.0 ( Listing Filter Component )';
        $slug            = 'migrate_3_13_x_to_3_14_0';
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
     * Overrides parent to send the parent and child component for processing.
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
                            $this->process_components_tree( $frame['component'], $frame['component']['components'], $datastore );
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
    private function process_components_tree( &$parent, &$components, &$datastore ) {
        // $target_types = $this->get_target_component_types();

        foreach ( $components as &$component ) {
            $type = isset($component['type']) ? $component['type'] : '';

            // if ( in_array( $type, $target_types, true ) ) {
                $this->custom_migrate_component( $parent, $component, $datastore );
            // }

            // Always recurse into children
            if ( isset($component['components']) && is_array($component['components']) ) {
                $this->process_components_tree( $component, $component['components'], $datastore );
            }
        }
        unset($component);
    }

    /**
     * Migrates a single component. Receives both the parent and the child component.
     */
    protected function custom_migrate_component( &$parent, &$component, &$datastore ) {
        if( $component['type'] === 'listing' && !isset($component['____migrated_to_v_3_14_0']) ) {
            $listing_id = isset( $component['__id'] ) ? $component['__id'] : null;
            if ( ! $listing_id || ! isset( $datastore[ $listing_id ] ) ) {
                return;
            }

            $old_listing_data = $datastore[ $listing_id ];

            $show_filter = isset( $old_listing_data['show_filter'] ) ? $old_listing_data['show_filter'] : false;
            if( $show_filter ) {
                

                // generate a unique listing_uid for the new listing filter component
                $listing_uid = uniqid('listing_');
                $datastore[$listing_id]['listing_uid'] = $listing_uid;

                $posttype = isset( $old_listing_data['posttype'] ) ? $old_listing_data['posttype'] : 'post';

                $filters = isset( $old_listing_data['filters'] ) ? $old_listing_data['filters'] : array();
                // old filters structure is like this:
                // {
                //     "category": {
                //         "show": true,
                //         "initial_value": "0"
                //     },
                //     "portfolio-cat": {
                //         "show": false,
                //         "initial_value": ""
                //     },
                //     "document-cat": {
                //         "show": false,
                //         "initial_value": ""
                //     },
                //     "month": {
                //         "show": true
                //     },
                //     "year": {
                //         "show": true,
                //         "initial_value": 0,
                //         "first_year": 2012
                //     }
                // }

                // new filters structure is like this:
                // [
                    // {
                        // "label": "SEARCH POST:",
                        // "__hidden": true,
                        // "__type": "search"
                    // },
                    // {
                        // "initial_value": "12",
                        // "display_type": "select",
                        // "__hidden": true,
                        // "__type": "taxfilter__post__category"
                    // },
                    // {
                        // "initial_value": "0",
                        // "display_type": "select",
                        // "__hidden": true,
                        // "__type": "taxfilter__document__document-cat"
                    // },
                    // {
                        // "__hidden": false,
                        // "__type": "month"
                    // },
                    // {
                        // "initial_value": 0,
                        // "first_year": 2012,
                        // "__hidden": true,
                        // "__type": "year"
                    // },
                    // {
                        // "text": "FILTER",
                        // "__hidden": true,
                        // "__type": "submit"
                    // },
                    // {
                        // "__hidden": false,
                        // "__type": "break"
                    // },
                    // {
                        // "initial_value": "0",
                        // "display_type": "radio",
                        // "__hidden": true,
                        // "__type": "taxfilter__post__post_tag"
                    // }
                // ]

                // Build the new filters array based on the old filters data
                $filters = array(
                    [
                        "label" => "SEARCH:",
                        "__hidden" => true,
                        "__type" => "search"
                    ]
                );

                // Loop through the old filters and convert them to the new format
                foreach( $old_listing_data['filters'] as $key => $filter_data ) {
                    if( isset($filter_data['show']) && $filter_data['show'] ) {
                        if( $key === 'month' ){
                            $filters[] = array(
                                "__hidden" => true,
                                "__type" => "month"
                            );
                        } elseif ($key === 'year') {
                            $filters[] = array(
                                "__hidden" => true,
                                "__type" => "year",
                                'first_year' => isset($filter_data['first_year']) ? $filter_data['first_year'] : 2012,
                                'initial_value' => isset($filter_data['initial_value']) ? $filter_data['initial_value'] : 0
                            );
                        } else {
                            $tax_slug = $key;
                            $initial_value = isset($filter_data['initial_value']) ? $filter_data['initial_value'] : '';
                            $display_type = isset($filter_data['display_type']) ? $filter_data['display_type'] : 'select';
                            $filters[] = array(
                                "initial_value" => $initial_value,
                                "display_type" => $display_type,
                                "__hidden" => true,
                                "__type" => "taxfilter__{$posttype}__{$tax_slug}"
                            );
                        }
                    }
                }

                // Add the submit button as last item to the filters array
                $filters[] = array(
                    "text" => "FILTER",
                    "__hidden" => true,
                    "__type" => "submit"
                );

                // create the new listing filter component and add it to the datastore
                $listing_filter_id = $this->generate_cmp_id();

                $listing_filter_component = array(
                    'type' => 'listing-filter',
                    'classes'    => array( 'listing-filter', 'component' ),
                    '__id' => $listing_filter_id
                );
                $listing_filter_datastore = array(
                    '__type' => 'listing-filter',
                    'listing_uid' => $listing_uid,
                    'template' => 'horizontal',
                    'posttype' => $posttype,
                    'filters' => $filters,
                );
                $datastore[$listing_filter_id] = $listing_filter_datastore;

                // insert the new listing filter component before the listing component in the parent's components array
                $listing_index = array_search( $component, $parent['components'], true );
                if ( $listing_index !== false ) {
                    // error_log('Inserting listing filter component at index: ' . $listing_index);
                    array_splice( $parent['components'], $listing_index, 0, array( $listing_filter_component ) );
                }
                
                // remove legacy entries from listing datastore
                unset( $datastore[ $listing_id ]['filters'] );
                unset( $datastore[ $listing_id ]['show_filter'] );

                // mark the listing component as migrated to avoid infinite loops
                $component['____migrated_to_v_3_14_0'] = true;
            }
        }
    }
}
