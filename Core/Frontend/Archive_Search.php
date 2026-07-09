<?php
namespace Core\Frontend;

class Archive_Search {
    public static function post_content_to_meta_queries($where, $wp_query){
        global $wpdb;
    
        //if there is no metaquery, bye!
        $meta_queries = $wp_query->get( 'meta_query' );
        if( !$meta_queries || $meta_queries == '' ) return $where;
    
        //if only one relation
        $where = str_replace($wpdb->postmeta . ".meta_key = 'post_title' AND " . $wpdb->postmeta . ".meta_value", $wpdb->posts . ".post_title", $where);
        $where = str_replace($wpdb->postmeta . ".meta_key = 'post_content' AND " . $wpdb->postmeta . ".meta_value", $wpdb->posts . ".post_content", $where);
        $where = str_replace($wpdb->postmeta . ".meta_key = 'post_excerpt' AND " . $wpdb->postmeta . ".meta_value", $wpdb->posts . ".post_excerpt", $where);
    
        ////for nested relations
    
        //count the numbers of meta queries for possible replacements
        $number_of_relations = count($meta_queries);
    
        //replace 'WHERE' using the multidimensional postmeta naming logic used by wordpress core
        $i = 1;
        while($i<=$number_of_relations && $number_of_relations > 0){
            $where = str_replace("mt".$i.".meta_key = 'post_title' AND mt".$i.".meta_value", $wpdb->posts . ".post_title", $where);
            $where = str_replace("mt".$i.".meta_key = 'post_content' AND mt".$i.".meta_value", $wpdb->posts . ".post_content", $where);
            $where = str_replace("mt".$i.".meta_key = 'post_excerpt' AND mt".$i.".meta_value", $wpdb->posts . ".post_excerpt", $where);
            $i++;
        }
    
        return $where;
    }

    public static function customize_main_query( $query ) {
        if( !is_admin() && $query->is_main_query() && ( isset($_GET['search']) || isset($_GET['taxonomies']) || isset($_GET['custom']) ) ){
            $meta_query = $query->get( 'meta_query' ) ?: array( 'relation' => 'AND' );
            $tax_query = $query->get( 'tax_query' ) ?: array();
    
            // search query
            if( isset($_GET['search']) && !empty($_GET['search']) ){ 
                $user_value = sanitize_text_field($_GET['search']);

                $search_sources = array( 'post_title', 'post_content', 'post_excerpt' );
                if( !empty($search_sources) ){
                    $search_query = array( 'relation' => 'OR');
                    foreach ($search_sources as $source) {
                        $search_query[] = array( 'key' => $source, 'value' => $user_value, 'compare' => 'LIKE' );
                    }
    
                    $meta_query[] = $search_query;
                }
            }
            // end search query

            // taxonomy query
            if( isset($_GET['taxonomies']) && is_array($_GET['taxonomies']) ){
                foreach( $_GET['taxonomies'] as $tax_slug => $term_value ){
                    if( empty($term_value) ) continue;

                    $term_ids = is_array($term_value)
                        ? array_map('absint', $term_value)
                        : array( absint($term_value) );

                    $tax_query[] = array(
                        'taxonomy' => sanitize_key($tax_slug),
                        'field'    => 'term_id',
                        'terms'    => $term_ids,
                    );
                }
            }
            // end taxonomy query

            // custom field query
            if( isset($_GET['custom']) && is_array($_GET['custom']) ){
                $custom_compare  = ( isset($_GET['custom_compare']) && is_array($_GET['custom_compare']) ) ? $_GET['custom_compare'] : array();
                $allowed_compares = array( '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE' );

                foreach( $_GET['custom'] as $meta_key => $value ){
                    if( !is_array($value) && $value === '' ) continue;
                    $meta_key = sanitize_key( $meta_key );

                    if( is_array($value) && isset($value['min'], $value['max']) ){
                        // number_range → BETWEEN
                        if( $value['min'] === '' && $value['max'] === '' ) continue;
                        $min = floatval( $value['min'] );
                        $max = floatval( $value['max'] );
                        if( $min > $max ) { $t = $min; $min = $max; $max = $t; }
                        $meta_query[] = array(
                            'key'     => $meta_key,
                            'value'   => array( $min, $max ),
                            'compare' => 'BETWEEN',
                            'type'    => 'NUMERIC',
                        );

                    } elseif( is_array($value) ){
                        // checkboxes → IN
                        $sanitized = array_values( array_filter( array_map( 'sanitize_text_field', $value ) ) );
                        if( empty($sanitized) ) continue;
                        $meta_query[] = array(
                            'key'     => $meta_key,
                            'value'   => $sanitized,
                            'compare' => 'IN',
                        );

                    } else {
                        // text / select / radio
                        $sanitized_value = sanitize_text_field( $value );
                        if( $sanitized_value === '' ) continue;
                        $compare = isset($custom_compare[$meta_key]) ? strtoupper( sanitize_text_field( $custom_compare[$meta_key] ) ) : '=';
                        if( !in_array( $compare, $allowed_compares ) ) $compare = '=';
                        $meta_query[] = array(
                            'key'     => $meta_key,
                            'value'   => $sanitized_value,
                            'compare' => $compare,
                        );
                    }
                }
            }
            // end custom field query
                
            $query->set( 'meta_query', $meta_query);
            $query->set( 'tax_query', $tax_query);
        }
    }
}