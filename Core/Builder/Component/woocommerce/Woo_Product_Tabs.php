<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Product_Tabs extends Component {

    public function __construct() {
		parent::__construct(
			'woo-product-tabs',
			__( 'Data Tabs', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-segmented-nav';
    }

    public static function get_fields() {
		$fields = array(
            Field::create('message', '__message')->hide_label()
                ->set_description(__('This component displays the WooCommerce product tabs.', 'mv23theme')),

            Field::create( 'section', '_template_section', 'Template' ),
            Field::create( 'image_select', 'template' )
                ->hide_label()
                ->set_attr( 'class', 'image-select-2-cols' )
                ->add_options( array(
                    'template0' => array(
                        'label' => 'Template 0',
                        'image' => BUILDER_PATH . '/assets/images//toggleboxes/horizontal-tabs.png'
                    ),
                    'template1' => array(
                        'label' => 'Template 1',
                        'image' => BUILDER_PATH . '/assets/images//toggleboxes/tab-style-3.png'
                    ),
                    'template2' => array(
                        'label' => 'Template 2',
                        'image' => BUILDER_PATH . '/assets/images//toggleboxes/tab-style-4.png'
                    ),
                    'template3' => array(
                        'label' => 'Template 3',
                        'image' => BUILDER_PATH . '/assets/images//toggleboxes/tab-style-8.png'
                    )
                ) ),
        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            $args['additional_classes'][] = 'component';

            $template = isset($args['template']) ? $args['template'] : '';
            if( $template != 'template0' ) {
                $args['additional_classes'][] = $template;
            }

            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            woocommerce_output_product_data_tabs();
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }

    public static function get_view_template() {
        $template = '<div class="woocommerce-tabs wc-tabs-wrapper">
        	<ul class="tabs wc-tabs" role="tablist">
        		<li role="presentation" class="description_tab active" id="tab-title-description">
        			<a href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true" tabindex="0">Description</a>
        		</li>
        		<li role="presentation" class="additional_information_tab" id="tab-title-additional_information">
        			<a href="#tab-additional_information" role="tab" aria-controls="tab-additional_information" aria-selected="false" tabindex="-1">Additional information</a>
        		</li>
        		<li role="presentation" class="reviews_tab" id="tab-title-reviews">
        			<a href="#tab-reviews" role="tab" aria-controls="tab-reviews" aria-selected="false" tabindex="-1">Reviews (1)</a>
        		</li>
        	</ul>
        	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description" style="display: block;">
        		<h2>Description</h2>
        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        	</div>
        	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information" style="display: none;">
        		<h2>Additional information</h2>
        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        	</div>
        	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews" style="display: none;">
        		<div id="reviews" class="woocommerce-Reviews">
        			<div id="comments">
        				<h2 class="woocommerce-Reviews-title">Reviews</h2>
        			</div>
        		</div>
        	</div>
        </div>';
        return $template;
    }
}

new Woo_Product_Tabs();