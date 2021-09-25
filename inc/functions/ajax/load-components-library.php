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






add_action("wp_ajax_component_library_action", "component_library_action");

function component_library_action() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $do = true;

    if ( $do ) {
        $post_id = $_REQUEST['post_id'];
        $btn_action = $_REQUEST['btn_action'];

        if ($btn_action == 'select') {
            $component_data = get_post_meta( $post_id, 'component_data', true );
    
            if ($component_data) :
                $result['status'] = "success";
                $result['component_data'] = $component_data;
            else:
                $result['status'] = "error";
                $result['message'] = 'No se pudo encontrar la data solicitada.';
            endif;
        } else {
            $result['status'] = "error";
            $result['message'] = 'No se enviaron los parámetros correctos';
        }

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