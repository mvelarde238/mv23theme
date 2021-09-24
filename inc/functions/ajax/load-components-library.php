<?php
add_action("wp_ajax_load_components_library", "load_components_library");

function load_components_library() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $do = true;

    if ( $do ) {
        $args_query = array( 
            'post_type' => 'component_library', 
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args_query ); 

        if ($query->have_posts()) :
            $result['status'] = "success";

            ob_start();
            echo '<div class="components-library__gallery">';
            while ( $query->have_posts() ) : 
                $query->the_post();
                include( locate_template( 'inc/partials/component-library.php', false, false ) ); 
            endwhile;
            echo '</div>';

            $result['content'] = ob_get_clean();
        else:
            $result['status'] = "error";
            $result['message'] = 'Aún no hay componentes guardados en la librería.';
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