<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Rating extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-rating',
			__( 'Rating', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-star';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product rating.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_template_single_rating();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<div class="woocommerce-product-rating">
            <div class="star-rating"></div>
            <a href="#reviews" class="woocommerce-review-link">(<span class="count">23</span> Customer Reviews)</a>
        </div>';
        
        return $template;
    }
}

new Woo_Product_Rating();