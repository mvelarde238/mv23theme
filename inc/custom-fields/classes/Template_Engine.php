<?php
namespace Theme_Custom_Fields;

use Theme_Custom_Fields\Template_Engine\Id;
use Theme_Custom_Fields\Template_Engine\Style;
use Theme_Custom_Fields\Template_Engine\Actions;
use Theme_Custom_Fields\Template_Engine\Classes;
use Theme_Custom_Fields\Template_Engine\Animation;
use Theme_Custom_Fields\Template_Engine\Video;
use Theme_Custom_Fields\Template_Engine\Scroll_Animations;

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

    public function handle( $type, $args ){
        $template = '';

        foreach (self::$components as $key => $class_name) {
            if( $key === $type ) $template = $class_name::display( $args );
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
        
        $id = Id::get_attribute( $args );
        $style_data = Style::get_data( $args );

        // this need to be done before classes checking:
        if( $style_data['attribute'] ) $args = self::check_components_padding( $args, $style_data );

        $class = Classes::get_attribute( $args );
        $animationAttrs = Animation::get_attributes( $args );

        if( $id ) $attributes[] = $id;
        if( $class ) $attributes[] = $class;
        if( $style_data['attribute'] ) $attributes[] = $style_data['attribute'];
        if( $animationAttrs ) $attributes[] = $animationAttrs;
        if( SCROLL_ANIMATIONS ) $attributes[] = Scroll_Animations::get_attributes( $args );
        if (isset($args['additional_attributes']) && is_array($args['additional_attributes']) && !empty($args['additional_attributes'])){
            $attributes = array_merge( $attributes, $args['additional_attributes'] );
        }
        
        return ( !empty($attributes) ) ? implode(' ', $attributes ) : '';
    }

    public static function check_components_padding( $args, $style_data ){
        $no_padding_components = array('columnas-internas','columnas-simples','columnas','grid-de-items','column','grid__item','fila','separador','components-wrapper','modulos', 'content'); // content is a group inside Carrusel

        if ( 
            isset( $args['__type'] ) && !in_array($args['__type'], $no_padding_components) &&
            ( $style_data['background'] || $style_data['borders'] || $style_data['box_shadow'])
        ){
            $args['additional_classes'][] = 'add-padding';
        } 

        $need_spacing = array('columnas-internas','columnas-simples','columnas','grid-de-items','column','grid__item','fila','separador','components-wrapper','content');

        if ( /* theses components dosnt need padding but need component spacing-separator when they have background, border or shadow */
            isset( $args['__type'] ) && in_array($args['__type'], $need_spacing ) &&
            ( $style_data['background'] || $style_data['borders'] || $style_data['box_shadow'])
        ){
            $args['additional_classes'][] = 'componente';
        }
        return $args;
    }

    public static function check_layout( $key, $args ){
        $layout = (isset($args['layout'])) ? $args['layout'] : 'layout1';
        // 'layout4' for extended columns:
        $containered_layouts = array('layout2','layout4');

        if ($key == 'start' && in_array($layout, $containered_layouts) ) return '<div class="container">';
        if ($key == 'end' && in_array($layout, $containered_layouts) ) return '</div>';
    }

    public static function component_wrapper( $key, $args ){
        $attributes = self::generate_attributes( $args );

        ob_start();
        if ($key == 'start'){
            echo '<div '.$attributes.'>';
            echo self::check_video_background( $args );
            echo self::check_layout('start', $args);
        }
        if ($key == 'end'){  
            echo self::check_actions( $args );
            echo self::check_layout('end', $args);
            echo '</div>';
        } 
        return ob_get_clean();
    }

    public static function check_actions( $args ){
        return Actions::get_code( $args );
    }

    public static function check_video_background( $args ){
        $video = '';
        $video_data = Video::get_video_data($args);
        if($video_data['code']){
            $code = $video_data['code'];
            $styles = $video_data['styles'];
            $video = '<div class="video-background"'; 
            if( $styles ) $video .= ' style="'.$styles.'"';
            $video .= '>'.$code.'</div>';
        } 
        return $video;
    }
}