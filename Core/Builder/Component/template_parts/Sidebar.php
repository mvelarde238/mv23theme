<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;

class Sidebar extends Component {

    public function __construct() {
		parent::__construct(
			'sidebar',
			__( 'Sidebar', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'bi-layout-sidebar-inset-reverse';
    }

    public static function get_builder_data() {
        return array(
			'posttypes' => array(
				'single_template',
                'archive_template',
                array(
                    'posttype' => 'page',
                    'is' => ['page_for_posts']
                )
            ),
		);
    }

	public static function get_fields() {
		$fields = array(
			Field::create( 'sidebar', 'sidebar' )
				->make_editable()
				->set_default_value('page_sidebar')
				->hide_label()
		);
		return $fields;
	}

    public static function display($args){
		$sidebar = isset($args['sidebar']) ? $args['sidebar'] : 'page_sidebar';

		// if( isset($args['post_id']) ) {
		// 	$post_id = $args['post_id'];
		// 	$post = get_post($post_id);
		// 	$post_type = $post->post_type;
		// 	if( $post_type == 'portfolio' ) $sidebar = 'portfolio_sidebar';
		// 	if( $post_type == 'product' ) $sidebar = 'shop_sidebar';
		// }

		// if(is_archive() || is_home()) {
		// 	if( is_post_type_archive('portfolio-cat') || is_tax('portfolio-tag') ){
		// 		$sidebar = 'portfolio_sidebar';
		// 	} else if( is_post_type_archive('product') || is_tax('product_cat') || is_tax('product_tag') ){
		// 		$sidebar = 'shop_sidebar';
		// 	}
		// 	$sidebar = 'page_sidebar';
		// 	if( USE_PORTFOLIO_CPT && ( is_post_type_archive('portfolio-cat') || is_tax('portfolio-tag') || is_singular('portfolio') ) ){
		// 		$sidebar = 'portfolio_sidebar';
		// 	} 
		// }

        ob_start(); ?>
		<?php if (is_active_sidebar($sidebar)) : ?>
			<?php dynamic_sidebar($sidebar); ?>
		<?php endif ?>
        <?php
        return ob_get_clean();
    }
}

new Sidebar();