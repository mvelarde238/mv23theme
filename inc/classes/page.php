<?php
class Page{
	private static $_instance = null;
	private $id;
	private $type;

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new Page();
        }
        return self::$_instance;
    }

	private function __construct(){
		$page_ID = null;
		$key = 'post';

		if(is_home() || is_404()) {
			$page_ID = get_option( 'page_for_posts' );
		} else if (is_archive()) {
			$archive_page_id = archive_page()->get_archive_id();
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

		self::filter_the_content();
		self::add_page_modules_to_rest_api();
	}

	function get_id(){
		return $this->id;
	}
	function get_type(){
		return $this->type;
	}

	private static function filter_the_content(){
        add_filter('the_content', function ($content) {
            if( is_singular() || is_page() ){
                ob_start();
                if ($content) echo '<div class="page-module"><div class="componente">' . $content . '</div></div>';
				echo Page::getInstance()->the_content();
                $filtered_content = ob_get_clean();
                return $filtered_content;
            } else {
                return $content;
            }
        }, 100);
    }

	private static function add_page_modules_to_rest_api(){
		add_filter('rest_prepare_page', function ($data, $post, $context) {
			$page_modules = Page::getInstance()->the_content( $post->ID );
	    	if (!empty($page_modules)) {
	    	    $data->data['content']['rendered'] .= $page_modules;
	    	}
	    	return $data;
		}, 10, 3);
	}

	public function the_content( $id = null ){
		$page_ID = ($id) ? $id : self::get_id();

		$modulos = ($page_ID != null) ? $modulos = get_post_meta($page_ID, 'v23_modulos', true) : null;
		$content_layout = ($page_ID != null) ? get_post_meta($page_ID, 'content_layout', true) : null;

		if (is_array($modulos) || is_array($content_layout)) :
			ob_start();

			if (is_array($modulos) && !empty($modulos) ) :
				foreach ($modulos as $modulo) :
					echo Theme_Custom_Fields\Template_Engine::getInstance()->handle( $modulo['__type'], $modulo );
				endforeach;
			endif;

			if (is_array($content_layout) && !empty($content_layout)) :
				$args = array();
				echo Content_Layout::the_content($content_layout, $args );
			endif;

			return ob_get_clean();
		else: 
			return '';
		endif;
	}
}