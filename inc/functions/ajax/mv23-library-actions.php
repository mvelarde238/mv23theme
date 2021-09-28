<?php
add_action("wp_ajax_mv23_library_save_item", "mv23_library_save_item");

function mv23_library_save_item() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $title = $_REQUEST['title'];
    $cat = $_REQUEST['cat'];
    $settings = $_REQUEST['settings'];

    if ( !empty($title) && !empty($cat) && !empty($settings) ) {

        $params = array(
            'post_title' => wp_strip_all_tags($title),
            'post_type'  => 'mv23_library',
            'post_status'   => 'publish',
            'meta_input' => array(
                'library_item_data' => $settings
            )
        );
        $post_id = wp_insert_post( $params, $wp_error );

        if (!is_wp_error($post_id)) {
            $term_response = wp_set_object_terms($post_id, $cat, 'mv23_library_tax');
            if ($term_response) {
                $result['status'] = "success";
                $result['message'] = 'Se guardó el item "'.$title.'" en la librería';
            } else{
                $result['status'] = "error";
                $result['message'] = 'Se guardó el item "'.$title.'" en la librería pero no se pudo asignar la categoría';
            }
        } else {
            $result['status'] = "error";
            $result['message'] = 'No se pudo guardar en la librería';
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






add_action("wp_ajax_load_mv23_library_gallery", "load_mv23_library_gallery");

function load_mv23_library_gallery() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $terms = $_REQUEST['terms'];

    if ( $terms ) {
        $args_query = array( 
            'post_type' => 'mv23_library', 
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'mv23_library_tax',
                    'field'    => 'slug',
                    'terms' => $terms
                )
            )
        );

        $query = new WP_Query( $args_query ); 

        if ($query->have_posts()) :
            $result['status'] = "success";

            ob_start();
            echo '<div class="mv23-library__gallery">';
            while ( $query->have_posts() ) : 
                $query->the_post();
                include( locate_template( 'inc/partials/item-mv23-library.php', false, false ) ); 
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






add_action("wp_ajax_mv23_library_action", "mv23_library_action");

function mv23_library_action() {

    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
    //     exit("No naughty business please.");
    // }

    $do = true;

    if ( $do ) {
        $post_id = $_REQUEST['post_id'];
        $btn_action = $_REQUEST['btn_action'];

        if ($btn_action == 'select') {
            $library_item_data = get_post_meta( $post_id, 'library_item_data', true );
    
            if ($library_item_data) :
                $result['status'] = "success";
                $result['library_item_data'] = $library_item_data;
            else:
                $result['status'] = "error";
                $result['message'] = 'No se pudo encontrar la data solicitada.';
            endif;

        } else if($btn_action == 'delete') {
            if(wp_delete_post($post_id)){
                $result['status'] = "success";
            } else {
                $result['status'] = "error";
                $result['message'] = 'No se pudo eliminar el item seleccionado.';
            }

        } else if($btn_action == 'add-thumbnail') {
            $thumb_id = $_REQUEST['thumb_id'];

            if ($thumb_id && update_post_meta( $post_id, '_thumbnail_id', $thumb_id )) {
                $result['status'] = "success";
            } else {
                $result['status'] = "error";
                $result['message'] = 'No se pudo asignar la imagen seleccionada.';
            }

        } else if($btn_action == 'remove-thumbnail') {
            if (delete_post_thumbnail($post_id)) {
                $result['status'] = "success";
            } else {
                $result['status'] = "error";
                $result['message'] = 'No se pudo quitar la imagen seleccionada.';
            }

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