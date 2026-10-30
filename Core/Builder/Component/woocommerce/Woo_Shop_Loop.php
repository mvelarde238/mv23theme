<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Shop_Loop extends Component {

    public function __construct() {
		parent::__construct(
			'woo-shop-loop',
			__( 'Shop Loop', 'mv23theme' ),
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('archive_template'),
            'block_category' => 'woocommerce'
		);
    }

	public static function get_icon() {
        return 'bi-grid';
    }

	public static function get_fields() {
		$fields = array(
			Field::create( 'message', '__message' )->hide_label()
                ->set_description(__('This component displays the WooCommerce shop loop.', 'mv23theme')),
		);
		return $fields;
	}

    public static function display($args){
        if( WOOCOMMERCE_IS_ACTIVE ){        
            ob_start(); 
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_product_loop_start();

	        if ( wc_get_loop_prop( 'total' ) ) {
	        	while ( have_posts() ) {
	        		the_post();
    
	        		do_action( 'woocommerce_shop_loop' );

	        		wc_get_template_part( 'content', 'product' );
	        	}
	        }

	        woocommerce_product_loop_end();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<p class="highlight-inner-message">Shop Loop Component</p>';

		return $template;
	}
}

new Woo_Shop_Loop();