<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Theme_Options\UF_Container\Posts_Subscription;
use Core\Theme_Options\UF_Container\Track_Posts_Data;

class Document {

    private static $instance = null;

    protected $post_type_slug;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function __construct(){
        $this->post_type_slug = 'document';
    }

    protected function get_post_type_slug() {
        return $this->post_type_slug;
    }

	public function register_posttype(){
        $post_type_slug = $this->post_type_slug;

        // add a filter to modify the post type setting
        $document_settings = apply_filters('filter_' . $post_type_slug . '_post_type_settings', array(
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'thumbnail', 'revisions'),
            'menu_icon' => 'dashicons-media-document',
        ));

		$document = new CPT(
			$post_type_slug, 
			$document_settings
		);

		$document->register_taxonomy(array(
			'taxonomy_name' => $post_type_slug . '-cat',
			'singular' => __('Document Category', 'mv23theme'),
			'plural' => __('Document Categories', 'mv23theme'),
			'show_ui' => true,
			'slug' => $post_type_slug . '-cat'
		));

        $document->register_taxonomy(array(
			'taxonomy_name' => $post_type_slug . '-tag',
			'hierarchical' => false,
			'show_ui' => true,
			'singular' => 'Document Tag',
			'plural' => 'Document Tags',
			'slug' => $post_type_slug . '-tag'
		));

        $document->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title', 'mv23theme'),
            'file' => __('File', 'mv23theme'),
            $post_type_slug . '-data' => __('Tracked Data', 'mv23theme'),
            $post_type_slug . '-cat' => __('Category', 'mv23theme'),
            'date' => __('Date', 'mv23theme')
        ));

        $document->populate_column($post_type_slug . '-data', array($this, 'populate_document_data_column'));
        $document->populate_column('file', array($this, 'populate_document_file_column'));
	}

    public function add_meta_boxes() {
        $post_type_slug = $this->post_type_slug;
        
        Container::create( 'document_settings' )
            ->add_location( 'post_type', $post_type_slug, array( 
                // 'context' => 'side' 
            ))
            ->add_fields(array(
                Field::create( 'radio', 'content_type', __('Content Type','mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                    'file'=>__('File','mv23theme'),
                    'url'=>__('Url','mv23theme')
                )),
                Field::create( 'text', 'file_url', __('URL','mv23theme'))->add_dependency('content_type','url','='),
                Field::create( 'file', 'file', __('File','mv23theme'))->set_output_type( 'id' )->add_dependency('content_type','file','='),
                Field::create( 'wysiwyg', 'description', __('Description','mv23theme')),
                Field::create( 'complex', 'metrics', __('Metrics','mv23theme') )->merge()->add_fields(array(
                    Field::create( 'number', 'post_views_count', __('Views','mv23theme'))->set_width(25),
                    Field::create( 'number', 'download_count', __('Downloads','mv23theme'))->set_width(25),
                    Field::create( 'number', 'previsualization_count', __('Previews','mv23theme'))->set_width(25),
                    Field::create( 'number', 'post_likes_count', __('Likes','mv23theme'))->set_width(25)
                )),
                Field::create( 'complex', '_post_subscription_wrapper', __('Post Subscription', 'mv23theme') )->merge()->add_fields(array(
                    Field::create( 'checkbox', 'override_global_posts_subscription')
                        ->hide_label()
                        ->set_text(__('Override Global Posts Subscription', 'mv23theme'))
                        ->fancy()
                        ->set_width(50),
                    Field::create( 'checkbox', 'post_subscription')
                        ->hide_label()
                        ->set_text(__('Enable Post Subscription', 'mv23theme'))
                        ->fancy()
                        ->add_dependency('override_global_posts_subscription')
                        ->set_width(50)
                ))
            ));
    }

    public function populate_document_file_column( $column_name, $post ){
        $content_type = get_post_meta($post->ID, 'content_type', true);
        $file_url = '';

        if($content_type == 'file') {
            $file_id = get_post_meta($post->ID, 'file', true);
            if($file_id) $file_url = wp_get_attachment_url($file_id);   
        } else {
            $file_url = get_post_meta($post->ID, 'file_url', true);
        }

        $remote_video_data = self::get_remote_video_data($post->ID);
        $is_remote_video = ($remote_video_data['link']) ? true : false;
        if( $is_remote_video ){
            $file_url = $remote_video_data['link'];
        }
        
        if($file_url) {
            echo '<a href="'.esc_url($file_url).'" target="_blank">'.__('View File', 'mv23theme').'</a>';
        } else {
            echo __('No file attached', 'mv23theme');
        }
    }

    public function populate_document_data_column( $column_name, $post ){

        if( TRACK_POSTS_DATA ){
            echo Track_Posts_Data::get_data_for_admin_column($post);
        }

        if ( POSTS_SUBSCRIPTION ){
            $subscribe_to_continue = Posts_Subscription::post_subscription_is_active($post->ID);
            if($subscribe_to_continue) echo sprintf(__('Subscribe to continue: %s', 'mv23theme'), __('Yes', 'mv23theme')) . '<br>';
            else echo sprintf(__('Subscribe to continue: %s', 'mv23theme'), __('No', 'mv23theme')) . '<br>';
        }
    }

    public static function get_document_file_url( $post_id ){
        $content_type = get_post_meta($post_id, 'content_type', true);
        $file_url = '';

        if($content_type == 'file') {
            $file_id = get_post_meta($post_id, 'file', true);
            if($file_id) $file_url = wp_get_attachment_url($file_id);   
        } else {
            $file_url = get_post_meta($post_id, 'file_url', true);
        }

        return $file_url;
    }

    // WP MEDIA FOLDER PLUGIN: REMOTE VIDEO SUPPORT
    public static function get_remote_video_data( $post_id ){
        $remote_video_data = array(
            'link' => '',
            'source' => 'youtube'
        );

        $content_type = get_post_meta($post_id, 'content_type', true);

        if($content_type == 'file') {
            $file_id = get_post_meta($post_id, 'file', true);
            if($file_id) {
                $remote_video = get_post_meta($file_id,'wpmf_remote_video_link',true);
                $remote_video_data['link'] = $remote_video;
                $remote_video_data['icon'] = 'bi-youtube';
            }
        }

        return $remote_video_data;
    }

    public static function get_file_size( $post_id ){
        $file_size = null;
        $content_type = get_post_meta($post_id, 'content_type', true);
        if($content_type == 'file') {
            $file_id = get_post_meta($post_id, 'file', true);
            if($file_id) {
                $file_path = get_attached_file( $file_id );
                if(file_exists($file_path)) {
                    $file_size_bytes = filesize( $file_path );
                    $file_size_kb = round( $file_size_bytes / 1024, 2 );
                    $file_size_mb = round( $file_size_bytes / 1024 / 1024, 2 );
                    $file_size = (  $file_size_mb > 1 ) ? intval($file_size_mb). ' MB' : intval($file_size_kb) . ' KB';
                }
            }
        }
        return $file_size;
    }

    public static function get_thumbnail($thumb_url, $ext, $file_url) {
        $thumbnail = $thumb_url;

        $images_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg', 'video');
        if( in_array($ext, $images_extensions) ){
            $thumbnail = $file_url;
        }

        return $thumbnail;
    }

    public static function can_be_previewed($ext) {
        $images_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg', 'video');
        return in_array($ext, array_merge($images_extensions, array('pdf')));
    }
}