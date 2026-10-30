<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Shop_Loop_Header extends Component {

    public function __construct() {
		parent::__construct(
			'woo-shop-loop-header',
			__( 'Shop Header', 'mv23theme' ),
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('archive_template'),
            'block_category' => 'woocommerce'
		);
    }

	public static function get_icon() {
        return 'bi-square';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'message', '__message' )->hide_label()
                ->set_description(__('This component displays the WooCommerce shop loop header.', 'mv23theme')),
        );
		return $fields;
	}

    public static function display($args){
        if( WOOCOMMERCE_IS_ACTIVE ){        
            ob_start(); 
            echo Template_Engine::component_wrapper('start', $args);
            do_action( 'woocommerce_shop_loop_header' );
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<header class="woocommerce-products-header">
			<h1 class="woocommerce-products-header__title page-title">Shop Header</h1>
	    </header>';

		return $template;
	}
}

new Woo_Shop_Loop_Header();