<?php
class Page_Header{

	private $page_header_element;
	private $id;
	private $layout;
	private $text_color;
	private $no_padding;
	private $parallax;
	private $page_header_bgi;
	private $bgc;
	private $classes;
	private $video_background;

	private $style;
	private $class;

	private $page_ID;
	private $page_type;
 	
 	function __construct(){
		$this->page_ID = Page::getInstance()->get_id();
		$this->page_type = Page::getInstance()->get_type();

 		$this->set_page_header_element();
		$this->set_layout();
		$this->set_classes();
		$this->set_text_color();
		$this->set_no_padding();
		$this->set_parallax();
		$this->set_page_header_bgi();
		$this->set_bgc();
		$this->set_video_background();
		$this->set_id();
		$this->set_class();
		$this->set_style();
 	}

 	private function set_page_header_element(){
		$page_header_element = get_metadata($this->page_type, $this->page_ID,'page_header_element', true);
		$this->page_header_element = ($page_header_element) ? $page_header_element : 'default';
	}
	private function set_layout(){
		$page_header_layout = get_metadata($this->page_type, $this->page_ID,'page_header_layout', true);
		$this->layout = ($page_header_layout) ? $page_header_layout : PAGE_HEADER_LAYOUT;
	}
	private function set_text_color(){
		$page_header_text_color = get_metadata($this->page_type, $this->page_ID,'page_header_text_color', true);
		$this->text_color = ($page_header_text_color) ? $page_header_text_color : PAGE_HEADER_TEXT_COLOR;
	}
	private function set_no_padding(){
		$page_header_padding = get_metadata($this->page_type, $this->page_ID,'page_header_padding', true);
		$this->no_padding = ($page_header_padding) ? $page_header_padding : 0;
	}
	private function set_parallax(){
		$page_header_bgi_parallax = get_metadata($this->page_type, $this->page_ID,'page_header_bgi_parallax', true);
		$this->parallax = ($page_header_bgi_parallax) ? $page_header_bgi_parallax : 0;
	}
	private function set_page_header_bgi(){
		$page_header_bgi = get_metadata($this->page_type, $this->page_ID,'page_header_bgi', true);
		$this->page_header_bgi = ($page_header_bgi) ? $page_header_bgi : null;
	}
	private function set_bgc(){
		$page_header_bgc = get_metadata($this->page_type, $this->page_ID,'page_header_bgc', true);
		$this->bgc = ($page_header_bgc) ? $page_header_bgc : PAGE_HEADER_BGC;
	}
	private function set_classes(){
		$page_header_class = get_metadata($this->page_type, $this->page_ID,'page_header_class', true);
		$this->classes = ($page_header_class) ? $page_header_class : null;
	}
	private function set_video_background(){
		$video_background = get_metadata($this->page_type, $this->page_ID,'page_header_video', true);
		$this->video_background = ($video_background) ? $video_background : null;
	}

 	private function set_style(){
 		$style = '';
 		
 		if ($this->get_bgc()) $style .= 'background-color: '.$this->get_bgc().';';
 		if ($this->get_page_header_bgi()) {
 			$bgi = wp_get_attachment_url( $this->get_page_header_bgi(), true);
 			if ($bgi) {
 				$style .= 'background-image: url('.$bgi.');';
 			}
 		}

		if(!empty($style)) $style = 'style="'.$style.'"'; 
		$this->style = $style;
 	}

 	private function set_class(){
 		$classes = array('page-header');

 		$classes[] = 'page-header--'.$this->get_page_header_element();
 		if ($this->get_page_header_element() == 'default') $classes[] = 'center-align';

		$page_header_class = get_metadata($this->page_type, $this->page_ID,'page_header_class', true);
		if($page_header_class) $classes[] = $page_header_class;

		if ($this->get_layout() == 'layout2' || $this->get_layout() == 'layout3') $classes[] = 'full-width';
		if ($this->get_no_padding()) $classes[] = 'no-padding';
		if ($this->get_text_color() == 'text-color-2') $classes[] = 'text-color-2';
		if ($this->get_parallax()) $classes[] = 'parallax';
		if ($this->get_page_header_bgi() == null) $classes[] = 'no-image';
		if ($this->get_video_background()) $classes[] = 'has-video-background';
		if ($this->get_classes()) $classes[] = $this->get_classes();

 		$this->class = 'class="'.implode(' ', $classes).'"';
 	}

	private function set_id(){
		$id = null;
		$page_header_id = get_metadata($this->page_type, $this->page_ID,'page_header_id', true);
		if(!empty($page_header_id)) $id = 'id="'.$page_header_id.'"'; 
		$this->id = $id;
	}

	public function get_page_header_element(){
		return $this->page_header_element;
	}
	public function get_id(){
		return $this->id;
	}
	public function get_layout(){
		return $this->layout;
	}
	public function get_classes(){
		return $this->classes;
	}
	public function get_text_color(){
		return $this->text_color;
	}
	public function get_no_padding(){
		return $this->no_padding;
	}
	public function get_parallax(){
		return $this->parallax;
	}
	public function get_page_header_bgi(){
		return $this->page_header_bgi;
	}
	public function get_bgc(){
		return $this->bgc;
	}
	public function get_video_background(){
		return $this->video_background;
	}
 	public function get_style(){
 		return $this->style;
 	}
 	public function get_class(){
 		return $this->class;
 	}
 	public function get_attributes(){
 		$attributes = null;
 		if ($this->get_id()) $attributes .= $this->get_id() . ' ';
 		if ($this->get_class()) $attributes .= $this->get_class();
 		if ($this->get_style()) $attributes .= ' '.$this->get_style();
 		return $attributes;
 	}
}