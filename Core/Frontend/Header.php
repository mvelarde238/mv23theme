<?php
namespace Core\Frontend;

use Core\Frontend\Page;
use Core\Utils\Helpers;

class Header{
	private $logo;
	private $styles = array();
	private $classes = array();
	private $overrided;
	private $header_type;

	private $page_ID;
	private $page_type;
 	
 	function __construct( $header_type = 'static' ){
		$page = new Page;
		$this->page_ID = $page->get_id();
		$this->page_type = $page->get_type();

		if( $header_type ) $this->header_type = $header_type;

 		$this->set_overrided();
 		$this->set_logo();
 		$this->set_styles();
 		$this->set_classes();
 	}

	private function get_meta( $meta ){
		$page = new Page();
		$value = null;
        $page_content_components = ($page->get_id() != null) ? get_post_meta($page->get_id(), 'page_content_components', true) : null;
        if( is_array($page_content_components) ) {
            $page_component = $page_content_components[0];
			if( isset( $page_component[ $meta ] ) ){
				$value = $page_component[ $meta ];
			}
		}

		return $value;
	}

 	private function set_overrided(){
		$key = $this->header_type;
 		$custom_header = $this->get_meta( 'custom_'.$key.'_header' );
 		if ($custom_header) $this->overrided = true;
 	}

 	private function set_logo(){
		$logo_id = null;

		$key = $this->header_type;  
		$header_logo = $key.'_header_logo';
 		
		$logo_version = ( $this->overrided ) ? $this->get_meta( $header_logo ) : get_option( $header_logo );
	 	if ( $logo_version === 'custom' ) {
			$custom_logo = 'custom_'.$key.'_header_logo';
			$logo_id = ( $this->overrided ) ? $this->get_meta( $custom_logo ) : get_option( $custom_logo );
		} else {
			$logo_id = get_option( $logo_version );
		}
		
 		$logo_url = ($logo_id) ? wp_get_attachment_image_src( $logo_id, 'full') : null; 
 		if (is_array($logo_url) && $logo_url[0]):
 			$this->logo = $logo_url[0];
 		endif;
 	}

 	private function set_styles(){
		$key = $this->header_type;
 		$styles = array();

		if ($this->overrided){
			$header_bgc = $key.'_header_bgc';
			$bgc = $this->get_meta( $header_bgc );
			$alpha = ($bgc && isset($bgc['alpha'])) ? $bgc['alpha'] : '100';
			$color = ($bgc['add_bgc']) ? 'rgba('.Helpers::hexToRgb($bgc['bgc'],$alpha).')' : 'initial';
			$styles[] = '--'.$key.'-header-color:'.$color.';';
		} 

		$this->styles = $styles;
 	}

 	private function set_classes(){
		$key = $this->header_type;
		$classes = array('header');
		if( $key ) $classes[] = 'header--'.$key;

		$header_color_scheme = $key.'_header_color_scheme';
 		$color_scheme = ($this->overrided) ? $this->get_meta( $header_color_scheme ) : get_option( $header_color_scheme );
 		if( $color_scheme ) $classes[] = $color_scheme;

 		if( $this->get_meta('hide_'.$key.'_header_logo') ) $classes[] = 'hide-'.$key.'-header-logo';

		$this->classes = $classes;
 	}

 	public function get_logo(){
 		return $this->logo;
 	}

 	public function get_styles(){
 		return $this->styles;
 	}

	public function get_classes(){
		return $this->classes;
	}

	public function get_options(){
		$header_type = $this->header_type;
		$options = array();
	
		$options['logo'] = $this->get_logo();
		$options['styles'] = $this->get_styles();
		$options['classes'] = $this->get_classes();

		return apply_filters('filter_header_options', $options, $header_type);
	}
}