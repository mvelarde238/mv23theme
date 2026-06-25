<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use WP_Query;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Templates_Library {

	private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Templates_Library();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
		$templates_library = new CPT(
			array(
				'post_type_name' => 'templates_library', 
				'singular' => 'Template',
				'plural' => 'Templates'
			),
			array(
				"show_in_menu" => false,
				// 'show_in_menu' => 'edit.php?post_type=page',
				'show_in_nav_menus' => false,
				'show_in_admin_bar' => false,
				'exclude_from_search' => true,
				'supports' => array('title','thumbnail'),
				'menu_icon' => 'dashicons-tagcloud',
				'public' => false,
			)
		);
		
		$templates_library->register_taxonomy(array(
			'taxonomy_name' => 'templates_library_tax',
			'singular' => __('Template Category', 'mv23theme'),
			'plural' => __('Template Categories', 'mv23theme'),
			'slug' => 'templates_library_tax'
		));
	}

	public function add_meta_boxes(){
		Container::create( 'template_data_container' )
			->add_location( 'post_type', array('templates_library') )
			->add_fields(array(
				Field::create( 'textarea', 'template_data' )
					->set_rows(20)
			));
	}

	public function save_item() {

	    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
	    //     exit("No naughty business please.");
	    // }

	    $title = $_REQUEST['title'];
	    $category = $_REQUEST['category'];
	    $template_data = $_REQUEST['template_data'];

	    if ( !empty($title) && !empty($template_data) ) {

	        $params = array(
	            'post_title' => wp_strip_all_tags($title),
	            'post_type'  => 'templates_library',
	            'post_status'   => 'publish',
	            'meta_input' => array(
	                'template_data' => $template_data
	            )
	        );
	        $post_id = wp_insert_post( $params, true );

	        if (!is_wp_error($post_id)) {
				$result['status'] = "success";
				$result['message'] = sprintf(__('The item "%s" has been saved in the library', 'mv23theme'), $title);

	            if($category) {
					$term_response = wp_set_object_terms($post_id, $category, 'templates_library_tax');
				}

				// Save the screenshot as the post's featured image if provided
				if ( !empty($_REQUEST['thumbnail']) ) {
					$data_url = $_REQUEST['thumbnail'];
					if ( preg_match( '/^data:(image\/[a-z]+);base64,/i', $data_url, $type ) ) {
						$image_data = base64_decode( substr( $data_url, strpos( $data_url, ',' ) + 1 ) );
						$mime_type  = strtolower( $type[1] );
						$extension  = explode( '/', $mime_type )[1];
						$filename   = 'template-thumb-' . $post_id . '.' . $extension;

						$upload = wp_upload_bits( $filename, null, $image_data );

						if ( empty( $upload['error'] ) ) {
							$attachment = array(
								'post_mime_type' => $mime_type,
								'post_title'     => $filename,
								'post_content'   => '',
								'post_status'    => 'inherit',
							);
							$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
							if ( !is_wp_error( $attach_id ) ) {
								require_once( ABSPATH . 'wp-admin/includes/image.php' );
								$attach_meta = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
								wp_update_attachment_metadata( $attach_id, $attach_meta );
								set_post_thumbnail( $post_id, $attach_id );
							}
						}
					}
				}
	        } else {
	            $result['status'] = "error";
	            $result['message'] = __('The item could not be saved in the library', 'mv23theme');
	        }

	    } else {
	        $result['status'] = "error";
	        $result['message'] = __('Incorrect parameters were sent', 'mv23theme');
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

	    $args_query = array( 
	        'post_type' => 'templates_library', 
	        'posts_per_page' => -1
	    );

		if (isset($_REQUEST['categories']) && is_array($_REQUEST['categories']) && count($_REQUEST['categories']) > 0) {
			$args_query['tax_query'] = array(
				array(
					'taxonomy' => 'templates_library_tax',
					'field'    => 'slug',
					'terms' => $_REQUEST['categories']
				)
			);
		}

	    $query = new WP_Query( $args_query ); 

	    if ($query->have_posts()) :
	        $result['status'] = "success";

	        ob_start();
	        echo '<div class="templates-library__gallery">';
	        while ( $query->have_posts() ) : 
	            $query->the_post();
	            include( locate_template( 'partials/card/postcard-template-item.php', false, false ) ); 
	        endwhile;
	        echo '</div>';

	        $result['content'] = ob_get_clean();
	    else:
	        $result['status'] = "error";
	        $result['message'] = __('There are no components saved in the library yet.', 'mv23theme');
	    endif;

	    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	        $result = json_encode($result);
	        echo $result;
	    } else {
	        header("Location: ".$_SERVER["HTTP_REFERER"]);
	    }
	    wp_die();
	}

	public function library_item_action() {

	    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
	    //     exit("No naughty business please.");
	    // }

	    $post_id = isset($_REQUEST['post_id']) ? intval($_REQUEST['post_id']) : 0;
	    $item_action = isset($_REQUEST['item_action']) ? sanitize_text_field($_REQUEST['item_action']) : '';

	    if ( $post_id > 0 && !empty($item_action) ) {
	        if ($item_action == 'insert') {
	            $template_data = get_post_meta( $post_id, 'template_data', true );
			
	            if ($template_data) :
	                $result['status'] = "success";
	                $result['template_data'] = $template_data;
	            else:
	                $result['status'] = "error";
	                $result['message'] = __('The requested data could not be found.', 'mv23theme');
	            endif;

	        } else if($item_action == 'delete') {
	            $thumb_id = get_post_thumbnail_id($post_id);
	            if(wp_delete_post($post_id)){
	                if ($thumb_id) {
	                    wp_delete_attachment($thumb_id, true);
	                }
	                $result['status'] = "success";
	            } else {
	                $result['status'] = "error";
	                $result['message'] = __('The selected item could not be deleted.', 'mv23theme');
	            }

	        } else if($item_action == 'add-thumbnail') {
	            $thumb_id = $_REQUEST['thumb_id'];

	            if ($thumb_id && update_post_meta( $post_id, '_thumbnail_id', $thumb_id )) {
	                $result['status'] = "success";
	            } else {
	                $result['status'] = "error";
	                $result['message'] = __('The selected image could not be assigned.', 'mv23theme');
	            }

	        } else if($item_action == 'remove-thumbnail') {
	            if (delete_post_thumbnail($post_id)) {
	                $result['status'] = "success";
	            } else {
	                $result['status'] = "error";
	                $result['message'] = __('The selected image could not be removed.', 'mv23theme');
	            }

	        } else {
	            $result['status'] = "error";
	            $result['message'] = __('Incorrect parameters were sent', 'mv23theme');
	        }

	    } else {
	        $result['status'] = "error";
	        $result['message'] = __('Incorrect parameters were sent', 'mv23theme');
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
