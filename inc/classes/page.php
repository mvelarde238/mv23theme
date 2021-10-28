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

	function __construct(){
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
	}

	function get_id(){
		return $this->id;
	}
	function get_type(){
		return $this->type;
	}
}