<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Flip_Box extends Component {

    public function __construct() {
		parent::__construct(
			'flipbox',
			__( 'Flip Box', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-image-flip-horizontal';
    }
    
	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Flip Effect','mv23theme') ),
            Field::create( 'image_select', 'flip_effect' )->hide_label()->show_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
                'horizontal-flip'  => array(
                    'label' => 'Horizontal flip',
                    'image' => BUILDER_PATH.'/assets/images/flipbox/horizontal-flip.png'
                ),
                'vertical-flip'  => array(
                    'label' => 'Vertical flip',
                    'image' => BUILDER_PATH.'/assets/images/flipbox/vertical-flip.png'
                ),
                'zoom-in'  => array(
                    'label' => 'Zoom in',
                    'image' => BUILDER_PATH.'/assets/images/flipbox/zoom-in-flip.png'
                ),
                'slide-in'  => array(
                    'label' => 'Slide in',
                    'image' => BUILDER_PATH.'/assets/images/flipbox/slide-in-flip.png'
                ),
            )),
            Field::create( 'tab', __('Aspect Ratio','mv23theme') ),
            Field::create( 'image_select', 'aspect_ratio', '' )->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-4-3.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-1.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-2_5.png'
                ),
                'custom'  => array(
                    'label' => 'custom',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-custom.png'
                ),
            )),
            Field::create( 'text', 'custom_aspect_ratio' )
                ->set_validation_rule('^(\d+(\.\d+)?)(\s*\/\s*(\d+(\.\d+)?))?$')
                ->add_dependency( 'aspect_ratio', 'custom' ),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'][] = 'component';

        $aspect_ratio = ( isset($args['aspect_ratio']) ) ? $args['aspect_ratio'] : false;
        if( $aspect_ratio ){
            $aspect_ratio_value = ( $args['aspect_ratio'] != 'custom' ) ? $args['aspect_ratio'] : $args['custom_aspect_ratio'];
            $args['additional_styles']['--flip-box-aspect-ratio'] = $aspect_ratio_value;
        }
        
        $flip_effect = ( isset($args['flip_effect']) ) ? $args['flip_effect'] : 'horizontal-flip';
        $args['additional_classes'][] = $flip_effect;

        $cards = array();
        $flipbox_inner = $args['components'][0];
        
        $keys = array('front','back');
        foreach ($keys as $index => $key) {
            $card = $flipbox_inner['components'][$index];
            $card['additional_classes'][] = 'flipbox-'.$key;
            $cards[] = $card;
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo '<div class="flipbox-inner">';
        foreach ($cards as $card) {
            echo Template_Engine::component_wrapper('start', $card);
            echo Template_Engine::check_components( $card );
            echo Template_Engine::component_wrapper('end', $card);
        }
        echo '</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Flip_Box();