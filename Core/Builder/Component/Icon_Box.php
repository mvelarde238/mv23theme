<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Icon_Box extends Component {

    public function __construct() {
		parent::__construct(
			'icon-box',
			__( 'Icon Box', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'bi-box-seam';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'radio', 'source', __('Icon source:', 'mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'icon' => __('Icon', 'mv23theme'),
                    'image' => __('Image', 'mv23theme'),
                ))
                ->set_default_value('icon'),
            Field::create( 'icon', 'icon', __('Icon', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-box-seam' )
                ->add_dependency('source','icon','='),
            Field::create( 'image', 'image', __('Image', 'mv23theme') )
                ->add_dependency('source','image','='),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
        
        $icon_source = $args['source'];
        if ($icon_source == 'icon') {
            $icon_prefix = (str_starts_with($args['icon'],'fa')) ? 'fa' : 'bi';
        	$element = '<i class="icon-box__icon '.$icon_prefix.' '.$args['icon'].'"></i>';
        } else {
        	$image_url = wp_get_attachment_url($args['image']);
        	$element = '<img class="icon-box__icon" src="'.$image_url .'" />';
        }
		
        $attributes = Template_Engine::generate_attributes( $args );
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        echo $element;
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Icon_Box();