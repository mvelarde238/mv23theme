<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Sale_Flash extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-sale-flash',
			__( 'Sale Flash', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-tag';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product sale flash.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_show_product_sale_flash();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<%
            postContext = BUILDER_GLOBALS.context.post;
            is_on_sale = postContext.product_meta.is_on_sale;
        %>
        <% if( is_on_sale ) { %>
            <span class="onsale">On Sale!</span>
        <% } else { %>
            <span class="onsale">Not on Sale</span>
        <% } %>';

        return $template;
    }
}

new Woo_Product_Sale_Flash();