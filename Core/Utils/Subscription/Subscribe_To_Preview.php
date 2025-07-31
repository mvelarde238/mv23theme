<?php
namespace Core\Utils\Subscription;

use Core\Utils\Subscription\Subscribe_To_Continue;
use Core\Posttype\Document;

class Subscribe_To_Preview extends Subscribe_To_Continue {
    private $version = '1.1.0';
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_filter( 'subscribe-to-preview_filter_response', array( $this, 'filter_response'));
    }
    
    public static function init() {
        self::getInstance();
    }

    public static function filter_response($response){
        $post_id = $response['data_id'];
        $file_id = get_post_meta($post_id, 'file', true);

        if( empty($file_id) ){
            $response['success'] = false;
            $response['errorType'] = 'fileNotFound';
        } else {
            $file_url = wp_get_attachment_url( $file_id );

            $remote_video_data = Document::get_remote_video_data($post_id);
            $is_remote_video = ($remote_video_data['link']) ? true : false;
            if( $is_remote_video ){
                $file_url = $remote_video_data['link'];
            }

            if($file_url){
                $response['success'] = true;
                $response['file_url'] = $file_url;
            } else {
                $response['success'] = false;
                $response['errorType'] = 'fileUrlNotFound';
            }
        }    
        return $response;
    }
}