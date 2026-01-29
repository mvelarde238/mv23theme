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
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
        $width_style = 'width: 25%; min-width: initial;';

        $settings_fields_1 =  array(
            Field::create( 'image_select', 'carousel_type', __('Carousel type','mv23theme') )
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
            ))
        );

        if( !SCROLL_ANIMATIONS ){
            $settings_fields_1[] = Field::create( 'message', 'marquee_message', __('Activate GSAP Animations','mv23theme') )->set_description('You need to activate GSAP animations to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate GSAP Animations</a>')->add_dependency('carousel_type', 'marquee', '=')->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }
        
		$settings_fields_2  = array(
            Field::create( 'complex', 'marquee_settings', __('Marquee Settings', 'mv23theme') )->hide_label()->merge()->add_fields(array(
                Field::create( 'number', 'marquee_speed', __('Marquee Animation Speed', 'mv23theme') )
                    ->set_default_value(18)
                    ->set_suffix(__('Seconds', 'mv23theme'))
                    ->set_attr( 'style', 'width: 50%; min-width: initial;' ),
                Field::create( 'color', 'fade_color', __('Fade Color', 'mv23theme') )
                    ->set_attr( 'style', 'width: 50%; min-width: initial;' )
            ))->add_dependency('carousel_type', 'marquee', '='),

            Field::create( 'complex', '_controls_wrapper' )->hide_label()->merge()->add_fields(array(
                Field::create( 'checkbox', 'show_controls' )->hide_label()->set_text(__('Show controls','mv23theme'))->set_width( 50 ),
                Field::create( 'select', 'controls_position' )->add_options( array(
                    'center' => __('Center','mv23theme'),
                    'bottom' => __('Bottom','mv23theme'),
                    'top' => __('Top','mv23theme'),
                ))->add_dependency('show_controls')
                ->hide_label()
                ->set_prefix( 'Position:' )
                ->set_width( 50 )
            ))->add_dependency('carousel_type', 'marquee', '!='),
            Field::create( 'complex', '_nav_wrapper' )->hide_label()->merge()->add_fields(array(
                Field::create( 'checkbox', 'show_nav' )->hide_label()->set_text(__('Show nav','mv23theme'))->set_width( 50 ),
                Field::create( 'select', 'nav_position' )->add_options( array(
                    'bottom' => __('Bottom','mv23theme'),
                    'top' => __('Top','mv23theme'),
                ))->add_dependency('show_nav')
                ->hide_label()
                ->set_prefix( 'Position:' )
                ->set_width( 50 )
            ))->add_dependency('carousel_type', 'marquee', '!='),
            Field::create( 'complex', '_autoplay_wrapper' )->hide_label()->merge()->add_fields(array(
                Field::create( 'checkbox', 'autoplay' )->set_text(__('Start Automatically','mv23theme'))->hide_label()->set_width( 50 ),
                Field::create( 'number', 'autoplay_timeout' )
                    ->set_prefix('Timeout:')
                    ->set_placeholder('5000')
                    ->set_suffix( 'ms' )
                    ->hide_label()
                    ->add_dependency( 'autoplay' )
                    ->set_width( 50 ),
                // Field::create( 'checkbox', 'autoplay_hover_pause' )->set_text(__('Pause on Hover','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency( 'autoplay' )
                //     ->set_width( 20 ),
                // Field::create( 'checkbox', 'prevent_action' )->set_text(__('Prevent action when running','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency( 'autoplay' )
                //     ->set_width( 20 )
            ))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'complex', '_mode_wrapper' )->hide_label()->merge()->add_fields(array(
                Field::create( 'select', 'mode' )->add_options( array(
                    'carousel' => 'Carrusel Mode',
                    'gallery' => 'Fade Mode',
                ))->hide_label()->set_width( 20 ),
                Field::create( 'select', 'axis' )->add_options( array(
                    'horizontal' => 'Horizontal',
                    'vertical' => 'Vertical',
                ))->add_dependency('mode','carousel','=')
                    ->set_prefix('Axis:')
                    ->hide_label()
                    ->set_width( 20 ),
                Field::create( 'number', 'speed' )
                    ->set_prefix('Animation Speed:')
                    ->set_default_value(450)
                    ->set_placeholder('450')
                    ->set_suffix( 'ms' )
                    ->hide_label()
                // Field::create( 'checkbox', 'disable_rewind' )->set_text(__('Disable rewind','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency('mode','carousel','=')
                //     ->set_width( 20 )
            ))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'checkbox', 'auto_height' )->hide_label()->set_text(__('Activate Auto Height','mv23theme'))->add_dependency('carousel_type', 'marquee', '!='),
            Field::create( 'checkbox', 'touch' )->hide_label()->set_text(__('Activate Touch','mv23theme'))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'complex', 'items', __('Columns', 'mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '4' )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '3' )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '2' )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '2' )->set_attr('style', $width_style)
            ))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'complex', 'gutter', __('Space between items', 'mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_attr('style', $width_style)
            )),

            Field::create('text', 'slider_uid', __('Slider UID', 'mv23theme'))
                ->set_description(__('This is used to identify the slider in the JS code. If you leave it empty, a random UID will be generated.', 'mv23theme'))
                ->set_attr( 'style', 'flex-grow: initial;' )
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

        $carousel_type = $args['carousel_type'] ?? 'slider';
        $show_controls = $args['show_controls'] ?? 0;
        $show_nav = $args['show_nav'] ?? 0;
        $nav_position = $args['nav_position'] ?? 'bottom';
        $autoplay = $args['autoplay'] ?? 0;
        $autoplay_timeout = $args['autoplay_timeout'] ?? 5000;
        // $autoplay_hover_pause = $args['autoplay_hover_pause'] ?? 0;
        // $prevent_action = $args['prevent_action'] ?? 0;
        // $rewind = $args['rewind'] ?? 0;
        // style="transition-timing-function: linear;" 
        $speed = $args['speed'] ?? 450;
        $auto_height = $args['auto_height'] ?? 0;
        $touch = $args['touch'] ?? 0;
        $axis = $args['axis'] ?? 'horizontal';
        $mode = $args['mode'] ?? 'carousel';
        $slider_uid = $args['slider_uid'] ?? '';

        $items_in_mobile = $args['items']['mobile'];
        $items_in_tablet = $args['items']['tablet'];
        $items_in_laptop = $args['items']['laptop'];
        $items_in_desktop = $args['items']['desktop'];

        $gutter_in_mobile = $args['gutter']['mobile'];
        $gutter_in_tablet = $args['gutter']['tablet'];
        $gutter_in_laptop = $args['gutter']['laptop'];
        $gutter_in_desktop = $args['gutter']['desktop'];

        if( $show_nav ){
            $nav_position = $args['nav_position'] ?? 'bottom';
            $args['additional_attributes'][] = 'data-nav-position="'.$nav_position.'"';
        } else {
            $args['additional_classes'][] = 'without-navigation';
        }

        if( $show_controls ){
            $controls_position = $args['controls_position'] ?? 'center';
            $args['additional_attributes'][] = 'data-controls-position="'.$controls_position.'"';
        }
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args); ?>

        <?php if($carousel_type == 'slider'): ?>
            <div class="carousel__slider"
                data-show-controls="<?=$show_controls?>" 
                data-show-nav="<?=$show_nav?>"
                data-nav-position="<?=$nav_position?>"
                data-mobile="<?=$items_in_mobile?>"
                data-tablet="<?=$items_in_tablet?>"
                data-laptop="<?=$items_in_laptop?>"
                data-desktop="<?=$items_in_desktop?>"
                data-mobile-gutter="<?=$gutter_in_mobile?>"
                data-tablet-gutter="<?=$gutter_in_tablet?>"
                data-laptop-gutter="<?=$gutter_in_laptop?>"
                data-desktop-gutter="<?=$gutter_in_desktop?>"
                data-autoplay="<?=$autoplay?>"
                data-speed="<?=$speed?>"
                data-autoplay-timeout="<?=$autoplay_timeout?>"
                data-auto-height="<?=$auto_height?>"
                data-touch="<?=$touch?>"
                data-axis="<?=$axis?>"
                data-mode="<?=$mode?>"
                data-slider-uid="<?=$slider_uid?>">
        <?php else: 
            $marquee_speed = ( isset($args['marquee_speed']) && is_numeric($args['marquee_speed']) ) ? $args['marquee_speed'] : 18;
            $fade_color = $args['fade_color'] ?? '';
            ?>
            <div class="marquee" data-speed="<?=$marquee_speed?>" style="--fade-color:<?=$fade_color?>;--d-gap:<?=$gutter_in_desktop?>px; --t-gap:<?=$gutter_in_tablet?>px; --m-gap:<?=$gutter_in_mobile?>px;">
            <div class="marquee-track">
        <?php endif; ?>

            <?php 
            if( isset($args['components']) && is_array($args['components']) ){
                $the_carousel = $args['components'][0];// carousel is inside a carousel wrapper
			    foreach ($the_carousel['components'] as $item) {
                    $id = (isset($item['__gjsAttributes']) && isset($item['__gjsAttributes']['id'])) ? $item['__gjsAttributes']['id'] : '';
                    echo '<div class="carousel__item carousel__item--content">';
                    echo '<div id="'.$id.'">';
                    if( isset($item['components']) && is_array($item['components']) ){
                        foreach ($item['components'] as $component) {
			    	        echo Template_Engine::getInstance()->handle( $component['__type'], $component );
			            }
                    }
                    echo '</div>';
                    echo '</div>';
			    }
		    }
            ?>

        <?php if($carousel_type == 'slider'): ?>
            </div> 
        <?php else: ?>
            </div>
            </div>
        <?php endif; ?>

        <?php echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Carousel();