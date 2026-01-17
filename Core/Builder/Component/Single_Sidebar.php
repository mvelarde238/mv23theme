<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Single_Sidebar extends Component {

    public function __construct() {
		parent::__construct(
			'single_sidebar',
			__( 'Single_Sidebar', 'mv23theme' )
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
        $sidebar = 'page_sidebar';
        if( USE_PORTFOLIO_CPT && ( is_post_type_archive('portfolio-cat') || is_tax('portfolio-tag') || is_singular('portfolio') ) ) $sidebar = 'portfolio_sidebar';
        ob_start(); ?>
        <div style="height:100%">
		    <div class="pinned-block">
		    	<?php if (is_active_sidebar($sidebar)) : ?>
		    		<?php dynamic_sidebar($sidebar); ?>
		    	<?php endif ?>
		    </div>
	    </div>
        <?php
        return ob_get_clean();
    }
}

new Single_Sidebar();