<?php
namespace Core\Frontend;

use WP_Query;
use Core\Builder\Component\Settings\Query_Parameters;
use Core\Builder\Component\Settings\Meta_Queries;
use Core\Builder\Component\Settings\Tax_Queries;

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
            
            // Handle WooCommerce specific queries
            $woo_tax_query = array();
            $woo_meta_query = array();
            if(WOOCOMMERCE_IS_ACTIVE){
                $woocommerce_key = isset($args['woocommerce_key']) ? $args['woocommerce_key'] : '';

                if($woocommerce_key == 'featured'){
                    $woo_tax_query[] = array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => array('featured'),
                        'operator' => 'IN'
                    );
                }
                if($woocommerce_key == 'on_sale'){
                    $woo_meta_query[] = array(
                        'key'           => '_sale_price',
                        'value'         => 0,
                        'compare'       => '>',
                        'type'          => 'numeric'
                    );
                }
                if($woocommerce_key == 'best_selling'){
                    $woo_meta_query[] = array(
                        'key' => 'total_sales'
                    );
                    $query_args['orderby'] = 'meta_value_num';
                    $query_args['order'] = 'DESC';
                }
            }

            // Handle tax queries 
            $tax_queries = Tax_Queries::from_repeater( $args['tax_queries'] ?? array() );
            $tax_query = self::combine_queries( $woo_tax_query, $tax_queries );
            if( !empty($tax_query) ){
                $query_args['tax_query'] = $tax_query;
            }

            // Handle meta queries
            $meta_queries = Meta_Queries::from_repeater( $args['meta_queries'] ?? array() );
            $meta_query = self::combine_queries( $woo_meta_query, $meta_queries );
            if( !empty($meta_query) ){
                $query_args['meta_query'] = $meta_query;
            }
        }

        $query = new WP_Query( $query_args );

        return $query;
    }

    // combines forced clauses (e.g. WooCommerce) with user clauses; user clauses keep their own relation inside a nested group
    private static function combine_queries( $forced_clauses, $user_queries ) {
        if( empty($forced_clauses) ) return $user_queries;
        if( empty($user_queries) ) return $forced_clauses;

        return array_merge( array( 'relation' => 'AND' ), $forced_clauses, array( $user_queries ) );
    }
}