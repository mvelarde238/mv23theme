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
            Field::create( 'icon', 'icon', __('Select Icon', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-box-seam' )
                ->add_dependency('source','icon','='),
            Field::create( 'image', 'image', __('Select Image', 'mv23theme') )
                ->add_dependency('source','image','='),

            Field::create( 'section', 'preset_icon_styles' , __('Preset Icon Styles', 'mv23theme') ),
            Field::create( 'actions_control', 'actions_control', __('Actions Control', 'mv23theme') )
                ->hide_label()
                ->set_attr( 'class', 'actions-control-3-cols' )
                ->set_actions(array(
                    array( 'label' => __('Circle', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'circle'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/circle.png' ),
                    array( 'label' => __('Rounded', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'rounded'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/rounded.png' ),
                    array( 'label' => __('Square', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'square'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/square.png' ),
                    // Outlined styles
                    array( 'label' => __('Circle Outline', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'circle-outline'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/circle-outline.png' ),
                    array( 'label' => __('Rounded Outline', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'rounded-outline'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/rounded-outline.png' ),
                    array( 'label' => __('Square Outline', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'square-outline'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/square-outline.png' ),
                    // Gradient styles
                    array( 'label' => __('Circle Gradient', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'circle-gradient'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/circle-gradient.png' ),
                    array( 'label' => __('Rounded Gradient', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'rounded-gradient'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/rounded-gradient.png' ),
                    array( 'label' => __('Square Gradient', 'mv23theme'), 'command' => 'set-preset-icon-style', 'hide_label' => true, 'args' => array('preset' => 'square-gradient'), 'image' => BUILDER_PATH.'/assets/images/preset-icon-styles/square-gradient.png' ),
                    // Reset styles
                    array( 'label' => __('Reset Styles', 'mv23theme'), 'command' => 'set-preset-icon-style', 'args' => array('preset' => 'none'), 'class' => 'uf-button button-primary' )
                ))
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