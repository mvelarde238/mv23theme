<?php
namespace Core\Frontend;

use Core\Builder\Template_Engine;
use Core\Frontend\Page;
use Core\Builder\Blocks_Layout;

class Page_Header{

	private static $instances = [];
	private $page_ID;
	private $page_type;
	private $content_type;
	private $content;
	private $settings;
	private $slider;

	public static function getInstance() {
		$calledClass = get_called_class();
        
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new $calledClass();
        }
        
        return self::$instances[$calledClass];
    }
 	
 	private function __construct(){
		$page = new Page();
		$this->page_ID = $page->get_id();
		$this->page_type = $page->get_type();

 		$this->set_content_type();
 		$this->set_content();
 		$this->set_settings();
 		$this->set_slider();
 	}

 	private function set_content_type(){
		$content_type = get_metadata($this->page_type, $this->page_ID, 'page_header_content_type', true);
		$this->content_type = ($content_type) ? $content_type : 'default';
	}
	private function set_content(){
		$content = get_metadata($this->page_type, $this->page_ID, 'page_header_content', true);
		$this->content = ($content) ? $content : array();
	}
	private function set_settings(){
		$settings = get_metadata($this->page_type, $this->page_ID, 'page_header_settings', true);
		$this->settings = ($settings) ? $settings : array();
	}
	private function set_slider(){
		$slider = get_metadata($this->page_type, $this->page_ID, 'page_header_slider', true);
		$this->slider = ($slider) ? $slider : array();
	}

	public function display(){
		$args = array( '__type' => 'page_header' );
		$args['settings'] = $this->settings;
		$args['additional_classes'] = [ 'page-header--'.$this->content_type ];
		
		// force containered page header
		if( !isset($args['settings']['layout']) || $args['settings']['layout']['key'] == 'layout1' ){
			$args['settings']['layout'] = array('use'=>1,'key'=>'layout2');
		}

		echo Template_Engine::component_wrapper( 'start', $args );

		switch ($this->content_type) {
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
					$posttype = get_post_type();
					echo '<p class="center mb0" style="font-size:13px;text-transform:uppercase;">'.$posttype.'</p>';
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

		echo Template_Engine::component_wrapper( 'end', $args );

		// echo '<pre>';
		// print_r($args);
		// echo '</pre>';
	}

	public function the_content(){
		$content = $this->content;
		if (is_array($content) && count($content) > 0) :
            echo '<div class="page-header__content">';
				echo Blocks_Layout::the_content($content);
            echo '</div>';
        endif;
	}

	public function the_slider(){
		$device_key = (constant('IS_MOBILE')) ? 'mobile' : 'desktop';
		if( 
			isset( $this->slider[$device_key] ) && 
			!empty( $this->slider[$device_key] )
			){

			$slider_shortcode = $this->slider[$device_key];
			echo do_shortcode( $slider_shortcode );
		}
	}

	public function get_content_type(){
		return $this->content_type;
	}
 	public function get_content(){
		return $this->content;
	}
 	public function get_settings(){
		return $this->settings;
	}
 	public function get_slider(){
		return $this->slider;
	}
}