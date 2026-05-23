<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'actions_container' ) 
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'select', 'trigger' )->add_options( array(
            'click' => __('On click','mv23theme'),
        )),

        Field::create( 'select', 'action' )->add_options( array(
            '' => __('No action','mv23theme'),
            'open-page' => __('Open new page','mv23theme'),
            'open-image-popup' => __('Show file in pop up','mv23theme'),
            'open-video-popup' => __('Show video in pop up','mv23theme'),
            'toggle-box' => __('Show / Hide Section','mv23theme'),
            'offcanvas-element' => __('Show Off-Canvas Element','mv23theme'),
            'next-post' => __('Go to next post','mv23theme'),
            'previous-post' => __('Go to previous post','mv23theme'),
            'interact-slider' => __('Interact with a slider','mv23theme'),
        )),
    
        Field::create( 'complex', 'link' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'radio', 'url_type', __('Source','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'internal' => __('Select a page','mv23theme'),
                    'external' => __('Enter URL','mv23theme'),
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
            Field::create( 'file', 'internal_image', __('Select an image','mv23theme') )
        ))->add_dependency('action','open-image-popup','='),
                
        Field::create( 'complex', 'video_popup' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'radio', 'video_source', __('Select the video source:','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'selfhosted' => __('Media','mv23theme'),
                    'external' => __('External','mv23theme')
                ))->set_width(50),
            Field::create( 'embed', 'external_video', __('Enter the video URL','mv23theme'))->add_dependency('video_source','external','=')->set_width(50),
            Field::create( 'video', 'internal_video', __('Select a video','mv23theme'))->add_dependency('video_source','selfhosted','=')->set_width(50),
        ))->add_dependency('action','open-video-popup','='),
    
        Field::create( 'complex', 'toggle_box_settings' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'text', 'selector' )
                ->set_width( 50 )
                ->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description( __('Internal selector -ID or CLASS- of the section to show/hide, use only lowercase and hyphens ( - )','mv23theme') ),
            Field::create( 'checkbox', 'scroll_to_box' )
                ->set_text( __('Scroll page to box.','mv23theme') ),
        ))->add_dependency('action','toggle-box','='),
    
        Field::create( 'complex', 'offcanvas_elements_settings', __('Select an offcanvas element','mv23theme') )->rows_layout()->add_fields(array(
            Field::create( 'select', 'id' )->add_posts( 'offcanvas_element' )->hide_label()
        ))->add_dependency('action','offcanvas-element','='),

        Field::create( 'complex', 'interact_slider_settings' )->hide_label()->rows_layout()->add_fields(array(
            Field::create( 'text', 'slider_uid', __('Slider UID', 'mv23theme') )
                ->set_description( __('Enter the Slider UID to interact with.','mv23theme') ),
            Field::create( 'select', 'interaction_type', __('Interaction Type','mv23theme') )
                ->add_options( array(
                    'next' => __('Go to next slide','mv23theme'),
                    'previous' => __('Go to previous slide','mv23theme'),
                    'go_to_slide' => __('Go to specific slide','mv23theme'),
                )),
            Field::create( 'number', 'slide_number', __('Slide Number','mv23theme') )
                ->set_description( __('Enter the slide number to go to (starting from 1).','mv23theme') )
                ->add_dependency('interaction_type','go_to_slide','='),
            Field::create( 'checkbox', 'scroll_to_slider' )
                ->set_text( __('Scroll to slider when interacting.','mv23theme') )
        ))->add_dependency('action','interact-slider','='),

        Field::create( 'radio', 'clickable_area', __('Clickable Area','mv23theme') )
            ->add_options( array(
                'inner_content' => __('Inner content','mv23theme'),
                'entire_component' => __('Entire component','mv23theme'),
                'extra_layer' => __('Extra layer over the component','mv23theme'),
            ))
            ->set_orientation( 'horizontal' )
            ->add_dependency('trigger','click','=')
            ->add_dependency('action','','!=')
    ));