<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Theme_Options\Theme_Options;

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
            Field::create( 'tab', __('Icon','mv23theme') ),
            Field::create( 'radio', 'isource', __('Icon source:', 'mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'icon' => __('Icon', 'mv23theme'),
                    'image' => __('Image', 'mv23theme'),
                ))
                ->set_default_value('icon')
                ->set_width(50),
            Field::create( 'icon', 'iname', __('Icon', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-box-seam' )
                ->add_dependency('isource','icon','=')
                ->set_width(50),
            Field::create( 'image', 'iimage', __('Image', 'mv23theme') )
                ->add_dependency('isource','image','=')
                ->set_width(50),

            Field::create( 'tab', __('Icon Position','mv23theme') ),
            Field::create( 'image_select', 'iposition', __('Position', 'mv23theme'))
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
            Field::create( 'select', 'ialignment', __('Icon Alignment', 'mv23theme'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->set_default_value('flex-start')
                ->add_options(array(
                    'flex-start'  => __('Start', 'mv23theme'),
                    'center'  => __('Center', 'mv23theme'),
                    'flex-end'  => __('End', 'mv23theme'),
                )),

            // GLOBAL
            Field::create( 'tab', __('Others','mv23theme') ),
            Field::create( 'select', 'content_alignment', __('Content Alignment', 'mv23theme'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->set_default_value('flex-start')
                ->add_options(array(
                    'flex-start'  => __('Start', 'mv23theme'),
                    'center'  => __('Center', 'mv23theme'),
                    'flex-end'  => __('End', 'mv23theme'),
                )),
            Field::create( 'select', 'horizontal_alignment', 'Alineación Horizontal')
                ->set_description( "This setting allows you to align the entire component." )
                ->add_options(array(
                    ''  => __('Default','mv23theme'),
                    'left'  => 'Izquierda',
                    'center'  => 'Al Centro',
                    'right'  => 'Derecha'
                )),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
        
        $icon_source = $args['isource'];
        if ($icon_source == 'icon') {
            $icon_prefix = (str_starts_with($args['iname'],'fa')) ? 'fa' : 'bi';
        	$element = '<i class="'.$icon_prefix.' '.$args['iname'].'"></i>';
        } else {
        	$image_url = wp_get_attachment_url($args['iimage']);
        	$element = '<img src="'.$image_url .'" />';
        }

        // set icon position styles
        if (isset($args['iposition'])) $args['additional_classes'][] = 'icon--'.$args['iposition'];

        // Set icon alignment styles
        $icon_wrapper_style = '';
        $alignment_prop = ($args['iposition'] == 'top') ? 'justify-content' : 'align-items';
        $icon_wrapper_style .= $alignment_prop.':'.$args['ialignment'].';';
        $icon_wrapper_style = ($icon_wrapper_style) ? 'style="'.$icon_wrapper_style.'"' : '';

        // Set Horizontal alignment
        if (isset($args['horizontal_alignment']) && $args['horizontal_alignment'] != '') {
            $args['additional_classes'][] = $args['horizontal_alignment'].'-all';
        }

        // get icon component ID
        $icon_wrapper = $args['components'][0];
        $comp_icon = $icon_wrapper['components'][0];
        $comp_icon_id = $comp_icon['__gjsAttributes']['id'] ?? '';
		
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);

        echo '<div class="icon-wrapper" '.$icon_wrapper_style.'>';
	    echo '<div id="'.$comp_icon_id.'" class="icon-cmp">'.$element.'</div>';
	    echo '</div>';

        if( isset($args['components']) && is_array($args['components']) ){
            // content_wrapper -> $args['components'][1];
            $args['components'][1]['additional_classes'][] = 'content-wrapper';
            foreach ( $args['components'] as $component ) {
                echo Template_Engine::getInstance()->handle( $component['__type'], $component );
            }
		}

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Icon_and_Text();