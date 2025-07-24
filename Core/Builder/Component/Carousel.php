<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Blocks_Layout;
use Ultimate_Fields\Container\Repeater_Group;
use Core\Builder\Blocks_Layout_Settings;

class Carousel extends Component {

    public function __construct() {
		parent::__construct(
			'carousel',
			__( 'Carousel', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-slides';
    }

    // public static function get_layout(){
    //     return 'grid';
    // }

    public static function get_title_template() {
		$template = '<% if ( items.length ){ %>
            Show <%= items.length %> items in <%= items_in_desktop %> columns
        <% } else { %>
            This component is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
        $content_group = new Repeater_Group( 'Content' );
		$content_group->set_title( __('Content','mv23theme') )
            ->set_edit_mode('popup')
            ->set_title_template( '<% if ( blocks_layout.length ){ %>
                    <% if ( blocks_layout[0][0].__type == "text_editor" ){ %>
                        <%= blocks_layout[0][0].content.replace(/<[^>]+>/ig, "") %>
                    <% } else { %>
                        <%= "First item type: "+blocks_layout[0][0].__type %>
                    <% } %>
                <% } else { %>
                    This item is empty
                <% } %>') 
			->add_fields(array(
                Field::create( 'tab', __('Content','mv23theme') ),
                Blocks_Layout::the_field(),
                Blocks_Layout_Settings::the_field(),
                Field::create( 'tab', __('Settings','mv23theme') ),
                Field::create( 'common_settings_control', 'settings' )->set_container( 'common_settings_container' ),
                Field::create( 'common_settings_control', 'actions_settings' )->set_container( 'actions_container' )
            ));

        $content_fields =  array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'repeater', 'items' )
                ->hide_label()
                ->set_add_text(__('Add Item','mv23theme'))
                ->set_chooser_type( 'dropdown' )
                ->add_group( $content_group )
                ->add_group('Item', array(
                    'title' => __('Image','mv23theme'),
                    'fields' => array(
                        Field::create( 'image', 'imagen' )->set_width( 25 ),
                        Field::create( 'complex', 'enlace' )->rows_layout()->add_fields(array(
                            Field::create( 'radio', 'url_type', __('Select the content to open on click:', 'mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                                '' => __('None', 'mv23theme'),
                                'interna' => __('Internal Page', 'mv23theme'),
                                'externa' => __('External Page', 'mv23theme'),
                                'popup' => __('Show image in a PopUp', 'mv23theme'),
                            )),
                            Field::create( 'wp_object', 'post', __('Internal Page', 'mv23theme') )->add( 'posts' )->set_button_text( __('Select Page', 'mv23theme') )->add_dependency('url_type','interna','='),
                            Field::create( 'text', 'url', __('External URL', 'mv23theme') )->add_dependency('url_type','externa','='),
                            Field::create( 'checkbox', 'new_tab' )->set_text( __('Open in a new window.', 'mv23theme') )->hide_label()->add_dependency('url_type','externa','='),
                        ))->set_width( 75 )
                    )
                ))
        );

        $settings_fields_1 =  array(
            Field::create( 'tab', 'Settings' ),
            Field::create( 'image_select', 'carousel_type', __('Carousel type','mv23theme') )
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
            Field::create( 'complex', 'marquee_settings', __('Marquee Settings', 'mv23theme') )->merge()->add_fields(array(
                Field::create( 'number', 'marquee_speed', __('Marquee Animation Speed', 'mv23theme') )
                    ->set_default_value(18)
                    ->set_suffix(__('Seconds', 'mv23theme'))
                    ->set_attr( 'style', 'flex-grow: initial;' ),
                Field::create( 'color', 'fade_color', __('Fade Color', 'mv23theme') )
                    ->set_default_value('#ffffff')
                    ->set_attr( 'style', 'flex-grow: initial;' )
            ))->add_dependency('carousel_type', 'marquee', '='),

            Field::create( 'complex', '_controls_wrapper', 'Show Controls' )->merge()->add_fields(array(
                Field::create( 'checkbox', 'show_controls' )->hide_label()->set_text(__('Show arrows','mv23theme'))->set_width( 30 ),
                Field::create( 'select', 'controls_position' )->add_options( array(
                    'center' => __('Center','mv23theme'),
                    'bottom' => __('Bottom','mv23theme'),
                    'top' => __('Top','mv23theme'),
                ))->add_dependency('show_controls')
                ->hide_label()
                ->set_prefix( 'Controls position:' )
                ->set_width( 30 )
            ))->add_dependency('carousel_type', 'marquee', '!='),
            Field::create( 'complex', '_nav_wrapper', 'Show Nav' )->merge()->add_fields(array(
                Field::create( 'checkbox', 'show_nav' )->hide_label()->set_text(__('Show page indicators','mv23theme'))->set_width( 30 ),
                Field::create( 'select', 'nav_position' )->add_options( array(
                    'bottom' => __('Bottom','mv23theme'),
                    'top' => __('Top','mv23theme'),
                ))->add_dependency('show_nav')
                ->hide_label()
                ->set_prefix( 'Nav position:' )
                ->set_width( 30 )
            ))->add_dependency('carousel_type', 'marquee', '!='),
            Field::create( 'complex', '_autoplay_wrapper', 'Autoplay' )->merge()->add_fields(array(
                Field::create( 'checkbox', 'autoplay' )->set_text(__('Start Automatically','mv23theme'))->hide_label()->set_width( 20 ),
                Field::create( 'number', 'autoplay_timeout' )
                    ->set_prefix('Timeout:')
                    ->set_placeholder('5000')
                    ->set_suffix( 'ms' )
                    ->hide_label()
                    ->add_dependency( 'autoplay' )
                    ->set_width( 20 )
                // Field::create( 'checkbox', 'autoplay_hover_pause' )->set_text(__('Pause on Hover','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency( 'autoplay' )
                //     ->set_width( 20 ),
                // Field::create( 'checkbox', 'prevent_action' )->set_text(__('Prevent action when running','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency( 'autoplay' )
                //     ->set_width( 20 )
            ))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'complex', '_mode_wrapper', 'Mode' )->merge()->add_fields(array(
                Field::create( 'select', 'mode' )->add_options( array(
                    'carousel' => 'Carrusel',
                    'gallery' => 'Fade',
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
                    ->set_width( 20 )
                // Field::create( 'checkbox', 'disable_rewind' )->set_text(__('Disable rewind','mv23theme'))
                //     ->hide_label()
                //     ->add_dependency('mode','carousel','=')
                //     ->set_width( 20 )
            ))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'checkbox', 'auto_height' )->set_text(__('Activate Auto Height','mv23theme'))->add_dependency('carousel_type', 'marquee', '!='),
            Field::create( 'checkbox', 'touch' )->set_text(__('Activate Touch','mv23theme'))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'complex', '_items_wrapper', __('Columns', 'mv23theme') )->merge()->add_fields(array(
                Field::create( 'text', 'items_in_desktop', __('Columns on desktop', 'mv23theme') )->set_default_value( '4' )->set_width( 25 ),
                Field::create( 'text', 'items_in_laptop', __('Columns on laptop', 'mv23theme') )->set_default_value( '3' )->set_width( 25 ),
                Field::create( 'text', 'items_in_tablet', __('Columns on tablet', 'mv23theme') )->set_default_value( '2' )->set_width( 25 ),
                Field::create( 'text', 'items_in_mobile', __('Columns on mobile', 'mv23theme') )->set_default_value( '2' )->set_width( 25 )
            ))->add_dependency('carousel_type', 'marquee', '!='),

            Field::create( 'complex', '_gutter_wrapper', __('Space between items', 'mv23theme') )->merge()->add_fields(array(
                Field::create( 'number', 'gutter_in_desktop', __('Gap on desktop', 'mv23theme') )->set_default_value( '0' )->set_width( 25 ),
                Field::create( 'number', 'gutter_in_laptop', __('Gap on laptop', 'mv23theme') )->set_default_value( '0' )->set_width( 25 ),
                Field::create( 'number', 'gutter_in_tablet', __('Gap on tablet', 'mv23theme') )->set_default_value( '0' )->set_width( 25 ),
                Field::create( 'number', 'gutter_in_mobile', __('Gap on mobile', 'mv23theme') )->set_default_value( '0' )->set_width( 25 )
            )),

            Field::create( 'complex', '_images_wrapper', __('Images Size', 'mv23theme') )->merge()->add_fields(array(
                Field::create( 'select', 'imgs_height' )->add_options( array(
                    'auto' => __('Auto', 'mv23theme'),
                    'custom' => __('Custom', 'mv23theme'),
                ))->hide_label()->set_width( 25 ),
                Field::create( 'number', 'img_max_height', __('Max height in pixels', 'mv23theme') )
                    ->set_default_value( '60' )
                    ->add_dependency('imgs_height','custom','=')
                    ->set_width( 25 ),
                )),

            Field::create('text', 'slider_uid', __('Slider UID', 'mv23theme'))
                ->set_description(__('This is used to identify the slider in the JS code. If you leave it empty, a random UID will be generated.', 'mv23theme'))
                ->set_attr( 'style', 'flex-grow: initial;' )
        );

