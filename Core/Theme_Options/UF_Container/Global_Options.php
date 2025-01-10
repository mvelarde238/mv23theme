<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Global_Options{
    public static function init(){
        Container::create( 'global_options' ) 
            ->set_title( __('Global Options','mv23theme') )
            ->add_location( 'options', 'theme-options' )
            ->add_fields(array(
                Field::create( 'wp_object', 'theme_footer_post', __('Footer','mv23theme') )->add( 'posts', 'post_type=footer' ),
        
                Field::create( 'complex', 'google_map_wrapper', __('Google Maps','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'checkbox', 'activate_gm', __('Activate Google Maps','mv23theme') )->set_text(__('Activate','mv23theme'))->set_width(50),
                    Field::create( 'multiselect', 'gm_services', __('Google Map Services','mv23theme'))->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
                        'places' => 'Places'
                        ))->add_dependency('activate_gm')->set_width(50),
                )),

                Field::create( 'complex', 'masonry_wrapper', __('Masonry Gallery','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'checkbox', 'activate_masonry', __('Activate Masonry','mv23theme') )->set_text(__('Activate','mv23theme')),
                )),
        
                Field::create( 'multiselect', 'show_editor_in', __('The theme hides the text editor on pages and products, show it in the following places:','mv23theme'))->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
                    'page' => __('Pages','mv23theme'),
                    'product' => __('Products','mv23theme')
                )),
                Field::create( 'complex', 'scroll_animations', __('Activate Scroll Animations','mv23theme') )->add_fields(array(
                    Field::create( 'checkbox', 'activate' )->set_text(__('Activate','mv23theme'))->hide_label()->set_width( 50 ),
                    Field::create( 'checkbox', 'activate_indicators' )->set_text(__('Activate indicators', 'mv23theme'))->hide_label()->set_width( 50 )->add_dependency('activate'),
                )),
                Field::create( 'checkbox', 'disable_comments_styles', __('Deactive theme styles in comments','mv23theme') )->set_text(__('Deactivate','mv23theme')),
            ));
    }
}