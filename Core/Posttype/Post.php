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
        $post_types = apply_filters( 'filter_post_meta_box_post_types', $post_types );

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
                Field::create( 'message', 'post_format_link_info', __('Link Post Format Info', 'mv23theme') )
                    ->set_description( __('When the "Link" format is selected, the post card will link to an external URL instead of its single page. The URL can be set in the "Link Destination" field.', 'mv23theme') )
                    ->add_dependency('post_format','link','=')
                    ->hide_label(),
                Field::create( 'select', 'post_link_type', __('Destination', 'mv23theme') )
	                ->set_default_value( 'external' )
	                ->add_options( array(
	                    'external' => __('External URL', 'mv23theme'),
	                    'internal' => __('Internal Page', 'mv23theme'),
	                    'file'     => __('File', 'mv23theme'),
	                ))->add_dependency('post_format','link','='),
                Field::create( 'text', 'post_link', __('URL', 'mv23theme') )
                    ->hide_label()
	                ->add_dependency('post_format','link','=')
	                ->add_dependency('post_link_type','external','='),
                Field::create( 'wp_object', 'post_link_post', __('Page', 'mv23theme') )
                    ->hide_label()
	                ->set_button_text( __('Select Page', 'mv23theme') )
	                ->add_dependency('post_format','link','=')
	                ->add_dependency('post_link_type','internal','='),
                Field::create( 'file', 'post_link_file', __('File', 'mv23theme') )
                    ->hide_label()
	                ->add_dependency('post_format','link','=')
	                ->add_dependency('post_link_type','file','='),
                Field::create( 'checkbox', 'post_link_new_tab', __('Open in a new window', 'mv23theme') )
	                ->set_text( __('Enable', 'mv23theme') )
	                ->add_dependency('post_format','link','=')
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