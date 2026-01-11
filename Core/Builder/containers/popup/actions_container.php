<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'actions_container' ) 
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'repeater', 'actions' )->set_add_text( __('Add Action','mv23theme') )->add_group( 'Action', array(
            'fields' => array(
                Field::create( 'select', 'trigger' )->add_options( array(
                    'click' => 'Click'
                ))->set_width(25),
                Field::create( 'select', 'action' )->add_options( array(
                    '' => 'Seleccionar',
                    'open-page' => 'Abrir nueva página',
                    'open-image-popup' => 'Mostrar archivo en pop up',
                    'open-video-popup' => 'Mostrar video en pop up',
                    'toggle-box' => 'Mostrar / Ocultar Sección',
                    'offcanvas-element' => 'Mostrar Off-Canvas Element'
                ))->set_width(75),
    
                Field::create( 'complex', 'enlace' )->hide_label()->rows_layout()->add_fields(array(
                    Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
                        'interna' => 'Página Interna',
                        'externa' => 'Página Externa',
                    )),
                    Field::create( 'wp_object', 'post', 'URL Interna' )->add( 'posts' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
                    Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
                    Field::create( 'checkbox', 'new_tab' )->set_text( 'Abrir en una nueva ventana.' )->hide_label(),
                ))->add_dependency('action','open-page','='),
    
                Field::create( 'complex', 'image_popup' )->hide_label()->rows_layout()->add_fields(array(
                    Field::create( 'file', 'internal_image')->hide_label()
                ))->add_dependency('action','open-image-popup','='),
                
                Field::create( 'complex', 'video_popup' )->hide_label()->rows_layout()->add_fields(array(
                    Field::create( 'radio', 'video_source','Seleccione el origen del video:')->set_orientation( 'horizontal' )->add_options( array(
                        'selfhosted' => 'Medios',
                        'external' => 'Externo'
                    ))->set_width(50),
                    Field::create( 'embed', 'external_video')->add_dependency('video_source','external','=')->set_width(50),
                    Field::create( 'video', 'internal_video')->add_dependency('video_source','selfhosted','=')->set_width(50),
                ))->add_dependency('action','open-video-popup','='),
    
                Field::create( 'complex', 'toggle_box_settings' )->hide_label()->rows_layout()->add_fields(array(
                    Field::create( 'text', 'selector' )->set_width( 50 )->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                        ->set_description( 'Selector -ID o CLASS- de la sección que se va mostrar / ocultar, usar solo minúsculas y guiones ( - )' ),
                    Field::create( 'checkbox', 'scroll_to_box' )->set_text( 'Scroll page to box.' ),
                ))->add_dependency('action','toggle-box','='),
    
                Field::create( 'complex', 'offcanvas_elements_settings' )->hide_label()->rows_layout()->add_fields(array(
                    Field::create( 'select', 'id' )->add_posts( 'offcanvas_element' )->hide_label()
                ))->add_dependency('action','offcanvas-element','='),
            ))
        )
    ));