<?php
if( !function_exists('load_posts') ){
    function load_posts() {

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
        $taxonomy = $_REQUEST["taxonomy"];
        $term = $_REQUEST["term"];
        $post_template = $_REQUEST["post_template"];
        $per_page = $_REQUEST["per_page"];

        if ( $posttype && $paged && $per_page ) {
            $paged = ($paged) ? $paged : 1;

            $args_query = array( 
                'post_type' => $posttype, 
                'paged' => $paged, 
                'posts_per_page' => $per_page,
                'post_status' => 'publish',
            );

            if($taxonomy && $term){
                $args_query['tax_query'] = array(array(
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => array( $term ),
                    'include_children' => true,
                    'operator' => 'IN'
                ));
            }

            $query = new WP_Query( $args_query ); 

            if ($query->have_posts()) :
                $result['status'] = "success";
                $template_file = THEME_DIR.'/inc/partials/minipost-'.$post_template.'.php';
                $template_file = file_exists( $template_file ) ? $template_file : THEME_DIR.'/inc/partials/minipost.php';

                ob_start(); 
                while ( $query->have_posts() ) : 
                    $query->the_post();
                    $title = get_the_title();
                    $id = get_the_ID();
                    $link = get_the_permalink($id);
                    $imagen = get_the_post_thumbnail_url( $id, 'medium' );
                    $thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
                    include($template_file);
                endwhile;
                $result['posts'] = ob_get_clean();

                ob_start(); 
                mv23_page_navi($query,$paged);
                $result['pagination'] = ob_get_clean();
                $result['max_num_pages'] = $query->max_num_pages;

            else:
                $result['status'] = "error";
                $result['message'] = $texts[0][$lang];
            endif;

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

add_action("wp_ajax_load_posts", "load_posts");
add_action("wp_ajax_nopriv_load_posts", "load_posts");