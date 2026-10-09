<?php
namespace Core\Frontend;

use WP_Query;
use Core\Builder\Component\Settings\Query_Parameters;

class Listing_Data_Provider {

    public static function get_data( $args = array() ) {
        $query = array();

        if( $args['data_source'] === 'custom_query' ){
            $query = self::build_custom_query( $args );
        } else {
            global $wp_query;
            $query = $wp_query;
        }

        return $query;
    }

    private static function build_custom_query( $args = array() ) {
        $query = array();

        $listing_source = $args['source'] ?? 'auto'; // auto || manual
        $woocommerce_key = ( WOOCOMMERCE_IS_ACTIVE && isset($args['woocommerce_key']) ) ? $args['woocommerce_key'] : '';
        
        if ($listing_source == 'manual') {
            $posts_ids = array();
            $manual_posts_raw = $args['posts'];
            foreach ($manual_posts_raw as $post) {
                array_push($posts_ids, str_replace('post_','',$post) );
            };
                
            $query_args = array();
            $query_args['post__in'] = $posts_ids;
            $query_args['orderby'] = 'post__in';
            $query_args['post_type'] = 'any';
            $query_args['posts_per_page'] = -1; // ?
        }
        
        if ($listing_source == 'auto') {
            // query parameters
            $query_args = Query_Parameters::from_repeater($args['query_parameters'] ?? array());

            if ( get_query_var('paged') ) $query_args['paged'] = get_query_var('paged');

            // post type
            $posttype = $args['posttype'] ?? '';
            $query_args['post_type'] = $posttype;
        
            // check if tax_query is needed 
            $tax_params = ( isset($args['tax_params']) ) ? $args['tax_params'] : null;
            $pt_taxonomies = get_object_taxonomies( $posttype ); // get taxonomies for selected posttype 
        
            if( is_array($tax_params) ){    
                $tax_query = array( 'relation' => 'AND' );

                foreach ($tax_params as $tax => $terms) {
                    $tax_parts = explode( '--', $tax );
                    $tax_name = $tax_parts[1];
                    // $tax_cpt = $tax_parts[0]; // util, but not used

                    // create tax_query if tax belongs to selected posttype and there are selected terms
                    if( in_array($tax_name,$pt_taxonomies) && is_array($terms) && count($terms) > 0 ){
                        if( !empty($terms[0]) ){            
                            array_push($tax_query, array(
                                'taxonomy' => $tax_name,
                                'field' => 'term_id',
                                'terms' => $terms,
                                'include_children' => true,
                                'operator' => 'IN'
                            ));
                        }
                    }
                }
            
                /* woo featured products */
                if(WOOCOMMERCE_IS_ACTIVE){
                    if($woocommerce_key == 'featured'){
                        array_push($tax_query, array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => array('featured'),
                            'operator' => 'IN'
                        ));
                    }
                }
                /* end woo featured products */
            
                if( count($tax_query) > 1 ){ // add tax query
                    $query_args['tax_query'] = $tax_query;
                }
            }

            if(WOOCOMMERCE_IS_ACTIVE){
                if($woocommerce_key == 'on_sale'){
                    $query_args['meta_query'] = array(
                        array(
                            'key'           => '_sale_price',
                            'value'         => 0,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        )
                    );
                }
                if($woocommerce_key == 'best_selling'){
                    $query_args['meta_query'] = array(
                        array(
                            'key' => 'total_sales'
                        )
                    );
                    $query_args['orderby'] = 'meta_value_num';
                }
            }

            // Handle meta queries from the listing arguments
            $meta_queries = self::build_meta_queries( $args['meta_queries'] ?? array() );
            if( !empty($meta_queries) ){
                $query_args['meta_query'] = array_merge( $query_args['meta_query'] ?? array(), $meta_queries );
            }
        }

        $query = new WP_Query( $query_args );

        return $query;
    }

    private static function build_meta_queries( $meta_queries ) {
        $result = array();
        if( empty($meta_queries) ) return $result;

        foreach ($meta_queries as $entry) {
            $type = $entry['__type'] ?? ( isset($entry['relation']) ? 'relation' : ( isset($entry['grouped_queries']) ? 'grouped_queries' : 'meta_query' ) );

            if( $type === 'relation' ){
                if( !empty($entry['relation']) ) $result['relation'] = strtoupper($entry['relation']);
                continue;
            }

            if( $type === 'grouped_queries' ){
                $nested = self::build_meta_queries( $entry['grouped_queries'] ?? array() );
                if( !empty($nested) ) $result[] = $nested;
                continue;
            }

            $clause = self::build_meta_query_clause( $entry );
            if( $clause !== null ) $result[] = $clause;
        }

        return $result;
    }

    private static function build_meta_query_clause( $query_data ) {
        if( !isset($query_data['key']) || empty($query_data['key']) ) return null;

        $compare_needs_no_value = array('EXISTS', 'NOT EXISTS');

        // normalize compare (accepts "NOT_EXISTS" as well as "NOT EXISTS")
        $compare = ( !empty($query_data['compare']) ) ? strtoupper( str_replace('_', ' ', trim($query_data['compare'])) ) : '=';
        $requires_value = !in_array($compare, $compare_needs_no_value, true);

        // EXISTS / NOT EXISTS don't take a value; skip entries missing a value otherwise
        if( $requires_value && !isset($query_data['value']) ) return null;

        $meta_query = array(
            'key' => $query_data['key'],
            'compare' => $compare,
        );

        if( !empty($query_data['type']) ) $meta_query['type'] = $query_data['type'];

        if( $requires_value ){
            $value = $query_data['value'];

            // if value is a comma separated string, convert it to an array
            if( is_string($value) && strpos($value, ',') !== false ){
                $value = array_map('trim', explode(',', $value));

                // '=' with an array value breaks wpdb::prepare, so use 'IN' instead
                if( !in_array($compare, array('IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'), true) ){
                    $compare = ( $compare === '!=' ) ? 'NOT IN' : 'IN';
                    $meta_query['compare'] = $compare;
                }
            }

            if( is_string($value) ){
                $value = self::resolve_dynamic_date_value($value);
            }

            $meta_query['value'] = $value;
        }

        return $meta_query;
    }

    // resolves dynamic date tokens like "@today", "@+3 days", "@first day of this month"
    private static function resolve_dynamic_date_value( $value ) {
        if( strlen($value) < 2 || $value[0] !== '@' ) return $value;

        $timestamp = strtotime( substr($value, 1) );
        return ( $timestamp !== false ) ? date('Y-m-d', $timestamp) : $value;
    }

    private static function fix_boolean_on_ajax_calls( $value ) {
        if( $value === 'true' ) return true;
        if( $value === 'false' ) return false;
        return $value;
    }
}