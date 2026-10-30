<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Share extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-share',
			__( 'Share', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-share';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product share buttons.', 'mv23theme'))
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_template_single_sharing();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
        $template = '<p class="highlight-inner-message">Sharing plugins can hook into here.</p>';

        return $template;
    }
}

new Woo_Product_Share();