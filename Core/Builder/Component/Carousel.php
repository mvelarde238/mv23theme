<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Container\Repeater_Group;
use Core\Builder\Slider_Settings;

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
            ))
        );

        if( !SCROLL_ANIMATIONS ){
            $settings_fields_1[] = Field::create( 'message', 'marquee_message', __('Activate GSAP Animations','mv23theme') )->set_description('You need to active GSAP animations to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate GSAP Animations</a>')->add_dependency('carousel_type', 'marquee', '=')->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }
        
		$settings_fields_2  = array(
            // Slider Settings
            Field::create( 'tab', 'slider_settings_tab', __('Slider Settings','mv23theme') )
                ->add_dependency('carousel_type', 'slider', '='),
            Slider_Settings::getRepeater( 'slider_settings', __('Slider Settings', 'mv23theme') )
                ->hide_label()
                ->add_dependency('carousel_type', 'slider', '='),

            // Marquee Settings
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

            // Columns Settings
            Field::create( 'tab', 'columns_settings_tab', __('Columns','mv23theme') )->add_dependency('carousel_type', 'slider', '='),
            Field::create( 'complex', 'items', __('Columns', 'mv23theme') )->hide_label()->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '4' )->set_minimum(1)->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '3' )->set_minimum(1)->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '2' )->set_minimum(1)->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '2' )->set_minimum(1)->set_attr('style', $width_style)
            )),

            // Space Between Items Settings
            Field::create( 'tab', 'space_between_items_tab', __('Space between items','mv23theme') ),
            Field::create( 'complex', 'gutter' )->hide_label()->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '20' )->set_attr('style', $width_style)
            ))
        );

		return array_merge(
            $settings_fields_1,
            $settings_fields_2
        );
	}

    private static function get_slider_uid( $slider_settings ){
        $uid = uniqid('test_slider_');

        if( isset($slider_settings['slider_uid']) && !empty($slider_settings['slider_uid']) ){
            $uid = $slider_settings['slider_uid'];
        }

        return $uid;
    }

    private static function get_controls_component( $components ){
        if( !is_array( $components ) ) return null;

        foreach( $components as $component ){
            if( isset( $component['type'] ) && $component['type'] === 'carousel-controls' ){
                return $component;
            }

            if(
                isset( $component['type'], $component['components'] )
                && $component['type'] === 'carousel-wrapper'
                && is_array( $component['components'] )
            ){
                $controls_component = self::get_controls_component( $component['components'] );

                if( $controls_component ){
                    return $controls_component;
                }
            }
        }

        return null;
    }

	public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;

        if( $args['attributes']['id'] == 'debug' ){
            error_log(print_r($args['slider_settings'], true));
        }
        
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

        // add slider UID if not already set
        $slider_uid_set = false;
        foreach( $args['slider_settings'] as $setting ){
            if( isset($setting['__type']) && $setting['__type'] === 'slider_uid' ){
                $slider_uid_set = true;
                break;
            }
        }
        if( !$slider_uid_set ){
            $args['slider_settings'][] = array(
                '__type' => 'slider_uid',
                'property' => 'slider_uid',
                'value' => uniqid('slider_')
            );
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        // Output starting HTML based on carousel type
        if($carousel_type == 'slider'): 
            echo self::slider_start( $args );
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
            echo self::slider_end();
            echo self::slider_controls( $args );

        } else if($carousel_type == 'marquee'){
            echo '</div>';
            echo '</div>';
        }

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    static function slider_start( $args ){
        // force controls false to avoid tns controls being generated, we will use our own controls component
        $args['slider_settings'][] = array(
            '__type' => 'controls',
            'property' => 'controls',
            'value' => false
        );

        // force "theme1" if not set
        $slider_theme_set = false;
        foreach( $args['slider_settings'] as $setting ){
            if( isset($setting['__type']) && $setting['__type'] === 'slider_theme' ){
                $slider_theme_set = true;
                break;
            }
        }
        if( !$slider_theme_set ){
            $args['slider_settings'][] = array(
                '__type' => 'slider_theme',
                'property' => 'slider_theme',
                'value' => 'theme1'
            );
        }

        // Build slider attributes
        $slider_attributes = [];

        if( $args['__type'] === 'carousel-wrapper' || $args['__type'] === 'theme-gallery-comp' ){
            $items = $args['items'];
            $gutter = $args['gutter'];
        }

        if( $args['__type'] === 'listing' || $args['__type'] === 'related-posts' ){
            $items = $args['columns'] ?? LISTING_COLUMNS;
            $gutter = $args['columns_gap'] ?? LISTING_GAP;
        }

        $items_in_mobile = $items['mobile'];
        $items_in_tablet = $items['tablet'];
        $items_in_laptop = $items['laptop'];
        $items_in_desktop = $items['desktop'];

        $gutter_in_mobile = $gutter['mobile'];
        $gutter_in_tablet = $gutter['tablet'];
        $gutter_in_laptop = $gutter['laptop'];
        $gutter_in_desktop = $gutter['desktop'];

        $slider_attributes['additional_attributes'] = array_merge( 
            array(
                'class' => 'carousel__slider',
                'data-mobile' => $items_in_mobile,
                'data-tablet' => $items_in_tablet,
                'data-laptop' => $items_in_laptop,
                'data-desktop' => $items_in_desktop,
                'data-mobile-gutter' => $gutter_in_mobile,
                'data-tablet-gutter' => $gutter_in_tablet,
                'data-laptop-gutter' => $gutter_in_laptop,
                'data-desktop-gutter' => $gutter_in_desktop,
            ), 
            Slider_Settings::to_dataset_attributes( $args['slider_settings'] ?? array() )
        );

        $slider_attrs = Template_Engine::generate_attributes( $slider_attributes );
        return '<div '.$slider_attrs.'>';
    }

    static function slider_end(){
        return '</div>';
    }

    static function slider_controls( $args, $required = false ){
        $slider_settings = Slider_Settings::from_repeater( $args['slider_settings'] ?? array() );

        $show_controls = !empty( $slider_settings['controls'] );
        
        // if controls are enabled, render the carousel-controls component
        if($show_controls ) {
            $slider_uid = self::get_slider_uid( $slider_settings );
            $controls_component = self::get_controls_component( $args['components'] ?? null );

            ob_start();
            if( $controls_component ){
                $controls_component['slider_uid'] = $slider_uid;
                echo Template_Engine::getInstance()->handle( $controls_component );
            } else {
                if( $required ){ ?>
                    <div class="carousel-controls tns-controls">
                        <div class="go-to-prev-slide component icon-box" data-controls="prev" data-slider-uid="<?=$slider_uid?>">
                            <i class="icon-box__icon fa <?=PREV_CAROUSEL_ICON?>"></i>
                        </div>
                        <div class="go-to-next-slide component icon-box" data-controls="next" data-slider-uid="<?=$slider_uid?>">
                            <i class="icon-box__icon fa <?=NEXT_CAROUSEL_ICON?>"></i>
                        </div>
                    </div>
                    <?php
                }
            }
            return ob_get_clean();
        }
        return '';
    }
}

new Carousel();