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
            if ($content) echo '<div class="page-module"><div class="component">' . $content . '</div></div>';
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

	public function the_content( $id = null ){
		$page_ID = ($id) ? $id : self::get_id();

		$page_content = ($page_ID != null) ? get_post_meta($page_ID, 'page_content_components', true) : null;
		$page_content_styles = ($page_ID != null) ? get_post_meta($page_ID, 'page_content_styles', true) : null;

		if (is_array($page_content)) :
			ob_start();
			echo '<style>'.$page_content_styles.'</style>';

			// wrapper > components > container > components:
			$container_components = $page_content[0]['components'][0]['components'] ?? [];
				
			if (is_array($container_components) && !empty($container_components)) :
				foreach ($container_components as $component) :
					echo Template_Engine::getInstance()->handle( $component['__type'], $component );
				endforeach;
			endif;
			
			return ob_get_clean();
		else: 
			return '';
		endif;
	}
}