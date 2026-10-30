<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Catalog_Ordering extends Component {

    public function __construct() {
		parent::__construct(
			'woo-catalog-ordering',
			__( 'Catalog Ordering', 'mv23theme' ),
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('archive_template'),
            'block_category' => 'woocommerce'
		);
    }

	public static function get_icon() {
        return 'bi-sort-down-alt';
    }

	public static function get_fields() {
		$fields = array(
			Field::create( 'message', '__message' )->hide_label()
                ->set_description(__('This component displays the WooCommerce catalog ordering.', 'mv23theme')),
		);
		return $fields;
	}

    public static function display($args){
        if( WOOCOMMERCE_IS_ACTIVE ){        
            ob_start(); 
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_catalog_ordering();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<form class="woocommerce-ordering" method="get">
	    	<select name="orderby" class="orderby">
	    		<option value="menu_order" selected="selected">Default order</option>
	    		<option value="popularity">Sort by popularity</option>
	    		<option value="rating">Sort by average rating</option>
	    		<option value="date">Sort by latest</option>
	    		<option value="price">Sort by price: low to high</option>
	    		<option value="price-desc">Sort by price: high to low</option>
	    	</select>
	    </form>';

		return $template;
	}
}

new Woo_Catalog_Ordering();