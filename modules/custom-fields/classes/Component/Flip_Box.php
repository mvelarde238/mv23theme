<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Blocks_Layout;

class Flip_Box extends Component {

    public function __construct() {
		parent::__construct(
			'flip_box',
			__( 'Flip_Box', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-image-flip-horizontal';
    }

    public static function get_title_template() {
		$template = '<% if ( front_content.blocks_layout.length < 1 ){ %>
            This item is empty
        <% } else { %>
            <% if ( front_content.blocks_layout[0][0].__type == "text_editor" ){ %>
                <%= front_content.blocks_layout[0][0].content.replace(/<[^>]+>/ig, "") %>
            <% } else { %>
                <%= "First item type: "+front_content.blocks_layout[0][0].__type %>
            <% } %>
        <% } %>';
		
		return $template;
	}

    public static function get_layout(){
        return 'grid';
    }

	public static function get_fields() {
        $components = array( 'text_editor', 'image', 'spacer', 'button', 'video', 'map', 'icon_and_text', 'menu', 'carrusel' );
        $alignments = array(
            'start' => __('Start','default'),
            'center' => __('Center','default'),
            'end' => __('End','default')
        );

		$fields = array( 
            Field::create( 'tab', __('Content','default') ),
            Field::create( 'complex', 'front_content' )->add_fields(array(
                Blocks_Layout::the_field(array( 
                    'slug' => 'blocks_layout', 
                    'components' => $components
                )) 
            ))->set_width(50),
            Field::create( 'complex', 'back_content' )->add_fields(array(
                Blocks_Layout::the_field(array( 
                    'slug' => 'blocks_layout',
                    'components' => $components
                )) 
            ))->set_width(50),

            // Field::create( 'common_settings_control', 'front_content' )->set_container( 'blocks_layout_container' )->set_width(50),
            // Field::create( 'common_settings_control', 'back_content' )->set_container( 'blocks_layout_container' )->set_width(50),

            Field::create( 'complex', '_front_settings_wrapper' )->hide_label()->merge()->add_fields(array(
                Field::create( 'common_settings_control', 'front_settings' )->set_container( 'common_settings_container' )->set_width(30),
                Field::create( 'select', 'front_justify_content', __('Horizontal Alignment','default'))->add_options( $alignments )->set_width(30),
                Field::create( 'select', 'front_align_items', __('Vertical Alignment','default'))->add_options( $alignments )->set_width(30)
            ))->set_width(50),
            Field::create( 'complex', '_back_settings_wrapper' )->hide_label()->merge()->add_fields(array(
                Field::create( 'common_settings_control', 'back_settings' )->set_container( 'common_settings_container' )->set_width(30),
                Field::create( 'select', 'back_justify_content', __('Horizontal Alignment','default'))->add_options( $alignments )->set_width(30),
                Field::create( 'select', 'back_align_items', __('Vertical Alignment','default'))->add_options( $alignments )->set_width(30)
            ))->set_width(50),
            
            Field::create( 'tab', __('Box Settings','default') ),
            Field::create( 'image_select', 'flip_effect' )->show_label()->add_options(array(
                'horizontal-flip'  => array(
                    'label' => 'Horizontal flip',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/horizontal-flip.png'
                ),
                'vertical-flip'  => array(
                    'label' => 'Vertical flip',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/vertical-flip.png'
                ),
                'zoom-in'  => array(
                    'label' => 'Zoom in',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/zoom-in-flip.png'
                ),
                'slide-in'  => array(
                    'label' => 'Slide in',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/slide-in-flip.png'
                ),
            )),
            Field::create( 'image_select', 'aspect_ratio', __('Aspect Ratio') )->add_options(array(
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
                'custom'  => array(
                    'label' => 'custom',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-custom.png'
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
		
		$args['additional_classes'] = array('component');

        $aspect_ratio = ( isset($args['aspect_ratio']) ) ? $args['aspect_ratio'] : false;
        if( $aspect_ratio ){
            $aspect_ratio_value = ( $args['aspect_ratio'] != 'custom' ) ? $args['aspect_ratio'] : $args['custom_aspect_ratio'];
            $args['additional_styles'][] = '--flip-box-aspect-ratio:'.$aspect_ratio_value;
        }
        
        $flip_effect = ( isset($args['flip_effect']) ) ? $args['flip_effect'] : 'horizontal-flip';
        $args['additional_classes'][] = $flip_effect;

        $cards = array();
        $keys = array('front','back');
        foreach ($keys as $key) {
            $card = array();
            $card['key'] = $key;
            $card['content'] = $args[$key.'_content']['blocks_layout'];
            $card['args'] = array(
                'settings' => $args[$key.'_settings'],
                'additional_classes' => array('flip-box-'.$key)
            );
            if( $args[$key.'_align_items'] != 'start' ) $card['args']['additional_styles'][] = 'align-items:'.$args[$key.'_align_items'];
            if( $args[$key.'_justify_content'] != 'start' ) $card['args']['additional_styles'][] = 'justify-content:'.$args[$key.'_justify_content'];    
            $cards[] = $card;
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo '<div class="flip-box-inner">';
        foreach ($cards as $card) {
            echo Template_Engine::component_wrapper('start', $card['args']);
            echo Blocks_Layout::the_content($card['content']); 
            echo Template_Engine::component_wrapper('end', $card['args']);
        }
        echo '</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Flip_Box();