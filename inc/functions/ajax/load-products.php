<?php
add_action("wp_ajax_load_products", "load_products");
add_action("wp_ajax_nopriv_load_products", "load_products");

function load_products() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $term = $_REQUEST["term"];
    $posttype = 'wbproduct';
    $taxonomy = 'wbproduct_tax';

    if ( $term ) {
        $args_query = array( 
            'post_type' => $posttype, 
            'posts_per_page' => -1, 
            'orderby' => 'rand' 
        );
        
        $tax_query = array( array('taxonomy' => $taxonomy,'terms' => $term,'field' => 'id','include_children' => true,'operator' => 'IN') );
        $args_query['tax_query'] =  $tax_query;

        $query = new WP_Query( $args_query ); 

        if ($query->have_posts()) :
            $result['status'] = "success";
            $index = 1;

            ob_start();
            echo '<div class="products-gallery__slider">';
            while ( $query->have_posts() ) : 
                $query->the_post();
                include( locate_template( 'inc/partials/product-card.php', false, false ) ); 
                $index++;
            endwhile;
            echo '</div>';

            $result['content'] = ob_get_clean();
        else:
            $result['status'] = "error";
            $result['message'] = 'No hay productos de la categoria escogida';
        endif;

    } else {
        $result['status'] = "error";
        $result['message'] = 'No se enviaron los parámetros correctos';
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