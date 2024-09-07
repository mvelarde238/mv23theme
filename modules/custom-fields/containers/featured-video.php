<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$post_types = array('post');
if( USE_PORTFOLIO_CPT ) $post_types[] = 'portfolio';

Container::create( 'featured_video' )
    ->add_location( 'post_type', $post_types, array(
        'context' => 'side',
        'priority' => 'low'
    ))
    ->add_fields(array(
        Field::create( 'checkbox', 'use_featured_video' )->set_text( __('Activate', 'default') )->fancy()->hide_label(),
        Field::create( 'radio', 'featured_video_source', __('Source','default'))
            ->set_orientation( 'horizontal' )
            ->add_options( array(
                'selfhosted' => 'Medios',
                'external' => 'Externo'
            ))->add_dependency( 'use_featured_video' ),
        Field::create( 'embed', 'featured_video_url', 'URL')->add_dependency('featured_video_source','external','=')->add_dependency( 'use_featured_video' ),
        Field::create( 'video', 'featured_video' )->add_dependency('featured_video_source','selfhosted','=')->add_dependency( 'use_featured_video' ),
    ));