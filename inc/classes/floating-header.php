<?php
class Floating_Header{
	private $options;
	private $fixed_header_overrided;
	private $floating_header_overrided;

	private $page_ID;
	private $page_type;

	function __construct(){
		$this->page_ID = Page::getInstance()->get_id();
		$this->page_type = Page::getInstance()->get_type();

		$this->set_overrided();
		$this->set_options();
	}

	public function set_overrided(){
 		$custom_fixed_header = get_metadata($this->page_type, $this->page_ID,'custom_fixed_header', true);
 		if ($custom_fixed_header) $this->fixed_header_overrided = true;

 		$custom_floating_header = get_metadata($this->page_type, $this->page_ID,'custom_floating_header', true);
 		if ($custom_floating_header) $this->floating_header_overrided = true;
 	}

	private function set_options(){
		$options = array();

		if ($this->floating_header_overrided) {
 			$logo_version = get_metadata($this->page_type, $this->page_ID,'floating_header_logo', true);
 			$bgc = get_metadata($this->page_type, $this->page_ID,'floating_header_bgc', true);
 			$options['floating_text_color'] = get_metadata($this->page_type, $this->page_ID,'floating_header_color_scheme', true);
 		} else {
			$logo_version = get_option( 'floating_header_logo' );
 			$bgc = get_option( 'floating_header_bgc' );
			$options['floating_text_color'] = get_option( 'floating_header_color_scheme' );
 		}
 		$logo_id = get_option( $logo_version );
 		$logo_url = ($logo_id) ? wp_get_attachment_image_src( $logo_id, 'full') : null; 
 		if (is_array($logo_url) && $logo_url[0]):
 			$options['logo'] = $logo_url[0];
 		endif;

		if ($bgc['add_bgc']) $options['style'] = 'background-color: rgba('.hexToRgb($bgc['bgc'],$bgc['alpha']).');';

		if ($this->fixed_header_overrided) {
			$options['fixed_text_color'] = get_metadata($this->page_type, $this->page_ID,'fixed_header_color_scheme', true);
		} else {
			$options['fixed_text_color'] = get_option( 'fixed_header_color_scheme' );
		} 

		$this->options = $options;
	}

	public function get_options(){
		return $this->options;
	}
}