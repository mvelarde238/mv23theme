<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Wrapper extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-wrapper',
			__( 'Product Wrapper', 'mv23theme' ),
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

	public static function get_icon() {
        return 'bi-square';
    }

	public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args){
        if( WOOCOMMERCE_IS_ACTIVE ){
            global $product;
            $args['additional_classes'][] = 'components-wrapper';
            
            ob_start(); 
            ?><div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>><?php
            echo Template_Engine::component_wrapper('start', $args);
            if(!is_admin()) do_action( 'woocommerce_before_single_product_summary' );
            echo Template_Engine::check_components( $args ); 
            if(!is_admin()) do_action( 'woocommerce_after_single_product_summary' );
            echo Template_Engine::component_wrapper('end', $args);
            echo '</div>';
            return ob_get_clean();
        }
    }
}

new Woo_Product_Wrapper();