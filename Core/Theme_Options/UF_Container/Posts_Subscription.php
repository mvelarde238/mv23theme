<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Utils\Subscription\Subscribe_To_Continue;
use Core\Utils\Subscription\Subscribe_To_Preview;
use Core\Utils\Subscription\Subscribe_To_Download;
use Core\Utils\Subscription\Subscribe_To_Redirect;

class Posts_Subscription{

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Posts_Subscription();
        }
        return self::$instance;
    }

    private function __construct(){
        /**
         * TODO:
         * Add forminator support
         */

        Subscribe_To_Continue::getInstance()->set_cf7_shortcode(
            get_option('posts_subscription_settings')['form_shortcode']
        );
        Subscribe_To_Preview::init();
        Subscribe_To_Download::init();
        Subscribe_To_Redirect::init();
    }

    public static function init(){
        # post types
        $post_types = array();
        $excluded = array();
		foreach( get_post_types( array('public'=>true, 'exclude_from_search'=>false), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}
			$post_types[ $id ] = __( $post_type->labels->name );
		}

        Container::create( 'posts_subscription_container' ) 
            ->set_title( __('Posts Subscription','mv23theme') )
            ->add_location( 'options', 'theme-options', array(
                'context' => 'side'
            ))
            ->add_fields(array(
                Field::create( 'complex', 'posts_subscription_settings' )->add_fields(array(
                    Field::create( 'checkbox', 'activate' )
                        ->set_text( __('Activate Subscription','mv23theme') )
                        ->hide_label(),
                    Field::create( 'multiselect', 'post_types', __( 'Post Types', 'mv23theme' ) )
                        ->required()
                        ->add_options( $post_types )
                        ->set_orientation( 'horizontal' )
                        ->set_input_type( 'checkbox' )
                        ->add_dependency( 'activate' ),
                    Field::create( 'select', 'subscription_form_source', __( 'Subscription Form Source', 'mv23theme' ) )
                        ->add_options( array(
                            'cf7' => __('Contact Form 7','mv23theme')
                            // 'forminator' => __('Forminator Form','mv23theme'), // TODO: forminator support
                            // 'custom' => __('Custom Form','mv23theme') // TODO: custom form
                        ) )
                        ->add_dependency( 'activate' ),
                    Field::create( 'text', 'form_shortcode', __( 'Form Shortcode', 'mv23theme' ) )
                        ->required()
                        ->set_placeholder( '[shortcode id="123"]' )
                        ->add_dependency( 'activate' )
                ))->hide_label()
            ));
    }

    public function filter_post_card_permalink($permalink, $post) {
        $posts_subscription_settings = get_option('posts_subscription_settings');

        $post_types = $posts_subscription_settings['post_types'];
        if( !is_array($post_types) || !in_array($post->post_type, $post_types) ) {
            return $permalink;
        }

        if( !self::post_subscription_is_active($post->ID) ) {
            return $permalink;
        }

        $permalink = add_query_arg(array(
            'id' => $post->ID,
            'action' => 'subscribe-to-redirect'
        ), home_url('/'));
        
        return $permalink;
    }

    public static function post_subscription_is_active( $post_id ){
        $is_active = false;

        $override_global_posts_subscription = get_post_meta($post_id, 'override_global_posts_subscription', true);

        if($override_global_posts_subscription) {
            $posts_subscription = get_post_meta($post_id, 'post_subscription', true);
            if($posts_subscription) $is_active = true;
        } else {
            if(POSTS_SUBSCRIPTION) $is_active = true;
        }
        
        return $is_active;
    }
}