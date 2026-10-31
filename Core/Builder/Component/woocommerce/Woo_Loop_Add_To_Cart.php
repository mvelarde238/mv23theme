<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Loop_Add_To_Cart extends Component {

    public function __construct() {
		parent::__construct(
			'woo-loop-add-to-cart',
			__( 'Add to Cart', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('postcard'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-cart';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce add to cart button.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_template_loop_add_to_cart();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
        $template = '<a href="#" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="xxx" data-product_sku="product-sku">Add to cart</a>';
        return $template;
    }
}

new Woo_Loop_Add_To_Cart();