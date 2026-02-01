<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'actions_container' ) 
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'select', 'trigger' )->add_options( array(
            'click' => 'Click'
        )),
        Field::create( 'select', 'action' )->add_options( array(
            '' => __('Select Action','mv23theme'),
            'open-page' => __('Open new page','mv23theme'),
            'open-image-popup' => __('Show file in pop up','mv23theme'),
            'open-video-popup' => __('Show video in pop up','mv23theme'),
            'toggle-box' => __('Show / Hide Section','mv23theme'),
            'offcanvas-element' => __('Show Off-Canvas Element','mv23theme')
        )),
    
        Field::create( 'complex', 'link' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'radio', 'url_type', __('Source','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'internal' => __('Internal Page','mv23theme'),
                    'external' => __('External Page','mv23theme'),
                )),
            Field::create( 'wp_object', 'post', __('Internal URL','mv23theme') )
                ->add( 'posts' )
                ->set_button_text( __('Select the page','mv23theme') )
                ->add_dependency('url_type','internal','='),
            Field::create( 'text', 'url', __('External URL','mv23theme') )
                ->add_dependency('url_type','external','='),
            Field::create( 'checkbox', 'new_tab', '' )
                ->set_text( __('Open in a new window.','mv23theme') ),
        ))->add_dependency('action','open-page','='),
    
        Field::create( 'complex', 'image_popup' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'file', 'internal_image')
        ))->add_dependency('action','open-image-popup','='),
                
        Field::create( 'complex', 'video_popup' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'radio', 'video_source', __('Select the video source:','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'selfhosted' => __('Media','mv23theme'),
                    'external' => __('External','mv23theme')
                ))->set_width(50),
            Field::create( 'embed', 'external_video')->add_dependency('video_source','external','=')->set_width(50),
            Field::create( 'video', 'internal_video')->add_dependency('video_source','selfhosted','=')->set_width(50),
        ))->add_dependency('action','open-video-popup','='),
    
        Field::create( 'complex', 'toggle_box_settings' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'text', 'selector' )
                ->set_width( 50 )
                ->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description( __('Internal selector -ID or CLASS- of the section to show/hide, use only lowercase and hyphens ( - )','mv23theme') ),
            Field::create( 'checkbox', 'scroll_to_box' )
                ->set_text( __('Scroll page to box.','mv23theme') ),
        ))->add_dependency('action','toggle-box','='),
    
        Field::create( 'complex', 'offcanvas_elements_settings' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'select', 'id', '' )
                ->add_posts( 'offcanvas_element' )
        ))->add_dependency('action','offcanvas-element','=')
    ));