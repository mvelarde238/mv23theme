<?php
namespace Core\Frontend;

use Theme_Custom_Fields\Template_Engine;
use Blocks_Layout;
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
            ob_start();
            if ($content) echo '<div class="page-module"><div class="component">' . $content . '</div></div>';
			echo $page->the_content();
            $filtered_content = ob_get_clean();
            return $filtered_content;
        } else {
            return $content;
        }
    }

	public function add_page_modules_to_rest_api($data, $post, $context){
		$page = new Page();
		$page_modules = $page->the_content( $post->ID );
	    if (!empty($page_modules)) {
	        $data->data['content']['rendered'] .= $page_modules;
	    }
	    return $data;
	}

	public function get_id(){
		return $this->id;
	}
	
	public function get_type(){
		return $this->type;
	}

	public function the_content( $id = null ){
		$page_ID = ($id) ? $id : self::get_id();

		// return '<p>ID: '.$page_ID.'</p>';

		$page_modules = ($page_ID != null) ? $page_modules = get_post_meta($page_ID, 'page_modules', true) : null;
		$blocks_layout = ($page_ID != null) ? get_post_meta($page_ID, 'blocks_layout', true) : null;

		if (is_array($page_modules) || is_array($blocks_layout)) :
			ob_start();

			if (is_array($page_modules) && !empty($page_modules) ) :
				foreach ($page_modules as $modulo) :
					echo Template_Engine::getInstance()->handle( $modulo['__type'], $modulo );
				endforeach;
			endif;

			if (is_array($blocks_layout) && !empty($blocks_layout)) :
				$args = array();
				echo Blocks_Layout::the_content($blocks_layout, $args );
			endif;

			return ob_get_clean();
		else: 
			return '';
		endif;
	}
}