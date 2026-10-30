<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Result_Count extends Component {

    public function __construct() {
		parent::__construct(
			'woo-result-count',
			__( 'Result Count', 'mv23theme' ),
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('archive_template'),
            'block_category' => 'woocommerce'
		);
    }

	public static function get_icon() {
        return 'bi-square';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'message', '__message' )->hide_label()
                ->set_description(__('This component displays the WooCommerce result count.', 'mv23theme')),
        );
		return $fields;
	}

    public static function display($args){
        if( WOOCOMMERCE_IS_ACTIVE ){        
            ob_start(); 
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_result_count();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
		$template = '<div class="woo-result-count component">
            <p class="woocommerce-result-count">Showing 1–16 of 23 results</p>
        </div>';

		return $template;
	}
}

new Woo_Result_Count();