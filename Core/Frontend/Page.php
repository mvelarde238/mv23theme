<?php
namespace Core\Frontend;

use Core\Builder\Template_Engine;
use Core\Posttype\Archive_Template;

class Page{ 
	private $id;
	private $type;

	public function __construct(){
		$page_ID = null;
		$key = 'post';

		if(is_home() || is_404()) {
			$page_ID = get_option( 'page_for_posts' );
		} else if (is_archive()) {
			$archive_template_id = Archive_Template::getInstance()->get_archive_template_id();
			if (!empty($archive_template_id)) {
				$page_ID = $archive_template_id;
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
			if( !$page_is_private ){
				if( !empty($content) ){
					echo '<div class="text-editor component">' . $content . '</div>';
				}
				echo $page->the_content();
			} 
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

	/**
	 * Compiles the structured styles array from page_content into a CSS string.
	 * 
	 * @param array $styles The page_content['styles'] array with GrapesJS style rules.
	 * @return string Compiled CSS string.
	 */
	public static function compile_styles_to_css( $styles ) {
		if ( !is_array($styles) || empty($styles) ) {
			return '';
		}

		$base_css = '';
		$media_css = array(); // [ mediaText => css_string ]

		foreach ( $styles as $style_rule ) {
			if ( !isset($style_rule['style']) || !is_array($style_rule['style']) || empty($style_rule['style']) ) {
				continue;
			}

			// Build selector string from selectors array
			$selectors = isset($style_rule['selectors']) ? $style_rule['selectors'] : array();
			$selector_parts = array();
			foreach ( $selectors as $selector ) {
				if ( is_string($selector) ) {
					$selector_parts[] = $selector;
				} elseif ( is_array($selector) && isset($selector['name']) ) {
					$selector_parts[] = '.' . $selector['name'];
				}
			}

			$selectors_add = isset($style_rule['selectorsAdd']) ? $style_rule['selectorsAdd'] : '';
			$state = isset($style_rule['state']) && !empty($style_rule['state']) ? ':' . $style_rule['state'] : '';
			$selector_string = implode('', $selector_parts) . $selectors_add . $state;

			if ( empty($selector_string) ) {
				continue;
			}

			// Build CSS declaration block
			$declarations = '';
			foreach ( $style_rule['style'] as $property => $value ) {
				$declarations .= $property . ':' . $value . ';';
			}

			$rule = $selector_string . '{' . $declarations . '}';

			// Group media query rules separately to preserve cascade order
			if ( !empty($style_rule['mediaText']) ) {
				$media_text = $style_rule['mediaText'];
				if ( !isset($media_css[$media_text]) ) {
					$media_css[$media_text] = '';
				}
				$media_css[$media_text] .= $rule;
			} else {
				$base_css .= $rule;
			}
		}

		// Sort media queries: max-width from largest to smallest to respect cascade
		uksort($media_css, function($a, $b) {
			$a_val = preg_match('/max-width\s*:\s*(\d+)/', $a, $ma) ? (int)$ma[1] : 0;
			$b_val = preg_match('/max-width\s*:\s*(\d+)/', $b, $mb) ? (int)$mb[1] : 0;
			return $b_val - $a_val;
		});

		// Output base rules first, then sorted media queries
		$css = $base_css;
		foreach ( $media_css as $media_text => $rules ) {
			$css .= '@media ' . $media_text . '{' . $rules . '}';
		}

		return $css;
	}

	public function the_content( $id = null ){
		$page_ID = ($id) ? $id : self::get_id();

		$page_content_datastore = ($page_ID != null) ? get_post_meta($page_ID, 'page_content_datastore', true) : null;
		$page_content = ($page_ID != null) ? get_post_meta($page_ID, 'page_content', true) : null;
		// Compile styles from structured data before consolidation
		$compiled_css = self::compile_styles_to_css( is_array($page_content) ? ($page_content['styles'] ?? []) : [] );
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
					
				if (is_array($container_components) && !empty($container_components)) :
					if ( !empty($compiled_css) ) echo '<style>' . $compiled_css . '</style>';

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