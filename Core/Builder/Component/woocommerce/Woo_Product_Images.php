<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Images extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-images',
			__( 'Product Images', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-images';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product images.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_show_product_images();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<nav class="woocommerce-product-gallery">
            <div class="woocommerce-product-gallery__wrapper">
                <a href="#"><img src="<%= window["Handlebars"].parse("{{post.thumbnail}}") %>" alt="Product Image"></a>
            </div>
        </nav>';
        
        return $template;
    }
}

new Woo_Product_Images();