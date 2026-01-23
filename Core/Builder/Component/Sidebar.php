<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Sidebar extends Component {

    public function __construct() {
		parent::__construct(
			'sidebar',
			__( 'Sidebar', 'mv23theme' )
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
		$sidebar_id = isset($args['sidebar_id']) ? $args['sidebar_id'] : 'page_sidebar';

		if( isset($args['post_id']) ) {
			$post_id = $args['post_id'];
			$post = get_post($post_id);
			$post_type = $post->post_type;
			if( $post_type == 'portfolio' ) $sidebar_id = 'portfolio_sidebar';
			if( $post_type == 'product' ) $sidebar_id = 'shop_sidebar';
		}

        ob_start(); ?>
		<div class="sidebar">
        	<div style="height:100%">
			    <div class="pinned-block">
			    	<?php if (is_active_sidebar($sidebar_id)) : ?>
			    		<?php dynamic_sidebar($sidebar_id); ?>
			    	<?php endif ?>
			    </div>
	    	</div>
	    </div>
        <?php
        return ob_get_clean();
    }
}

new Sidebar();