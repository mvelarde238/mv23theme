<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Container\Repeater_Group;

class Carousel extends Component {

    public function __construct() {
		parent::__construct(
			'carousel-wrapper',
			__( 'Carousel', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-slides';
    }

    public static function get_builder_data() {
        return array(
            'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {
        $width_style = 'width: 25%; min-width: initial;';

        $settings_fields_1 =  array(
            Field::create( 'tab', 'general_settings_tab', __('General Settings','mv23theme') ),
            Field::create( 'image_select', 'carousel_type', __('Carousel type','mv23theme') )
                ->hide_label()
                ->set_attr( 'class', 'image-select-2-cols' )
                ->show_label()
                ->add_options(array(
                    'slider' => array(
                        'label' => 'slider',
                        'image' => BUILDER_PATH.'/assets/images/galleries/slider.png'
                    ),
                    'marquee' => array(
                        'label' => 'marquee',
                        'image' => BUILDER_PATH.'/assets/images/galleries/marquee.png'
                    )
            )),
            Field::create( 'select', 'carousel_theme' )
                ->add_options( array(
                    'theme1' => __('Theme 1','mv23theme'),
                    // 'theme2' => __('Theme 2','mv23theme'),
                    'none' => __('None','mv23theme'),
                ))
                ->set_default_value('theme1')
                ->hide_label()
                ->set_prefix( __('Carousel Theme:', 'mv23theme') )
                ->add_dependency('carousel_type', 'slider', '=')
        );

        if( !SCROLL_ANIMATIONS ){
            $settings_fields_1[] = Field::create( 'message', 'marquee_message', __('Activate GSAP Animations','mv23theme') )->set_description('You need to active GSAP animations to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate GSAP Animations</a>')->add_dependency('carousel_type', 'marquee', '=')->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }
        
		$settings_fields_2  = array(
            Field::create( 'tab', 'marquee_settings_tab', __('Marquee Settings','mv23theme') )
                ->add_dependency('carousel_type', 'marquee', '='),
            Field::create( 'complex', 'marquee_settings', __('Marquee Settings', 'mv23theme') )->hide_label()->add_fields(array(
                Field::create( 'number', 'speed', __('Animation Speed', 'mv23theme') )
                    ->set_default_value(40)
                    ->set_suffix(__('Seconds', 'mv23theme'))
                    ->set_attr( 'style', 'width: 50%; min-width: initial;' ),
                Field::create( 'text', 'fade_width', __('Fade Width', 'mv23theme') )
                    ->set_placeholder('100px')
                    ->set_default_value('100px')
                    ->set_attr( 'style', 'width: 50%; min-width: initial;' ),
                Field::create( 'select', 'direction', __('Direction', 'mv23theme') )
                    ->add_options( array(
                        'left' => __('Left','mv23theme'),
                        'right' => __('Right','mv23theme'),
                    ))
                    ->set_default_value('left')
                    ->set_width( 50 ),
            ))->add_dependency('carousel_type', 'marquee', '='),

            Field::create( 'tab', 'slider_settings_tab', __('Slider Settings','mv23theme') )
                ->add_dependency('carousel_type', 'marquee', '!='),
        
            Field::create( 'complex', 'controls_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'show' )
                    ->hide_label()
                    ->set_text(__('Show controls','mv23theme'))
                    ->set_default_value(1)
                    ->set_width( 50 ),
                Field::create( 'select', 'position' )
                    ->hide_label()->add_dependency('show')->set_prefix( __('Position:', 'mv23theme') )->set_width( 50 )
                    ->set_default_value('center')
                    ->add_options( array(
                        'top' => __('Top','mv23theme'),
                        'center' => __('Center','mv23theme'),
                        'bottom' => __('Bottom','mv23theme'),
                    )),
            ))->add_dependency('carousel_type', 'slider', '='),

            Field::create( 'complex', 'nav_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'show' )->hide_label()->set_text(__('Show nav','mv23theme'))->set_width( 50 ),
                Field::create( 'select', 'position' )
                    ->hide_label()->add_dependency('show')->set_prefix( __('Position:', 'mv23theme') )->set_width( 50 )
                    ->set_default_value('bottom')
                    ->add_options( array(
                        'top' => __('Top','mv23theme'),
                        'bottom' => __('Bottom','mv23theme'),
                    ))
            ))->add_dependency('carousel_type', 'slider', '='),

            Field::create( 'complex', 'carousel_mode' )->hide_label()->add_fields(array(
                field::create( 'checkbox', 'active' )->hide_label()->set_text(__('Customize slider mode','mv23theme')),
                Field::create( 'select', 'mode' )
                    ->add_options( array(
                        'carousel' => 'Carrusel Mode',
                        'gallery' => 'Fade Mode',
                    ))
                    ->hide_label()
                    ->add_dependency('active')
                    ->set_width( 20 ),
                Field::create( 'select', 'axis' )
                    ->add_options( array(
                        'horizontal' => 'Horizontal',
                        'vertical' => 'Vertical',
                    ))
                    ->add_dependency('mode','carousel','=')
                    ->add_dependency('active')
                    ->set_prefix('Axis:')
                    ->hide_label()
                    ->set_width( 20 ),
                Field::create( 'number', 'speed' )
                    ->set_prefix('Animation Speed:')
                    ->add_dependency('active')
                    ->set_default_value(450)
                    ->set_placeholder('450')
                    ->set_suffix( 'ms' )
                    ->hide_label()
                // Field::create( 'checkbox', 'disable_rewind' )->set_text(__('Disable rewind','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency('mode','carousel','=')
                //     ->set_width( 20 )
            ))->add_dependency('carousel_type', 'slider', '='),

            Field::create( 'complex', 'autoplay_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'active' )->set_text(__('Start Automatically','mv23theme'))->hide_label(),
                Field::create( 'number', 'timeout' )
                    ->set_prefix('Timeout:')
                    ->set_placeholder('5000')
                    ->set_suffix( 'ms' )
                    ->hide_label()
                    ->add_dependency( 'active' ),
                // Field::create( 'checkbox', 'hover_pause' )->set_text(__('Pause on Hover','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency( 'active' )
                //     ->set_width( 20 ),
                // Field::create( 'checkbox', 'prevent_action' )->set_text(__('Prevent action when running','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency( 'active' )
                //     ->set_width( 20 )
            ))->add_dependency('carousel_type', 'slider', '='),

            // startIndex settings
            Field::create( 'complex', 'start_index_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'active' )->set_text(__('Set start index','mv23theme'))->hide_label(),
                Field::create( 'text', 'index' )
                    ->set_prefix('Start Index:')
                    ->set_placeholder('0')
                    ->add_suggestions( array(
                        '0','1','2',
                        'in_the_middle',
                        'at_the_end'
                    ))
                    ->hide_label()
                    ->add_dependency( 'active' )
            ))->add_dependency('carousel_type', 'slider', '='), 

            Field::create( 'tab', 'columns_settings_tab', __('Columns','mv23theme') )->add_dependency('carousel_type', 'slider', '='),
            Field::create( 'complex', 'items', __('Columns', 'mv23theme') )->hide_label()->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '4' )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '3' )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '2' )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '2' )->set_attr('style', $width_style)
            )),

            Field::create( 'tab', 'space_between_items_tab', __('Space between items','mv23theme') ),
            Field::create( 'complex', 'gutter' )->hide_label()->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style)
            )),

            Field::create( 'tab', 'advanced_settings_tab', __('Advanced Settings','mv23theme') )->add_dependency('carousel_type', 'slider', '='),
            Field::create('text', 'slider_uid', __('Slider UID', 'mv23theme'))
                ->set_description(__('This is used to identify the slider in the JS code. If you leave it empty, a random UID will be generated.', 'mv23theme'))
                ->set_attr( 'style', 'flex-grow: initial;' ),
            Field::create( 'checkbox', 'auto_height' )->hide_label()->set_text(__('Activate Auto Height','mv23theme'))->add_dependency('carousel_type', 'slider', '='),
            Field::create( 'checkbox', 'touch' )->hide_label()->set_text(__('Activate Touch','mv23theme'))->add_dependency('carousel_type', 'slider', '='),
        );

		return array_merge(
            $settings_fields_1,
            $settings_fields_2
        );
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
		$args['additional_classes'][] = 'carousel';
        $args['additional_attributes'] = array();

        // Get carousel items
        $items = array();
        if( isset($args['components']) && is_array($args['components']) ){
            $the_carousel = null;
            foreach( $args['components'] as $comp ){
                if( isset($comp['type']) && $comp['type'] === 'carousel' ){
                    $the_carousel = $comp;
                    break;
                }
            }
            if( $the_carousel ){
                $items = $the_carousel['components'];
            }
        }
        // return if not items found
        if( count($items) === 0 ) return;

        // Get settings values
        $carousel_type = $args['carousel_type'] ?? 'slider';
        
        $gutter_in_mobile = $args['gutter']['mobile'];
        $gutter_in_tablet = $args['gutter']['tablet'];
        $gutter_in_laptop = $args['gutter']['laptop'];
        $gutter_in_desktop = $args['gutter']['desktop'];
        
        // Build slider attributes
        if($carousel_type == 'slider'){
            $carousel_theme = $args['carousel_theme'] ?? 'theme1';
            if($carousel_theme !== 'none'){
                $args['additional_attributes']['data-theme'] = $carousel_theme;
            }
            
            $controls_settings = $args['controls_settings'] ?? array();
            $show_controls = $controls_settings['show'] ?? 1;
            $controls_position = $controls_settings['position'] ?? 'center';
    
            $nav_settings = $args['nav_settings'] ?? array();
            $show_nav = $nav_settings['show'] ?? 0;
            $nav_position = $nav_settings['position'] ?? 'bottom';
    
            $autoplay_settings = $args['autoplay_settings'] ?? array();
            $autoplay = $autoplay_settings['active'] ?? 0;
            $autoplay_timeout = $autoplay_settings['timeout'] ?? 5000;
            // $autoplay_hover_pause = $args['autoplay_hover_pause'] ?? 0;
            // $prevent_action = $args['prevent_action'] ?? 0;
            // $rewind = $args['rewind'] ?? 0;
            // style="transition-timing-function: linear;" 

            $carousel_mode = $args['carousel_mode'] ?? array(
                'active' => false,
                'mode' => 'carousel',
                'axis' => 'horizontal',
                'speed' => 450
            );
            $speed = $carousel_mode['active'] ? ($carousel_mode['speed'] ?? 450) : 450;
            $mode = $carousel_mode['active'] ? ($carousel_mode['mode'] ?? 'carousel') : 'carousel';
            $axis = $carousel_mode['active'] ? ($carousel_mode['axis'] ?? 'horizontal') : 'horizontal';
    
            $auto_height = $args['auto_height'] ?? 0;
            $touch = $args['touch'] ?? 0;
            $slider_uid = (!empty($args['slider_uid']) ) ? $args['slider_uid'] : uniqid('slider_');

            $items_in_mobile = $args['items']['mobile'];
            $items_in_tablet = $args['items']['tablet'];
            $items_in_laptop = $args['items']['laptop'];
            $items_in_desktop = $args['items']['desktop'];

            if( $show_nav ){
                $args['additional_attributes']['data-nav-position'] = $nav_position;
            } else {
                $args['additional_classes'][] = 'without-navigation';
            }
            if( $show_controls ){
                $args['additional_attributes']['data-controls-position'] = $controls_position;
            }

            // Handle start index
            $start_index_settings = $args['start_index_settings'] ?? array();
            $start_index = 0;
            if( isset($start_index_settings['active']) && $start_index_settings['active'] ){
                $start_index_value = $start_index_settings['index'] ?? 0;
                if( is_numeric($start_index_value) ){
                    $start_index = intval($start_index_value);
                } else {
                    // handle non-numeric values
                    switch ($start_index_value) {
                        case 'in_the_middle':
                            $start_index = floor(count($items) / 2);
                            break;
                        case 'at_the_end':
                            $start_index = count($items) - 1;
                            break;
                        default:
                            $start_index = 0;
                    }
                }
            }

            $slider_attributes = [];
            $slider_attributes['additional_attributes'] = array(
                'class' => 'carousel__slider',
                'data-show-nav' => $show_nav,
                'data-nav-position' => $nav_position,
                'data-mobile' => $items_in_mobile,
                'data-tablet' => $items_in_tablet,
                'data-laptop' => $items_in_laptop,
                'data-desktop' => $items_in_desktop,
                'data-mobile-gutter' => $gutter_in_mobile,
                'data-tablet-gutter' => $gutter_in_tablet,
                'data-laptop-gutter' => $gutter_in_laptop,
                'data-desktop-gutter' => $gutter_in_desktop,
                'data-autoplay' => $autoplay,
                'data-speed' => $speed,
                'data-autoplay-timeout' => $autoplay_timeout,
                'data-auto-height' => $auto_height,
                'data-touch' => $touch,
                'data-axis' => $axis,
                'data-mode' => $mode,
                'data-start-index' => $start_index,
                'data-slider-uid' => $slider_uid
            );
        }

        // Build marquee attributes
        if($carousel_type == 'marquee'){
            $marquee_attributes = [];
            $marquee_settings = $args['marquee_settings'] ?? array();
            $marquee_speed = ( isset($marquee_settings['speed']) && is_numeric($marquee_settings['speed']) ) ? $marquee_settings['speed'] : 40;
            $fade_width = $marquee_settings['fade_width'] ?? '100px';
            $direction = $marquee_settings['direction'] ?? 'left';
            $marquee_attributes['additional_attributes'] = array(
                'class' => 'marquee',
                'data-speed' => $marquee_speed,
                'data-direction' => $direction,
                'style' => "--fade-width:{$fade_width};--d-gap:{$gutter_in_desktop}px;--l-gap:{$gutter_in_laptop}px; --t-gap:{$gutter_in_tablet}px; --m-gap:{$gutter_in_mobile}px;"
            );
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        // Output starting HTML based on carousel type
        if($carousel_type == 'slider'): 
            $slider_attrs = Template_Engine::generate_attributes( $slider_attributes );
            echo '<div '.$slider_attrs.'>';
        else:
            $marquee_attrs = Template_Engine::generate_attributes( $marquee_attributes );
            echo '<div '.$marquee_attrs.'>';
            echo '<div class="marquee-track">';
        endif;

        // Loop through items and render them
        if( count($items) > 0 ){
		    foreach ($items as $item) {
                $id = (isset($item['attributes']) && isset($item['attributes']['id'])) ? $item['attributes']['id'] : '';
                echo '<div class="carousel__item--content">';
                echo '<div id="'.$id.'" class="carousel__item components-wrapper">';
                echo Template_Engine::check_components( $item );
                echo '</div>';
                echo '</div>';
		    }
		}

        // Output closing HTML based on carousel type
        if($carousel_type == 'slider'){
            echo '</div>';
        } else {
            echo '</div>';
            echo '</div>';
        }

        // if controls are enabled, render the carousel-controls component
        if($carousel_type == 'slider' && $show_controls ) {
            $controls_component = null;
            if( isset($args['components']) && is_array($args['components']) ){
                foreach( $args['components'] as $comp ){
                    if( isset($comp['type']) && $comp['type'] === 'carousel-controls' ){
                        $controls_component = $comp;
                        break;
                    }
                }
            }
            if( $controls_component ){
                $controls_component['slider_uid'] = $slider_uid;
                echo Template_Engine::getInstance()->handle( $controls_component );
            }
        }

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Carousel();