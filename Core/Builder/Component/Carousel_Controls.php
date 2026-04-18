<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Carousel_Controls extends Component {

    public function __construct() {
		parent::__construct(
			'carousel-controls',
			__( 'Carousel Controls', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		return array();
	}

	public static function display( $args ){
        $slider_uid = $args['slider_uid'] ?? null;
		ob_start();
		echo '<div class="carousel-controls tns-controls">';
        $buttons = ['prev', 'next'];
        foreach( $buttons as $index => $button_type ){
            $button_component = $args['components'][$index] ?? null;
            if( $button_component ){
                $button_component['additional_attributes'] = array(
                    'data-controls' => $button_type,
                    'data-slider-uid' => $slider_uid
                );
                $button_component['additional_classes'] = array('go-to-'.$button_type.'-slide');
                echo Template_Engine::getInstance()->handle( $button_component );
            }
        }
        echo '</div>';
		return ob_get_clean();
	}
}

new Carousel_Controls();