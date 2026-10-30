<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Add_To_Cart extends Component {

    public function __construct() {
		parent::__construct(
			'woo-add-to-cart',
			__( 'Add to Cart', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
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
            woocommerce_template_single_add_to_cart();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
        $template = '<form class="cart">
        	<div class="quantity">
        		<input type="number" id="quantity_6ae4c5c32fa4c" class="input-text qty text" name="quantity" value="1">
        	</div>
        	<button type="submit" name="add-to-cart" value="2794" class="single_add_to_cart_button button alt">Add to cart</button>
        </form>';
        return $template;
    }
}

new Woo_Add_To_Cart();