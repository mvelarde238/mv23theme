<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Container\Repeater_Group;

if ( ! defined( 'PREV_CAROUSEL_ICON' ) ) define( 'PREV_CAROUSEL_ICON', 'fa-angle-left' );
if ( ! defined( 'NEXT_CAROUSEL_ICON' ) ) define( 'NEXT_CAROUSEL_ICON', 'fa-angle-right' );

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
                    ->set_attr( 'style', 'width: 50%; min-width: initial;' )
            ))->add_dependency('carousel_type', 'marquee', '='),

            Field::create( 'tab', 'slider_settings_tab', __('Slider Settings','mv23theme') )
                ->add_dependency('carousel_type', 'marquee', '!='),
        
            Field::create( 'complex', 'controls_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'show' )->hide_label()->set_text(__('Show controls','mv23theme'))->set_width( 50 ),
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

            Field::create( 'tab', 'columns_settings_tab', __('Columns Settings','mv23theme') ),
            Field::create( 'complex', 'items', __('Columns', 'mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '4' )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '3' )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '2' )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '2' )->set_attr('style', $width_style)
            ))->add_dependency('carousel_type', 'slider', '=')->hide_label(),

            Field::create( 'complex', 'gutter', __('Space between items', 'mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style)
            )),

            Field::create( 'tab', 'advanced_settings_tab', __('Advanced Settings','mv23theme') ),
            Field::create('text', 'slider_uid', __('Slider UID', 'mv23theme'))
                ->set_description(__('This is used to identify the slider in the JS code. If you leave it empty, a random UID will be generated.', 'mv23theme'))
                ->set_attr( 'style', 'flex-grow: initial;' ),
            Field::create( 'checkbox', 'auto_height' )->hide_label()->set_text(__('Activate Auto Height','mv23theme'))->add_dependency('carousel_type', 'slider', '='),
            Field::create( 'checkbox', 'touch' )->hide_label()->set_text(__('Activate Touch','mv23theme'))->add_dependency('carousel_type', 'slider', '='),
            Field::create( 'complex', 'customize_icons')->hide_label()->add_fields(array(
                field::create( 'checkbox', 'active' )->hide_label()->set_text(__('Customize navigation icons','mv23theme')),
                Field::create( 'icon', 'prev_icon' )
                    ->add_set( 'bootstrap-icons' )
                    ->add_set( 'font-awesome' )
                    ->set_default_value( PREV_CAROUSEL_ICON )
                    ->add_dependency('active')
                    ->set_width(50),
                Field::create( 'icon', 'next_icon' )
                    ->add_set( 'bootstrap-icons' )
                    ->add_set( 'font-awesome' )
                    ->set_default_value( NEXT_CAROUSEL_ICON )
                    ->add_dependency('active')
                    ->set_width(50),
            ))->add_dependency('carousel_type', 'slider', '=')
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
        $carousel_theme = $args['carousel_theme'] ?? 'theme1';
        if($carousel_theme !== 'none'){
            $args['additional_classes'][] = 'carousel--'.$carousel_theme;
        }

        $controls_settings = $args['controls_settings'] ?? array();
        $show_controls = $controls_settings['show'] ?? 0;
        $controls_position = $controls_settings['position'] ?? 'center';

        $nav_settings = $args['nav_settings'] ?? array();
        $show_nav = $nav_settings['show'] ?? 0;
        $nav_position = $nav_settings['position'] ?? 'bottom';

        $autoplay_settings = $args['autoplay_settings'] ?? array();
        $autoplay = $autoplay_settings['autoplay'] ?? 0;
        $autoplay_timeout = $autoplay_settings['autoplay_timeout'] ?? 5000;
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
        $slider_uid = $args['slider_uid'] ?? '';

        $items_in_mobile = $args['items']['mobile'];
        $items_in_tablet = $args['items']['tablet'];
        $items_in_laptop = $args['items']['laptop'];
        $items_in_desktop = $args['items']['desktop'];

        $gutter_in_mobile = $args['gutter']['mobile'];
        $gutter_in_tablet = $args['gutter']['tablet'];
        $gutter_in_laptop = $args['gutter']['laptop'];
        $gutter_in_desktop = $args['gutter']['desktop'];

        $customize_icons = $args['customize_icons'] ?? array(  
            'active' => false,
            'prev_icon' => PREV_CAROUSEL_ICON,
            'next_icon' => NEXT_CAROUSEL_ICON
        );
        $prev_icon = $customize_icons['active'] ? ($customize_icons['prev_icon'] ?? PREV_CAROUSEL_ICON) : PREV_CAROUSEL_ICON;
        $next_icon = $customize_icons['active'] ? ($customize_icons['next_icon'] ?? NEXT_CAROUSEL_ICON) : NEXT_CAROUSEL_ICON;

        if( $show_nav ){
            $args['additional_attributes'][] = 'data-nav-position="'.$nav_position.'"';
        } else {
            $args['additional_classes'][] = 'without-navigation';
        }

        if( $show_controls ){
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
                data-prev-icon="<?=$prev_icon?>"
                data-next-icon="<?=$next_icon?>"
                data-slider-uid="<?=$slider_uid?>">
        <?php else: 
            $marquee_settings = $args['marquee_settings'] ?? array();
            $marquee_speed = ( isset($marquee_settings['speed']) && is_numeric($marquee_settings['speed']) ) ? $marquee_settings['speed'] : 40;
            $fade_width = $marquee_settings['fade_width'] ?? '100px';
            ?>
            <div class="marquee" data-speed="<?=$marquee_speed?>" style="--fade-width:<?=$fade_width?>;--d-gap:<?=$gutter_in_desktop?>px;--l-gap:<?=$gutter_in_laptop?>px; --t-gap:<?=$gutter_in_tablet?>px; --m-gap:<?=$gutter_in_mobile?>px;">
            <div class="marquee-track">
        <?php endif; ?>

            <?php 
            if( isset($args['components']) && is_array($args['components']) ){
                $the_carousel = $args['components'][0];// carousel is inside a carousel wrapper
			    foreach ($the_carousel['components'] as $item) {
                    $id = (isset($item['attributes']) && isset($item['attributes']['id'])) ? $item['attributes']['id'] : '';
                    echo '<div class="carousel__item carousel__item--content">';
                    echo '<div id="'.$id.'" class="components-wrapper">';
                    echo Template_Engine::check_components( $item );
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