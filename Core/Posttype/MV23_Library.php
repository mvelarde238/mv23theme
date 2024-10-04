<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use WP_Query;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class MV23_Library {

	private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MV23_Library();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
		$mv23_library = new CPT(
			array(
				'post_type_name' => 'mv23_library', 
				'singular' => 'Library',
				'plural' => 'Library'
			),
			array(
				'show_in_menu' => 'edit.php?post_type=page',
				"show_in_menu" => false,
				'show_in_nav_menus' => false,
				'show_in_admin_bar' => false,
				'exclude_from_search' => true,
				'supports' => array('title','thumbnail'),
				'menu_icon' => 'dashicons-tagcloud',
			)
		);
		
		$mv23_library->register_taxonomy(array(
			'taxonomy_name' => 'mv23_library_tax',
			'singular' => 'Categoría',
			'plural' => 'Categorías',
			'slug' => 'mv23_library_tax'
		));
	}

	public function add_meta_boxes(){
		Container::create( 'library_item_data' )->add_location( 'post_type', array('mv23_library') )->add_fields(array(
			Field::create( 'textarea', 'library_item_data' ),
		));
	}

	public function save_item() {

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

	public function load_gallery() {

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
	                include( locate_template( 'partials/card/postcard-mv23-library.php', false, false ) ); 
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

	public function library_action() {

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
}
