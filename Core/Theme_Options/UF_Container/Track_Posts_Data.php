<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Utils\Posts_Data\Posts_Views;
use Core\Utils\Posts_Data\Posts_Likes;
use Core\Utils\Posts_Data\Previsualization_Count;
use Core\Utils\Posts_Data\Downloads_Count;
use Core\Builder\Core;

class Track_Posts_Data{

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Track_Posts_Data();
        }
        return self::$instance;
    }

    private function __construct(){
        $settings = get_option('track_posts_data_settings', array());
        if( is_array($settings) ) {
            $post_types = $settings['post_types'] ?? array();
            if( !empty($post_types) ) {
                foreach( $post_types as $post_type ) {
                    Posts_Views::implement_in_cpt($post_type);
                    Posts_Likes::implement_in_cpt($post_type);
                    Previsualization_Count::implement_in_cpt($post_type);
                    Downloads_Count::implement_in_cpt($post_type);
                }
            }
        }
    }

    public static function init(){
        Container::create( 'track_posts_data_container' ) 
            ->set_title( __('Track Posts Data','mv23theme') )
            ->add_location( 'options', 'theme-options', array(
                'context' => 'side'
            ))
            ->add_fields(array(
                Field::create( 'complex', 'track_posts_data_settings' )->add_fields(array(
                    Field::create( 'message', 'description' )
                        ->set_description( __('Activate this option to track post views, likes, previsualization and downloads.','mv23theme') )
                        ->hide_label(),
                    Field::create( 'checkbox', 'activate' )
                        ->set_text( __('Activate Tracking','mv23theme') )
                        ->hide_label(),
                    Field::create( 'multiselect', 'post_types', __( 'Post Types', 'mv23theme' ) )
                        ->required()
                        ->set_options_callback( array( Core::class, 'get_post_types' ) )
                        ->set_orientation( 'horizontal' )
                        ->set_input_type( 'checkbox' )
                        ->add_dependency( 'activate' )
                ))->hide_label()
            ));
    }

    public static function get_data_for_admin_column($post){
        $data = '';

        $post_views_count = get_post_meta($post->ID,'post_views_count',true);
        // translators: %s: Views Count
        if($post_views_count) $data .= sprintf(__('Views: %s', 'mv23theme'), $post_views_count) . '<br>';

        $likes_count = get_post_meta($post->ID,'post_likes_count',true);
        // translators: %s: Likes Count
        if($likes_count) $data .= sprintf(__('Likes: %s', 'mv23theme'), $likes_count) . '<br>';

        $previsualization_count = get_post_meta($post->ID,'previsualization_count',true);
	    // translators: %s: Previews Count
	    if($previsualization_count) $data .= sprintf(__('Previews: %s', 'mv23theme'), $previsualization_count) . '<br>';

        $download_count = get_post_meta($post->ID,'download_count',true);
	    // translators: %s: Downloads Count
	    if($download_count) $data .= sprintf(__('Downloads: %s', 'mv23theme'), $download_count) . '<br>';

        return $data;
    }

    public static function is_active( $post ){
        $is_active = false;

        $track_posts_data_settings = get_option('track_posts_data_settings');
        if($track_posts_data_settings && $track_posts_data_settings['activate']) {
            if( in_array($post->post_type, $track_posts_data_settings['post_types']) ){
                $is_active = true;
            }
        }

        return $is_active;
    }
}