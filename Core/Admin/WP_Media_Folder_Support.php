<?php
namespace Core\Admin;

use Ultimate_Fields\Field;
use Core\Builder\Template_Engine\Video as Video_Template_Engine;

class WP_Media_Folder_Support {

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WP_Media_Folder_Support();
        }
        return self::$instance;
    }

    private function __construct(){}

    public function add_wp_media_folder_source($sources) {
        $sources['wp-media'] = __('Select Folder', 'mv23theme');
        return $sources;
    }

    public function add_wp_media_folder_content_tab_fields($fields) {

        $fields[] = Field::create( 'select', 'wp_media_folder' )
            ->add_terms( 'wpmf-category' )
            ->fancy()
            ->add_dependency('source', 'wp-media', '=');

        $fields[] = Field::create( 'message', 'wp_media_folder_message', __('WP Media Folder', 'mv23theme') )
            ->set_description('<a href="'.admin_url().'upload.php" target="_blank">'.__('Create a new WP Media Folder', 'mv23theme').'</a>')
            ->add_dependency('source', 'wp-media', '=');

        // Field::create( 'checkbox', 'autoinsert' )->set_text( '¿Autoinsertar las imágenes agregadas a la galerîa?' ); // dosnt work, the shortcode needs the attachments id's
        // Field::create( 'select', 'orderby', 'Ordenar por')->add_options( array(
        //     'custom' => 'Personalizado',
        //     'rand' => 'Random',
        //     'title' => 'Tìtulo',
        //     'date' => 'Fecha'
        // ))->add_dependency('../wp_media_folder','0','!=');
        // Field::create( 'select', 'order', 'Orden')->add_options( array(
        //     'DESC' => 'Descendente',
        //     'ASC' => 'Ascendente',
        // ));

        return $fields;
    }

    public function stop_gallery_process_if_wp_media_folder_is_empty($args) {
        $source = $args['source'] ?? '';
        if ($source === 'wp-media') {
            $wp_media_folder = $args['wp_media_folder'] ?? 0;
            if (empty($wp_media_folder)) {
                // Stop the gallery process if the WP Media Folder is empty
                return false;
            }
        }
    }

    public function filter_gallery_settings($shortcode_attributes, $args) {
        $source = $args['source'] ?? '';
        if ($source === 'wp-media') {
            $wp_media_folder = $args['wp_media_folder'] ?? 0;
            if ($wp_media_folder) {
                $shortcode_attributes['wpmf_folder_id'] = $wp_media_folder;
                $shortcode_attributes['wpmf_autoinsert'] = '1';
            }
        }
        return $shortcode_attributes;
    }

    public function filter_gallery_attachments($attachments, $shortcode_attributes, $args) {
        $source = $args['source'] ?? '';
        if ($source === 'wp-media') {
            $wp_media_folder = $args['wp_media_folder'] ?? 0;
            if ($wp_media_folder) {
                // Get attachments from the specified WP Media Folder
                $attachments = get_posts(array(
                    'post_type' => 'attachment',
                    'post_status' => 'inherit',
                    'posts_per_page' => -1,
                    'meta_key' => 'wpmf_order',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'wpmf-category',
                            'field'    => 'term_id',
                            'terms'    => array( $wp_media_folder ),
                            'include_children' => true
                        ),
                    ),
                ));
                // Extract attachment IDs
                $attachments = wp_list_pluck($attachments, 'ID');
            }
        }
        return $attachments;
    }

    public function filter_gallery_attachment_data($attachment_data, $attachment_id, $gallery_settings, $type) {
        $image_formats = array('image/jpeg', 'image/png', 'image/gif');

        if( in_array($type, $image_formats) ) {
            // WP MEDIA FOLDER PLUGIN: REMOTE VIDEO SUPPORT
            $remote_video = get_post_meta($attachment_id,'wpmf_remote_video_link',true);

            if ( !empty($remote_video) ) {
                $is_remote_video = true;
                $attachment_data['is_remote_video'] = $is_remote_video;

                $attachment_data['type'] = 'video';

                $url = $remote_video;
                $attachment_data['url'] = $url;

                $video_args = array(
                    'video_source' => 'external',
                    'external_url' => $url,
                    'video_settings' => array()
                );
                if( !empty($gallery_settings['size_styles'])){
                    $video_args['video_settings']['styles'] = $gallery_settings['size_styles'];
                }

                $video_data = Video_Template_Engine::get_video_data( $video_args );
                if( !empty($video_data['code']) ) $attachment_data['output'] = $video_data['code'];
            }
        }

        return $attachment_data;
    }

    public function filter_gallery_attachment_link_attributes($link_attrs, $attachment_id, $gallery_settings, $the_attachment_data) {
        if($gallery_settings['link'] != 'none') {

            // href: support for _wpmf_gallery_custom_image_link
            if( $gallery_settings['link'] === 'custom' ){
                $wpmf_link = get_post_meta($attachment_id, '_wpmf_gallery_custom_image_link', true);
                $attachment_link = $wpmf_link ? $wpmf_link : '#';
                $link_attrs['href'] = $attachment_link;
            }
            
            // target: support for _gallery_link_target
            if( $gallery_settings['link'] == 'custom' || $gallery_settings['link'] == 'post' ){
                $attachment_target = get_post_meta($attachment_id, '_gallery_link_target', true);
                if( $attachment_target && $attachment_target == '_blank' ){
                    $link_attrs['target'] = "_blank";
                    $link_attrs['rel'] = "noopener noreferrer";
                } 
            }

            // data-type
            $attachment_type = $the_attachment_data['type'];
            $is_remote_video = (isset($the_attachment_data['is_remote_video'])) ? true : false;
            if( $attachment_type === 'video' && $is_remote_video ) {
                $link_attrs['data-type'] = 'video';
            }
        }

        return $link_attrs;
    }
}