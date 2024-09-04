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
        $lang = $_REQUEST["lang"];

        $posttype = $_REQUEST["posttype"];
        $paged = $_REQUEST["paged"];
        $taxonomies = explode(',',$_REQUEST["taxonomies"]);
        $terms = explode(',',$_REQUEST["terms"]);
        $search = $_REQUEST["search"];
        $year = $_REQUEST["year"];
        $month = $_REQUEST["month"];
        $post_template = $_REQUEST["post_template"];
        $listing_template = $_REQUEST["listing_template"];
        $on_click_post = $_REQUEST["on_click_post"];
        $per_page = $_REQUEST["per_page"];
        $offset = $_REQUEST["offset"];
        $order = $_REQUEST["order"];
        $orderby = $_REQUEST["orderby"];
        $wookey = $_REQUEST["wookey"];
        $pagination_type = $_REQUEST["pagination_type"];

        if ( $posttype && $paged && $per_page ) {
            $paged = ($paged) ? $paged : 1;

            $args_query = array( 
                'post_type' => $posttype, 
                'paged' => $paged, 
                'order' => $order,
                'orderby' => $orderby,
                // 'offset' => $offset, // not working
                'posts_per_page' => $per_page,
                'post_status' => 'publish',
            );

            if( is_array($taxonomies) && is_array($terms) ){
                $tax_query = array( 'relation' => 'AND' );
                $count = 0;
                foreach ($taxonomies as $tax) {
                    if( isset($terms[$count]) && !empty($terms[$count]) ){   
                        array_push($tax_query, array(
                            'taxonomy' => $tax,
                            'field' => 'term_id',
                            'terms' => array( $terms[$count] ),
                            'include_children' => true,
                            'operator' => 'IN'
                        ));
                    }
                    $count++;
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

            if ($search) {
                $args_query['s'] =  $search;
            }
    
            if ($year || $month) {
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

            $query = new WP_Query( $args_query ); 

            if ($query->have_posts()) {
                $result['status'] = "success";

                ob_start(); 
                while ( $query->have_posts() ) : 
                    $query->the_post();

                    if($listing_template == 'carrusel') echo '<div>';
                    set_query_var( 'on_click_post', $on_click_post );
                    get_template_part( 'partials/card/minipost',$post_template);
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
                        $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';
                        echo '<p class="aligncenter"><button class="btn load_more_posts" data-paged="2">'.$load_more_text[$current_lang].'</button></p>'; 
                    }
                    $result['pagination'] = ob_get_clean();
                    $result['max_num_pages'] = $query->max_num_pages;
                } else {
                    $result['pagination'] = '';
                }

            } else {
                $result['status'] = "error";
                $result['message'] = $texts[0][$lang];
                $result['tax_query'] = $tax_query;
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