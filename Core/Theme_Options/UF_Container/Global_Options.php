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

                Field::create( 'complex', 'masonry_wrapper', __('Masonry Gallery','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'checkbox', 'activate_masonry', __('Activate Masonry','mv23theme') )->set_text(__('Activate','mv23theme')),
                )),
        
                Field::create( 'multiselect', 'show_editor_in', __('The theme hides the text editor on pages and products, show it in the following places:','mv23theme'))->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
                    'page' => __('Pages','mv23theme'),
                    'product' => __('Products','mv23theme')
                )),
                Field::create( 'checkbox', 'disable_comments_styles', __('Deactive theme styles in comments','mv23theme') )->set_text(__('Deactivate','mv23theme')),
            ));
    }
}