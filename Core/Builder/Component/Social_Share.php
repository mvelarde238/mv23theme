<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Social_Share extends Component {

    public function __construct() {
		parent::__construct(
			'social-share',
			__( 'Social Share', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args){
        ob_start();
        echo '<div class="social-share-wrapper">';
        echo do_shortcode('[social_share]');
        echo '</div>';
        return ob_get_clean();
    }
}

new Social_Share();