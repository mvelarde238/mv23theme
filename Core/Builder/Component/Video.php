<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Template_Engine\Video as Template_Engine_Video;

class Video extends Component {

    public function __construct() {
		parent::__construct(
			'video',
			__( 'Video', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-format-video';
    }

    public static function get_builder_data() {
        return array(
			'connected_gjs_type' => 'video2'
		);
    }

	public static function get_fields() {
        $control_css = 'width:50%;min-width:auto;flex-grow:initial;';

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'radio', 'video_source', __('Source','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'selfhosted' => __('Media','mv23theme'),
                    'external' => __('External','mv23theme')
                )),

            Field::create( 'embed', 'external_url', 'URL')->add_dependency('video_source','external','='),
            Field::create( 'video', 'video' )->add_dependency('video_source','selfhosted','='),

            Field::create('checkbox', 'expand_on_click', __('Expand on click','mv23theme'))->fancy()
                ->add_dependency('video_source','selfhosted','=')
                // ->add_dependency('video','','NOT_NULL')
                ->add_dependency_group()
                ->add_dependency('video_source','external','=')
                ->add_dependency('external_url','','!=')
                ->set_text( __( 'Show the video in a popup.', 'mv23theme' ) ),
            
            Field::create( 'tab', __('Video Settings','mv23theme') ),
            Field::create( 'complex', 'video_settings' )->hide_label()->add_fields(array(
                    Field::create( 'checkbox', 'controls', __('Controls','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                    Field::create( 'checkbox', 'autoplay', __('AutoPlay','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                    Field::create( 'checkbox', 'muted', __('Muted','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                    Field::create( 'checkbox', 'loop', __('Loop','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                    Field::create( 'color', 'bgc', __('Background color','mv23theme') )->set_default_value('#000000'),
                    Field::create( 'number', 'opacity', __('Opacity','mv23theme') )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )
                ))
                ->add_dependency('video_source','selfhosted','=')
                // ->add_dependency('video','','NOT_NULL')
                ->add_dependency_group()
                ->add_dependency('video_source','external','=')
                ->add_dependency('external_url','','!='),
    
            Field::create( 'tab', __('Aspect Ratio','mv23theme') ),
            Field::create( 'image_select', 'aspect_ratio', __('Aspect Ratio') )->hide_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-default.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
                'custom'  => array(
                    'label' => 'custom',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-custom.png'
                ),
            )),
            Field::create( 'text', 'custom_aspect_ratio' )
                ->set_validation_rule('^(\d+(\.\d+)?)(\s*\/\s*(\d+(\.\d+)?))?$')
                ->add_dependency( 'aspect_ratio', 'custom' )
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
		$args['additional_classes'][] = 'media';
        
        $video_data = Template_Engine_Video::get_video_data($args);
        if( empty($video_data['code']) ) return;
    
        $video_source = ( isset($args['video_source']) ) ? $args['video_source'] : 'selfhosted';

        $expand_on_click = ( isset($args['expand_on_click']) && $args['expand_on_click'] ) ? true : false;
        if( $expand_on_click ){
            $video_key = ( $video_source === 'selfhosted' ) ? 'internal' : 'external';
            $args['actions_settings'] = array();
            $args['actions_settings']['actions'] = array(
                array(
                    'trigger' => 'click',
                    'action' => 'open-video-popup',
                    'video_popup' => array(
                        'video_source' => $video_source,
                        $video_key.'_video' => $video_data['url']
                    )
                )
            );
        }

        $args['additional_classes'][] = $video_source;

        $attachment = ($video_source === 'selfhosted') ? get_post( $args['video']['videos'][0] ): null;
        $caption = ($attachment) ? $attachment->post_excerpt : null;
        
        $attributes = Template_Engine::generate_attributes( $args );
        ob_start();
        echo '<div '.$attributes.'>';
        do_action( 'after_component_wrapper_start', $args );
        echo Template_Engine::check_layout('start', $args);
        echo $video_data['code'];
        if( $caption ) echo '<p class="media-caption">'.esc_html($caption).'</p>';
        echo Template_Engine::check_actions( $args );
        echo Template_Engine::check_layout('end', $args);
        do_action( 'before_component_wrapper_end', $args );
        echo '</div>';
        return ob_get_clean();
	}
}

new Video();