		return array_merge(
            $content_fields,
            $settings_fields_1,
            $settings_fields_2
        );
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
        $args['additional_attributes'] = array();

        $items = $args['items'];
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

        $items_in_mobile = $args['items_in_mobile'];
        $items_in_tablet = $args['items_in_tablet'];
        $items_in_laptop = $args['items_in_laptop'];
        $items_in_desktop = $args['items_in_desktop'];

        $img_styles = '';
        $imgs_height = $args['imgs_height'] ?? 'auto';
        if($imgs_height == 'custom') {
            $img_max_height = $args['img_max_height'] . 'px';
            $img_styles = 'style="max-height:'.$img_max_height.'"'; 
        }

        $gutter_in_mobile = $args['gutter_in_mobile'] ?? 0;
        $gutter_in_tablet = $args['gutter_in_tablet'] ?? 0;
        $gutter_in_laptop = $args['gutter_in_laptop'] ?? 0;
        $gutter_in_desktop = $args['gutter_in_desktop'] ?? 0;

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
            $fade_color = $args['fade_color'] ?? '#ffffff';
            ?>
            <div class="marquee" data-speed="<?=$marquee_speed?>" style="--fade-color:<?=$fade_color?>;--d-gap:<?=$gutter_in_desktop?>px; --t-gap:<?=$gutter_in_tablet?>px; --m-gap:<?=$gutter_in_mobile?>px;">
            <div class="marquee-track">
        <?php endif; ?>

