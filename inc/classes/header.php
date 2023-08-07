<?php
class Header{
	private $logo;
	private $style;
	private $classes;
	private $additional_classes = array();
	private $overrided;

	private $page_ID;
	private $page_type;
 	
 	function __construct(){
		$this->page_ID = Page::getInstance()->get_id();
		$this->page_type = Page::getInstance()->get_type();

 		$this->set_overrided();
 		$this->set_logo();
 		$this->set_style();
 		$this->set_classes();
 	}

 	private function set_overrided(){
 		$custom_fixed_header = get_metadata($this->page_type, $this->page_ID,'custom_fixed_header', true);
 		if ($custom_fixed_header) $this->overrided = true;
 	}

 	private function set_logo(){
 		if (get_metadata($this->page_type, $this->page_ID,'replace_logo', true)) {
	 		$logo_id = get_metadata($this->page_type, $this->page_ID,'header_logo', true);
 		} else {
	 		if ($this->overrided) {
	 			$logo_version = get_metadata($this->page_type, $this->page_ID,'fixed_header_logo', true);
	 		} else {
	 			$logo_version = get_option( 'fixed_header_logo' );
	 		}
 			$logo_id = get_option( $logo_version );
 		}
 		$logo_url = ($logo_id) ? wp_get_attachment_image_src( $logo_id, 'full') : null; 
 		if (is_array($logo_url) && $logo_url[0]):
 			$this->logo = $logo_url[0];
 		endif;
 	}

 	private function set_style(){
 		$style = '';
 		if ($this->overrided) {
 			$bgc = get_metadata($this->page_type, $this->page_ID,'fixed_header_bgc', true);
 		} else {
 			$bgc = get_option( 'fixed_header_bgc' );
 		}

		$alpha = ($bgc && isset($bgc['alpha'])) ? $bgc['alpha'] : '100';
		$style .= ($bgc && $bgc['add_bgc']) ? 'background-color: rgba('.hexToRgb($bgc['bgc'],$alpha).');' : '';

		if(!empty($style)) $style = 'style="'.$style.'"'; 
		$this->style = $style;
 	}

	public function set_additional_classes($additional_classes=array()){
		if($additional_classes && is_array($additional_classes)) $this->additional_classes = $additional_classes;
	}

 	private function set_classes(){
 		$classes = array('header');
 		if ($this->overrided) {
 			$color = get_metadata($this->page_type, $this->page_ID,'fixed_header_color_scheme', true);
 		} else {
 			$color = get_option( 'fixed_header_color_scheme' );
 		}
 		if ($color == 'text-color-2') $classes[] = 'text-color-2';

 		if(get_metadata($this->page_type, $this->page_ID,'hide_logo', true)) $classes[] = 'hide-logo';

		$this->classes = $classes;
 	}

 	public function get_logo(){
 		return $this->logo;
 	}

 	public function get_style(){
 		return $this->style;
 	}

 	public function get_class(){
		$classes = array_merge($this->classes, $this->additional_classes);
		$class_attribute = 'class="'.implode(' ', $classes).'"';
 		return $class_attribute;
 	}
}