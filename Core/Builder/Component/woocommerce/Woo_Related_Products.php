<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Related_Products extends Component {

    public function __construct() {
		parent::__construct(
			'woo-related-products',
			__( 'Related Products', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-box-seam';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce related products.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_output_related_products();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }
    
    public static function get_view_template() {
        $template = '<div class="highlight-inner-message"><p>Related Products</p></div>';
        return $template;
    }
}

new Woo_Related_Products();