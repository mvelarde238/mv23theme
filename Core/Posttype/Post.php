<?php
namespace Core\Posttype;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Post {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Post();
        }
        return self::$instance;
    }

    private function __construct(){}

    public function add_meta_boxes(){
        $post_types = array('post');
        if( USE_PORTFOLIO_CPT ) $post_types[] = 'portfolio';

        // POSTS FORMAT
        Container::create( 'post_format' )
            ->add_location( 'post_type', $post_types, array(
                'context' => 'side'
            ))
            ->add_fields(array(
                Field::create( 'select', 'post_format' )
	                ->set_input_type( 'radio' )
                    ->hide_label()
	                ->add_options(array(
	                	''   => __('Standard','mv23theme'),
	                	'link'   => __('Link','mv23theme')
                    )),
                Field::create( 'text', 'post_link' )->add_dependency('post_format','link','=')
            ));

        // FEATURED VIDEO
        Container::create( 'featured_video' )
            ->add_location( 'post_type', $post_types, array(
                'context' => 'side',
                'priority' => 'low'
            ))
            ->add_fields(array(
                Field::create( 'checkbox', 'use_featured_video' )->set_text( __('Activate', 'mv23theme') )->fancy()->hide_label(),
                Field::create( 'radio', 'featured_video_source', __('Source','mv23theme'))
                    ->set_orientation( 'horizontal' )
                    ->add_options( array(
                        'selfhosted' => 'Medios',
                        'external' => 'Externo'
                    ))->add_dependency( 'use_featured_video' ),
                Field::create( 'embed', 'featured_video_url', 'URL')->add_dependency('featured_video_source','external','=')->add_dependency( 'use_featured_video' ),
                Field::create( 'video', 'featured_video' )->add_dependency('featured_video_source','selfhosted','=')->add_dependency( 'use_featured_video' ),
            ));
    }
}