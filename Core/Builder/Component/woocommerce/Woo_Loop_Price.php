<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Loop_Price extends Component {

    public function __construct() {
		parent::__construct(
			'woo-loop-price',
			__( 'Price', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('postcard'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-currency-dollar';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product price.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_template_loop_price();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<%
            postContext = BUILDER_GLOBALS.context.post;
            is_on_sale = postContext.product_meta.is_on_sale;
            regular_price = postContext.product_meta.regular_price;
            price = postContext.product_meta.price;
            sale_price = postContext.product_meta.sale_price;
            is_variable = postContext.product_meta.is_variable;
        %>
        <span class="price">
            <% if( is_on_sale && !is_variable ) { %>
                <del><span class="woocommerce-Price-amount amount">$<%= regular_price %></span></del>
                <ins><span class="woocommerce-Price-amount amount">$<%= sale_price %></span></ins>
            <% }else{ %>
                <span class="woocommerce-Price-amount amount">$<%= price %></span>
            <% } %>
        </span>';
        
        return $template;
    }
}

new Woo_Loop_Price();