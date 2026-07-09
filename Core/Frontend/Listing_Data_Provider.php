<?php
namespace Core\Frontend;

use WP_Query;

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

        $args_query = array();
        $listing_source = $args['source'] ?? 'auto'; // auto || manual
        $woocommerce_key = ( WOOCOMMERCE_IS_ACTIVE && isset($args['woocommerce_key']) ) ? $args['woocommerce_key'] : '';
            
        if ($listing_source == 'manual') {
            $posts_ids = array();
            $manual_posts_raw = $args['posts'];
            foreach ($manual_posts_raw as $post) {
                array_push($posts_ids, str_replace('post_','',$post) );
            };
            
            $args_query['post__in'] = $posts_ids;
            $args_query['orderby'] = 'post__in';
            $args_query['post_type'] = 'any';
            $args_query['posts_per_page'] = -1; // ?
        }
        
        if ($listing_source == 'auto') {
            $args_query['paged'] = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

            // post type
            $posttype = $args['posttype'] ?? '';
            $args_query['post_type'] = $posttype;

            // query params
            $query_params = $args['query_params'] ?? array();
            $posts_per_page = $query_params['posts_per_page'] ?? 3;
            $order = $query_params['order'] ?? 'DESC';
            $orderby = $query_params['orderby'] ?? 'date';

            // post status params
            $status_params = $args['status_params'] ?? array(
                'set_post_status' => false,
                'post_status' => array('publish')
            );
            $post_status = ( self::fix_boolean_on_ajax_calls( $status_params['set_post_status'] ) && isset($status_params['post_status']) && is_array($status_params['post_status']) && count($status_params['post_status']) > 0 ) ? $status_params['post_status'] : array('publish');

            $args_query['posts_per_page'] = $posts_per_page;
            $args_query['order'] = $order;
            $args_query['orderby'] = $orderby;
            $args_query['post_status'] = $post_status;
    
            // optional params
            if( isset($args['post__not_in']) ) $args_query['post__not_in'] = $args['post__not_in'];
            if( isset($query_params['offset']) && is_numeric($query_params['offset']) && $query_params['offset'] > 0 ){
                $args_query['offset'] = $query_params['offset'];
            } 
        
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
                    $args_query['tax_query'] = $tax_query;
                }
            }

            if(WOOCOMMERCE_IS_ACTIVE){
                if($woocommerce_key == 'on_sale'){
                    $args_query['meta_query'] = array(
                        array(
                            'key'           => '_sale_price',
                            'value'         => 0,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        )
                    );
                }
                if($woocommerce_key == 'best_selling'){
                    $args_query['meta_query'] = array(
                        array(
                            'key' => 'total_sales'
                        )
                    );
                    $args_query['orderby'] = 'meta_value_num';
                }
            }
        }
        
        $query = new WP_Query( $args_query );

        return $query;
    }

    private static function fix_boolean_on_ajax_calls( $value ) {
        if( $value === 'true' ) return true;
        if( $value === 'false' ) return false;
        return $value;
    }
}