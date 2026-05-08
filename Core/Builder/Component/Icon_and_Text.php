<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Icon_and_Text extends Component {

    public function __construct() {
		parent::__construct(
			'icon-and-text',
			__( 'Icon and Text', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-align-pull-left';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Icon Position','mv23theme') ),
            Field::create( 'image_select', 'icon_position', __('Position', 'mv23theme'))
                ->hide_label()
                ->add_options(array(
                    'left'  => array(
                        'label' => __('Left', 'mv23theme'),
                        'image' =>  BUILDER_PATH.'/assets/images/icon-left.png'
                    ),
                    'top'  => array(
                        'label' => __('Top', 'mv23theme'),
                        'image' =>  BUILDER_PATH.'/assets/images/icon-top.png'
                    ),
                    'right'  => array(
                        'label' => __('Right', 'mv23theme'),
                        'image' =>  BUILDER_PATH.'/assets/images/icon-right.png'
                    ),
                )),
            Field::create( 'select', 'icon_alignment', __('Icon Alignment', 'mv23theme'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->set_default_value('flex-start')
                ->add_options(array(
                    'flex-start'  => __('Start', 'mv23theme'),
                    'center'  => __('Center', 'mv23theme'),
                    'flex-end'  => __('End', 'mv23theme'),
                )),

            // GLOBAL
            Field::create( 'tab', __('Other settings','mv23theme') ),
            Field::create( 'select', 'content_alignment', __('Content Alignment', 'mv23theme'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->set_default_value('flex-start')
                ->add_options(array(
                    'flex-start'  => __('Start', 'mv23theme'),
                    'center'  => __('Center', 'mv23theme'),
                    'flex-end'  => __('End', 'mv23theme'),
                )),
            Field::create( 'select', 'horizontal_alignment', __('Horizontal Alignment', 'mv23theme'))
                ->set_description( __("This setting allows you to align the entire component.", 'mv23theme') )
                ->add_options(array(
                    ''  => __('Default','mv23theme'),
                    'left'  => __('Left', 'mv23theme'),
                    'center'  => __('Center', 'mv23theme'),
                    'right'  => __('Right', 'mv23theme')
                )),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;
        
		$args['additional_classes'][] = 'component';

        // set icon position styles
        if (isset($args['icon_position'])) $args['additional_classes'][] = 'icon--'.$args['icon_position'];

        // Set icon alignment styles
        $icon_wrapper_style = '';
        $alignment_prop = ($args['icon_position'] == 'top') ? 'justify-content' : 'align-items';
        $icon_wrapper_style .= $alignment_prop.':'.$args['icon_alignment'].';';
        $icon_wrapper_style = ($icon_wrapper_style) ? 'style="'.$icon_wrapper_style.'"' : '';

        // Set Horizontal alignment
        if (isset($args['horizontal_alignment']) && $args['horizontal_alignment'] != '') {
            $args['additional_classes'][] = $args['horizontal_alignment'].'-all';
        }

        // set content alignment
        if (isset($args['content_alignment']) && $args['content_alignment'] != 'flex-start') {
            $args['additional_styles']['align-items'] = $args['content_alignment'];
        }
		
        $attributes = Template_Engine::generate_attributes( $args );
		ob_start();
        do_action( 'after_component_wrapper_start', $args );
        echo Template_Engine::check_actions('start', $args );
        echo '<div '.$attributes.'>';
        
        $icon_wrapper = $args['components'][0] ?? null;
        echo '<div class="icon-wrapper" '.$icon_wrapper_style.'>';
        $icon_box = $icon_wrapper['components'][0] ?? null;
		echo Template_Engine::getInstance()->handle( $icon_box );
	    echo '</div>';

        $content_wrapper = $args['components'][1] ?? null;
        $content_wrapper['additional_classes'][] = 'content-wrapper';
		echo Template_Engine::getInstance()->handle( $content_wrapper );

        do_action( 'before_component_wrapper_end', $args );
        echo '</div>';
        echo Template_Engine::check_actions('end', $args );
		return ob_get_clean();
	}
}

new Icon_and_Text();