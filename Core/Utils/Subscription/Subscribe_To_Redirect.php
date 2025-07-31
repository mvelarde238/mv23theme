<?php
namespace Core\Utils\Subscription;

use Core\Utils\Subscription\Subscribe_To_Continue;

class Subscribe_To_Redirect extends Subscribe_To_Continue {
    private $version = '1.1.0';
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_filter( 'subscribe-to-redirect_filter_response', array( $this, 'filter_response'));
    }
    
    public static function init() {
        self::getInstance();
    }

    public static function filter_response($response){
        $redirect_url = get_permalink($response['data_id']);
        $response['success'] = true;
        $response['redirect_url'] = $redirect_url;

        return $response;
    }
}