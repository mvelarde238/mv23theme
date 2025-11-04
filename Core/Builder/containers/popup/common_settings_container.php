<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'common_settings_container' ) 
    // ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'complex', 'main_attributes', __('Attributes html', 'mv23theme') )->add_fields(array(
            Field::create( 'text', 'id', __('ID','mv23theme') )
                ->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description( __('Section identifier ID, use only lowercase letters and hyphens (-)', 'mv23theme') )
                ->set_width( 50 ),
            Field::create( 'text', 'class', __('Class','mv23theme') )
                ->set_description( __('Section class attribute, use only lowercase letters and hyphens (-)', 'mv23theme' ) )
                ->set_width( 50 )
        ))->merge(),
        Field::create( 'complex', 'layout', __('Layout','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width(50),
            Field::create( 'select', 'key', __('Layout','mv23theme'))->add_options( array(
                'layout1' => __('Standard Layout','mv23theme'),
                'layout2' => __('Extended background / Centered content','mv23theme'),
                'layout3' => __('Fully extended','mv23theme')
            ))->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'helpers', __('Helpers','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width(10),
            Field::create( 'multiselect', 'list')
                ->hide_label()
                ->set_input_type( 'checkbox' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'overflow-scroll' => __('Overflow scroll','mv23theme'),
                    'overflow-hidden' => __('Overflow hidden','mv23theme'),
                    'hide-br' => __('Hide line breaks on tablets and mobile devices','mv23theme'),
                    'hide-br-tablet' => __('Hide line breaks on tablets only','mv23theme'),
                    'hide-br-mobile' => __('Hide line breaks on mobile devices only','mv23theme'),
                    'extend-bg-to-left' => __('Extend background to left','mv23theme'),
                    'extend-bg-to-right' => __('Extend background to right','mv23theme'),
                    'full-height' => __('Full Height','mv23theme')
                ))->add_dependency('use')->set_width( 80 )
        )),
        Field::create( 'complex', 'video_background', __('Video background', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),

            Field::create( 'radio', 'video_source', __('Source','mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                'selfhosted' => __('Media','mv23theme'),
                'external' => __('External','mv23theme')
                ))->add_dependency('use')->set_width(30),

                Field::create( 'video', 'video' )->add_dependency('use')->add_dependency('video_source','selfhosted','=')->set_width(30),
            Field::create( 'embed', 'external_url', 'URL')->add_dependency('use')->add_dependency('video_source','external','=')->set_width(30),

            Field::create( 'complex', 'video_settings', __('Video Settings','mv23theme') )->add_fields(array(
                Field::create( 'color', 'bgc', __('Background color','mv23theme') )->set_width(10),
                Field::create( 'checkbox', 'controls', __('Controls','mv23theme') )->set_text( __('Activate','mv23theme') )->set_width(10),
                Field::create( 'checkbox', 'autoplay', __('AutoPlay','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                Field::create( 'checkbox', 'muted', __('Muted','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                Field::create( 'checkbox', 'loop', __('Loop','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                Field::create( 'number', 'opacity', __('Opacity','mv23theme') )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width(10)
            ))->hide_label()->add_dependency('use')
        )),
        Field::create( 'complex', 'slider_background', __('Slider background', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'shortcode')->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'visibility', __('Visibility','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width(50),
            Field::create('select', 'key', __('Visibility','mv23theme') )->add_options(array(
                'all' => __('Visible to all users','mv23theme'),
                'user_is_logged_in' => __('Visible for registered users','mv23theme'),
                'user_is_not_logged_in' => __('Visible for non-registered users','mv23theme'),
                'is_private' => __('Visible for admin users','mv23theme')
            ))->add_dependency('use')->set_width(50)
        )),
        Field::create( 'complex', 'responsive', __('Responsive','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'hide_on_mobile', __('hide_on_mobile','mv23theme') )->fancy()->set_width(30),
            Field::create( 'checkbox', 'hide_on_tablet', __('hide_on_tablet','mv23theme') )->fancy()->set_width(30),
            Field::create( 'checkbox', 'hide_on_desktop', __('hide_on_desktop','mv23theme') )->fancy()->set_width(30)
        ))
    ));  