<?php
namespace Core\Admin;

use WP_Query;
use Core\Frontend\Pagination;

class Ajax_Load_Posts{

    public function load_posts() {

        // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
        //     exit("No naughty business please.");
        // }

        $texts = array(
            array("es" => "No hubieron resultados", "en" => "No matches were found"),
            array("es" => "No se enviaron parámetros", "en" => "No parameters were sent")
        );

        $filter_values = $_REQUEST;
        $paged = $_REQUEST["paged"];
        $lang = $_REQUEST["lang"];

        $listing_args = json_decode(stripslashes($_REQUEST['listing_args']), true);
        $posttype = $listing_args["posttype"];
        $taxonomies = $listing_args["taxonomies"];
        $terms = $listing_args["terms"];
        $postcard_template = $listing_args["post_template"];
        $listing_template = $listing_args["listing_template"];
        $on_click_post = $listing_args["on_click_post"];
        $on_click_scroll_to = $listing_args["on_click_scroll_to"];
        $per_page = $listing_args["per_page"] ?? null;
        // $offset = (int) $listing_args["offset"] ?? null;
        $order = $listing_args["order"] ?? null;
        $orderby = $listing_args["orderby"] ?? null;
        $wookey = $listing_args["wookey"];
        $pagination_type = $listing_args["pagination_type"];
        $post_status = $listing_args["post_status"] ?? 'publish';

        if ( $posttype && $paged && $per_page ) {
            $paged = ($paged) ? (int) $paged : 1;

            $args_query = array( 
                'post_type' => $posttype, 
                'paged' => $paged,
                'post_status' => $post_status
            );

            $listing_source = $listing_args['source'] ?? 'auto';
            if ($listing_source == 'manual') { // for admin use only
                $manual_posts = $listing_args['posts'] ?? array();
                if (is_array($manual_posts) && count($manual_posts) > 0) {
                    $posts_ids = array();
                    foreach ($manual_posts as $post) {
                        array_push($posts_ids, str_replace('post_','',$post) );
                    }
                    $args_query['post_type'] = 'any';
                    $args_query['post__in'] = $posts_ids;
                    $args_query['orderby'] = 'post__in'; // to keep the order of manual posts
                    // when using post__in, all posts are returned in one page, so we need to paginate manually
                    $args_query['posts_per_page'] = -1; 
                }
            }

            if ($listing_source == 'auto') {
                if( $order ) $args_query['order'] = $order;
                if( $orderby ) $args_query['orderby'] = $orderby;
                if( $per_page ) $args_query['posts_per_page'] = $per_page;
                // if( $offset ) $args_query['offset'] = $offset; // not working ?

                if( is_array($taxonomies) && is_array($terms) ){
                    $tax_query = array( 'relation' => 'AND' );

                    foreach ($taxonomies as $tax) {
                        $_terms = ( isset( $filter_values[$tax] ) && !empty( $filter_values[$tax] ) ) ? array($filter_values[$tax]) : $terms;

                        if( empty($_terms) ) continue;

                        array_push($tax_query, array(
                            'taxonomy' => $tax,
                            'field' => 'term_id',
                            'terms' => $_terms,
                            'include_children' => true,
                            'operator' => 'IN'
                        ));
                    }

                    if($wookey == 'featured'){
                        array_push($tax_query, array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN'
                        ));
                    }

                    if( count($tax_query) > 1 ) $args_query['tax_query'] = $tax_query;
                }

                if ( isset($filter_values['search']) && !empty($filter_values['search']) ) {
                    $args_query['s'] =  $filter_values['search'];
                }
            
                if ( isset($filter_values['year']) || isset($filter_values['month']) ) {
                    $year = $filter_values['year'] ?? null;
                    $month = $filter_values['month'] ?? null;

                    switch ($posttype) {
                        case 'event':
                            if ($year && $month) $dates = array( $year.'-'.$month.'-01 01:00:00', $year.'-'.$month.'-31 23:59:59' );
                            if (!$month) $dates = array( $year.'-01-01 01:00:00', $year.'-12-31 23:59:59' );
                        
                            $args_query['meta_query'] =  array(
                                'event_start_clause' => array(
                                    'key' => '_event_start',
                                    'value' => $dates,
                                    'compare' => 'BETWEEN',
                                    'type' => 'DATE'
                                )
                            );
                            $args_query['orderby'] = 'event_start_clause';
                            break;
                        
                        default:
                            $date_params = array();
                            if($year) $date_params['year'] = $year;
                            if($month) $date_params['month'] = $month;
                            $args_query['date_query'] = array( $date_params );
                            break;
                    }
                }

                if($wookey == 'on_sale'){
                    $args_query['meta_query'] = array(
                        array(
                            'key'           => '_sale_price',
                            'value'         => 0,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        )
                    );
                }
                if($wookey == 'best_selling'){
                    $args_query['meta_query'] = array(
                        array(
                            'key' => 'total_sales'
                        )
                    );
                    $args_query['orderby'] = 'meta_value_num';
                }
            }

            $query = new WP_Query( $args_query ); 

            if ($query->have_posts()) {
                $result['status'] = "success";

                ob_start(); 
                while ( $query->have_posts() ) : 
                    $query->the_post();

                    if($listing_template == 'carrusel') echo '<div>';
                    get_template_part( 'partials/card/postcard', $postcard_template, array( 
                        'post_template' => $postcard_template,
                        'on_click_post' => $on_click_post,
                        'on_click_scroll_to' => $on_click_scroll_to
                    ));
                    if($listing_template == 'carrusel') echo '</div>';
                endwhile;
                $result['posts'] = ob_get_clean();

                if ( $query->max_num_pages > 1 ){
                    ob_start(); 
                    if($pagination_type == 'classic') {
                        Pagination::display($query,$paged);
                    }
                    if($pagination_type == 'load_more'){
                        $load_more_text = LISTING_LOAD_MORE_TEXT;
                        echo '<p class="aligncenter"><button class="btn load_more_posts" data-paged="2">'.$load_more_text[$lang].'</button></p>'; 
                    }
                    $result['pagination'] = ob_get_clean();
                    $result['max_num_pages'] = $query->max_num_pages;
                } else {
                    $result['pagination'] = '';
                }

            } else {
                $result['status'] = "error";
                $result['message'] = $texts[0][$lang];
            }

        } else {
            $result['status'] = "error";
            $result['message'] = $texts[1][$lang];
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        }
        else {
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
        wp_die();
    }
}