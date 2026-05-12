<?php
namespace Core\Builder;

use Core\Builder\Template_Engine\Id;
use Core\Builder\Template_Engine\Style;
use Core\Builder\Template_Engine\Actions;
use Core\Builder\Template_Engine\Classes;
use Core\Builder\Template_Engine\Video;
use Core\Builder\Template_Engine\Scroll_Animations;
use Core\Frontend\Page;
use Core\Builder\Core as Builder_Core;
use Core\Builder\Conditional_Rendering;

class Template_Engine{
	private static $instance = null;
    
    /**
     * Holds a key value reference of Repeater_Group[__type] => Component Class
     * that will be processed by the engine
     */
    private static $components = array();

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Template_Engine();
            Video::filter_oembed_result();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){}

    public function handle( $component_data ){
        $template = '';

        foreach (self::$components as $key => $class_name) {
            if( $key === $component_data['type'] ){
                $template = $class_name::display( $component_data );
            }     
        }

        return $template;
    }

    public static function register_component( $id, $class_name ){
        self::$components[ $id ] = $class_name;
    }

    public static function get_components(){
        return self::$components;
    }

    public static function generate_attributes( $args ){
        $attributes = array();
        
        $id = Id::get_id( $args );
        $style_data = Style::get_data( $args );
        $classes = Classes::get_classes( $args );

        if( $id ) $attributes['id'] = $id;
        if( $classes ) $attributes['class'] = implode(' ', $classes);
        if( $style_data['inline_styles'] ) $attributes['style'] = $style_data['inline_styles'];
        if( SCROLL_ANIMATIONS ){
            if( 
                isset($args['scroll_animations_settings']) && 
                is_array($args['scroll_animations_settings']) && 
                isset($args['scroll_animations_settings']['groups']) && 
                is_array($args['scroll_animations_settings']['groups']) )
            {
                $animations_settings = $args['scroll_animations_settings'];
                $attributes['data-scroll-animations'] = Scroll_Animations::get_animations( $animations_settings );
            }
        } 
        if (isset($args['additional_attributes']) && is_array($args['additional_attributes']) && !empty($args['additional_attributes'])){
            $attributes = array_merge( $attributes, $args['additional_attributes'] );
        }
        
        $attributes_string = '';
        if( !empty($attributes) ){
            foreach( $attributes as $key => $value ){
                if ($value !== null){
                    $attributes_string .= $key.'="'.esc_attr($value).'" ';
                }
            }
        }
        return $attributes_string;
    }

    public static function check_layout( $key, $args ){
        $layout = (isset($args['settings']['layout'])) ? $args['settings']['layout']['key'] : 'layout1';
        $containered_layouts = array('layout2');

        if ($key == 'start' && in_array($layout, $containered_layouts) ) return '<div class="container">';
        if ($key == 'end' && in_array($layout, $containered_layouts) ) return '</div>';
    }

    public static function check_full_width( $key, $args ){
        $layout = (isset($args['settings']['layout'])) ? $args['settings']['layout']['key'] : 'layout1';
        $full_width_layouts = array('layout2','layout3');

        if ($key == 'start' && in_array($layout, $full_width_layouts) ) return '<div class="full-width">';
        if ($key == 'end' && in_array($layout, $full_width_layouts) ) return '</div>';
    }

    public static function component_wrapper( $key, $args ){
        $attributes = self::generate_attributes( $args );

        $html_tag = ( isset($args['html_tag']) && !empty($args['html_tag']) ) ? $args['html_tag'] : 'div';

        ob_start();
        if ($key == 'start'){
            echo self::check_full_width('start', $args);
            echo '<'.$html_tag.' '.$attributes.'>';
            do_action( 'after_component_wrapper_start', $args );
            echo self::check_video_background( $args );
            echo self::check_slider_background( $args );
            echo self::check_layout('start', $args);
            echo self::check_actions('start', $args );
        }
        if ($key == 'end'){  
            echo self::check_actions('end', $args );
            echo self::check_layout('end', $args);
            do_action( 'before_component_wrapper_end', $args );
            echo '</'.$html_tag.'>';
            echo self::check_full_width('end', $args);
        } 
        return ob_get_clean();
    }

    public static function check_components( $args ){
        if( isset($args['components']) ){

            $components = $args['components'];
            // if first component is a container use inner components
            // this is because an inner container is implemented on layout2 structure
            if( isset($components[0]) && $components[0]['type'] === 'container' && isset($components[0]['components']) ){
                $components = $components[0]['components'];
            }

            ob_start();
			foreach ($components as $component) {
                echo Template_Engine::getInstance()->handle( $component );
            }
            return ob_get_clean();
		}
    }

    public static function check_actions( $key, $args ){
        $action = Actions::get_code( $args );

        if( $action && $key == 'start' ) echo $action['start'];
        if( $action && $key == 'end' ) echo $action['end'];
    }

    public static function check_video_background( $args ){
        $video = '';
        
        if ( isset($args['settings']['video_background']) ) {
            $args['settings']['video_background']['video_settings']['classes'] = 'video-background'; 
            $video_data = Video::get_video_data( $args['settings']['video_background'] );
            if($video_data['code']) $video = $video_data['code'];
        }
        
        return $video;
    }

    public static function check_slider_background( $args ){
        $slider_background = '';
        if( isset($args['settings']['slider_background']) ){
            $slider_background_settings = $args['settings']['slider_background'];
            if($slider_background_settings['use'] && !empty($slider_background_settings['shortcode'])){
                $slider_background = '<div id="slider-background" class="slider-background">'.do_shortcode($slider_background_settings['shortcode']).'</div>';
            }
        }
        return $slider_background;
    }

    public static function is_restricted( $args ){
        $is_restricted = false;

        $visibility_settings = $args['visibility_settings'] ?? array();
        $is_restricted_by_rules = Conditional_Rendering::instance()->should_hide_element( $visibility_settings );
        if( $is_restricted_by_rules ) $is_restricted = true;
    
        return $is_restricted;
    }
}