<?php
add_action("wp_ajax_load_posts", "load_posts");
add_action("wp_ajax_nopriv_load_posts", "load_posts");

function load_posts() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $texts = array(
        array("es_ES" => "ANTERIOR", "en_US" => "PREVIUS"),
        array("es_ES" => "SIGUIENTE", "en_US" => "NEXT"),
        array("es_ES" => "En construcción", "en_US" => "No matches were found"),
        array("es_ES" => "No se enviaron parámetros", "en_US" => "No parameters were sent")
    );
    $lang = $_REQUEST["lang"];

    $term = $_REQUEST["term"];
    $search = $_REQUEST["search"];
    $year = $_REQUEST["year"];
    $month = $_REQUEST["month"];
    $paged = $_REQUEST["paged"];
    $posttype = $_REQUEST["posttype"];
    $taxonomy = $_REQUEST["taxonomy"];

    // if ( $term || $search || $year || $month ) {
    // if ( $term || $search ) {
        $paged = ($paged) ? $paged : 1;
        $actual_page = ($paged) ? $paged : 1;

        $args_query = array( 
            'post_type' => $posttype, 
            'posts_per_page' => 12, 
            'order'=>'ASC', 
            'paged' => $paged, 
            'orderby' => 'title' 
        );

        if( $term && $term != '' ){
            $tax_query = array( array('taxonomy' => $taxonomy,'terms' => $term,'field' => 'id','include_children' => true,'operator' => 'IN') );
            $args_query['tax_query'] =  $tax_query;
        } 

        if ($search) $args_query['s'] =  $search;

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

        $query = new WP_Query( $args_query ); 

        if ($query->have_posts()) :
            $result['status'] = "success";
            $index = 1;

            ob_start();
            while ( $query->have_posts() ) : 
                $query->the_post();
                include( locate_template( 'inc/partials/minipost-'.$posttype.'.php', false, false ) ); 
                $index++;
            endwhile;

            ///////////////////////////////////////////////////////////////////////////////////////////////
            // PAGINATION 
            ///////////////////////////////////////////////////////////////////////////////////////////////
            echo '<div class="documentos-grid__pagination">';
            $bignum = 999999999;
            if ( $query->max_num_pages > 1 ){
    
            $actual_link = home_url('/estudios-y-documentos-de-interes');;
            $base = $actual_link.'/page/' .'%#%'. '%_%';
    
                echo paginate_links( array(
                    'base'         => $base,
                    'format'       => '?paged=%#%',
                    'current'      => max( 1, $actual_page ),
                    'total'        => $query->max_num_pages,
                    'prev_text'    => '<<',
                    'next_text'    => '>>',
                    'type'         => 'list',
                    'end_size'     => 3,
                    'mid_size'     => 3
                ) );
            }
            echo '</div>';
            ///////////////////////////////////////////////////////////////////////////////////////////////

            $result['content'] = ob_get_clean();
        else:
            $result['status'] = "error";
            $result['message'] = $texts[2][$lang];
        endif;

    // } else {
    //     $result['status'] = "error";
    //     $result['message'] = $texts[3][$lang];
    // }

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    }
    else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    wp_die();
}