            <?php for ($i=0; $i < count($items); $i++) { 
                $type = $items[$i]['__type'];

                if( $type == 'item' ){
                    $imagen = $items[$i]['imagen'];
                    $bgi = wp_get_attachment_url($imagen);
                
                    $link = NULL;
                    $enlace = $items[$i]['enlace'];
                    switch ($enlace['url_type']) {
                        case 'externa':
                            $link = $enlace['url'];
                            break;
                        
                        case 'interna':
                            $link = get_permalink( str_replace('post_','',$enlace['post']) );
                            break;
                        
                        case 'popup':
                            $link = $bgi;
                            break;
                    }

                    $lightbox_class = ( $enlace['url_type'] == 'popup' ) ? 'zoom' : '';
                    ?>
                    <div class="carousel__item carousel__item--image">
                        <div class="component">
                            <img src="<?=$bgi?>" <?=$img_styles?> alt="Carrusel Item">
                            <?php if ($link != NULL): ?>
                                <?php $target = ($enlace['new_tab'] == 1) ? '_blank' : '';  ?>
                                <a class="carousel__item__link <?=$lightbox_class?>" href="<?=$link?>" target="<?=$target?>"></a>
                                <?php endif ?>
                            </div>
                    </div>
                    <?php
                }
            
                if( $type == 'content' ){
                    $blocks_layout = $items[$i]['blocks_layout'];
                    if (is_array($blocks_layout) && count($blocks_layout) > 0) :
                        $item_attributes = Template_Engine::generate_attributes( $items[$i] );    
                        echo '<div class="carousel__item carousel__item--content">';
                        echo '<div '.$item_attributes.'>';
                        echo Blocks_Layout::the_content($blocks_layout, array('component_args' => $items[$i]));
                        echo Template_Engine::check_actions( $items[$i] );
                        echo '</div>';
                        echo '</div>';
                    endif;
                }
            
            }; ?>

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