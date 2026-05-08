<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Testimonial extends Component {

    public function __construct() {
		parent::__construct(
			'testimonial',
			__( 'Testimonial', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-testimonial';
    }

	public static function get_fields() {
        // testimonial styles
        $testimonial_styles_array = array();
        $testimonial_styles_quantity = 6;
        for ($i=1; $i <= $testimonial_styles_quantity ; $i++) { 
            $testimonial_styles_array['style'.$i] = array(
                'label' => 'Testimonial style '.$i,
                'image' => BUILDER_PATH . '/assets/images/testimonial/testimonial-style-'.$i.'.png'
            );
        }
        $testimonial_styles = apply_filters(
            'filter_testimonial_styles_for_testimonial_component',
            $testimonial_styles_array
        );

		$fields = array(
            Field::create( 'image_select', 'testimonial_style', __('Style','mv23theme') )
                ->set_attr( 'class', 'image-select-2-cols' )
                ->add_options( $testimonial_styles )
                ->set_default_value('style1')
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;
        $testimonial_style = ( isset($args['testimonial_style']) ) ? $args['testimonial_style'] : 'style1';
        $args['additional_attributes']['data-style'] = $testimonial_style;
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Testimonial();