<?php
namespace Theme;

use Theme_Custom_Fields\Template_Engine;
use Page;
use Blocks_Layout;

class Page_Header{

	private static $instance;
	private $page_ID;
	private $page_type;
	private $page_header_content_type;
	private $page_header_content;
	private $page_header_settings;
	private $page_header_slider;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Page_Header();
        }
        return self::$instance;
    }
 	
 	private function __construct(){
		$this->page_ID = Page::getInstance()->get_id();
		$this->page_type = Page::getInstance()->get_type();

 		$this->set_page_header_content_type();
 		$this->set_page_header_content();
 		$this->set_page_header_settings();
 		$this->set_page_header_slider();
 	}

 	private function set_page_header_content_type(){
		$page_header_content_type = get_metadata($this->page_type, $this->page_ID, 'page_header_content_type', true);
		$this->page_header_content_type = ($page_header_content_type) ? $page_header_content_type : 'default';
	}
	private function set_page_header_content(){
		$page_header_content = get_metadata($this->page_type, $this->page_ID, 'page_header_content', true);
		$this->page_header_content = ($page_header_content) ? $page_header_content : array();
	}
	private function set_page_header_settings(){
		$this->page_header_settings = get_metadata($this->page_type, $this->page_ID, 'page_header_settings', true);
	}
	private function set_page_header_slider(){
		$page_header_slider = get_metadata($this->page_type, $this->page_ID, 'page_header_slider', true);
		$this->page_header_slider = ($page_header_slider) ? $page_header_slider : array();
	}

	public function display(){
		$page_header_args = array( '__type' => 'page_header' );
		$page_header_args['settings'] = $this->page_header_settings;

		echo Template_Engine::component_wrapper( 'start', $page_header_args );

		switch ($this->page_header_content_type) {
			case 'slider':
				$this->the_slider();
				break;
			
			case 'content':
				$this->the_content();
				break;
			
			case 'none':
				break;
				
			default:
				echo '<div class="component center-align">';
				if (is_archive()) :
					the_archive_title( '<h1>', '</h1>' );
				elseif( is_search() ):
					$searchkey = $_GET['s'];
					echo '<h1 class="center">Resultados de búsqueda para: '.$searchkey.'</h1>';
				elseif( is_404() ):
					echo '<h1>Not Found</h1>';
				else:
					echo '<h1>'.get_the_title( $this->page_ID ).'</h1>';
				endif;
				echo '</div>';
		}

		echo Template_Engine::component_wrapper( 'end', $page_header_args );
	}

	public function the_content(){
		$page_header_content = $this->page_header_content;
		if (is_array($page_header_content) && count($page_header_content) > 0) :
            echo '<div class="page-header__content">';
				echo Blocks_Layout::the_content($page_header_content);
            echo '</div>';
        endif;
	}

	public function the_slider(){
		$device_key = (constant('IS_MOBILE')) ? 'mobile' : 'desktop';
		if( 
			isset( $this->page_header_slider[$device_key] ) && 
			!empty( $this->page_header_slider[$device_key] )
			){

			$slider_shortcode = $this->page_header_slider[$device_key];
			echo do_shortcode( $slider_shortcode );
		}
	}
}