<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Attributes extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-attributes',
			__( 'Attributes', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-card-list';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product attributes.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            global $product;
            if( ! $product ) {
                return '';
            }
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            wc_display_product_attributes($product);
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function _get_view_template() {
        $template = '<div class="product_meta">
        	<span class="sku_wrapper">SKU: <span class="sku">product-sku</span></span>
        	<span class="posted_in">Category: <a href="#" rel="tag">Term name</a></span>
        </div>';
        return $template;
    }
}

new Woo_Product_Attributes();