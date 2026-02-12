<?php
namespace Core\Frontend;

use Core\Builder\Template_Engine;
use Core\Posttype\Archive_Page;

class Page{ 
	private $id;
	private $type;

	public function __construct(){
		$page_ID = null;
		$key = 'post';

		if(is_home() || is_404()) {
			$page_ID = get_option( 'page_for_posts' );
		} else if (is_archive()) {
			$archive_page_id = Archive_Page::getInstance()->get_archive_id();
			if (!empty($archive_page_id)) {
				$page_ID = $archive_page_id;
				$key = 'post';
			} else {
				if (is_post_type_archive()) {
					$page_ID = null;
					$key = 'post';
				} else if(is_date()){
					$page_ID = null;
					$key = 'post';
				} else {
					$page_ID = get_queried_object()->term_id;
					$key = 'term';
				}
			}
		} else if (is_search()) {
			$page_ID = null;
		} else {
			$page_ID = get_the_ID();
		}

		$this->id = $page_ID;
		$this->type = $key;
	}

	public function filter_the_content($content){
        if( is_singular() || is_page() ){
			$page = new Page();
			$page_is_private = self::page_is_private(); 
            ob_start();
			if( !$page_is_private ) echo $page->the_content();
            $filtered_content = ob_get_clean();
            return $filtered_content;
        } else {
            return $content;
        }
    }

	private function page_is_private(){
		global $post;
		
		if (post_password_required()) {
			return true;
		}
		
		if (get_post_status($post) === 'private' && !is_user_logged_in()) {
			return true;
		}
		
		return false;
	}

	public function add_page_content_to_rest_api($data, $post, $context){
		$page = new Page();
		$page_content = $page->the_content( $post->ID );
	    if (!empty($page_content)) {
	        $data->data['content']['rendered'] .= $page_content;
	    }
	    return $data;
	}

	public function get_id(){
		return $this->id;
	}
	
	public function get_type(){
		return $this->type;
	}

	/**
	 * Consolidates page_content by merging each component with its corresponding datastore entry
	 * 
	 * @param array $page_content Full page content structure
	 * @param array $datastore Associative array [component_id => component_data]
	 * @return array page_content with all components consolidated
	 */
	public static function consolidate_content( $page_content, $datastore ){
		if ( !is_array($page_content) || !is_array($datastore) ) {
			return $page_content;
		}

		// Navigate structure: pages > frames > component (wrapper)
		if ( isset($page_content['pages']) && is_array($page_content['pages']) ) {
			foreach ( $page_content['pages'] as $page_index => $page ) {
				if ( isset($page['frames']) && is_array($page['frames']) ) {
					foreach ( $page['frames'] as $frame_index => $frame ) {
						if ( isset($frame['component']) ) {
							$page_content['pages'][$page_index]['frames'][$frame_index]['component'] = 
								self::merge_component_datastore( $frame['component'], $datastore );
						}
					}
				}
			}
		}

		return $page_content;
	}

	/**
	 * Recursively merges a component with its datastore entry
	 * 
	 * @param array $component Component to process
	 * @param array $datastore Full datastore array
	 * @return array Component with datastore data merged
	 */
	private static function merge_component_datastore( $component, $datastore ){
		if ( !is_array($component) ) {
			return $component;
		}

		// Reserved keys that should not be overwritten from datastore
		$reserved_keys = [
			'components',
			'type',
			'attributes',
			'classes',
			'__id',
			'__post_id'
		];

		// Save child components before merge
		$child_components = isset($component['components']) ? $component['components'] : null;

		// Lookup datastore by __id
		$__id = $component['__id'] ?? null;
		if ( $__id && isset($datastore[$__id]) && is_array($datastore[$__id]) ) {
			// Filter reserved keys from datastore before merging
			$filtered_datastore = array_diff_key( 
				$datastore[$__id], 
				array_flip( $reserved_keys ) 
			);
			$component = array_merge( $component, $filtered_datastore );
		}

		// Restore and process child components recursively
		if ( $child_components !== null ) {
			$component['components'] = [];
			foreach ( $child_components as $child ) {
				$component['components'][] = self::merge_component_datastore( $child, $datastore );
			}
		}

		return $component;
	}

	public function the_content( $id = null ){
		$page_ID = ($id) ? $id : self::get_id();

		$page_content_styles = ($page_ID != null) ? get_post_meta($page_ID, 'page_content_styles', true) : null;
		$page_content_datastore = ($page_ID != null) ? get_post_meta($page_ID, 'page_content_datastore', true) : null;
		$page_content = ($page_ID != null) ? get_post_meta($page_ID, 'page_content', true) : null;
		// Consolidate content with datastore
		$page_content = self::consolidate_content( $page_content, $page_content_datastore );

		if (is_array($page_content)) :
			ob_start();

			$wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
			if ( !$wrapper['type'] === 'wrapper' ) return '';

			$container = null;
			foreach ( $wrapper['components'] as $component ) {
				if ( $component['type'] === 'container' ) {
					$container = $component;
					break;
				}
			}
			if ( $container ) {
				$container_components = $container['components'] ?? [];
	
				// If single page, get components inside single-page-structure:
				if( is_singular() && !empty($container_components) && $container_components[0]['type'] === 'single-page-structure' ){
					$single_page_structure = $container_components[0];
					$single_main = $single_page_structure['components'][0];
					$container_components = $single_main['components'];
				}

				// If archive page, get components inside archive-page-structure:
				if( (is_archive() || is_home()) && !empty($container_components) && $container_components[0]['type'] === 'archive-page-structure' ){
					$archive_page_structure = $container_components[0];
					$archive_main = $archive_page_structure['components'][0];
					$container_components = $archive_main['components'];
				}
					
				if (is_array($container_components) && !empty($container_components)) :
					echo '<style>'.$page_content_styles.'</style>';

					foreach ($container_components as $component) :
						$component['__post_id'] = $page_ID;
						echo Template_Engine::getInstance()->handle( $component );

					endforeach;
				endif;
			} else {
				error_log('No container found in wrapper component.');
			}

			return ob_get_clean();
		else: 
			return '';
		endif;
	}
}