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
        
                Field::create( 'checkbox', 'disable_comments_styles', __('Deactive theme styles in comments','mv23theme') )->set_text(__('Deactivate','mv23theme')),
            ));
    }
}