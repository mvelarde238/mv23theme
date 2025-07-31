<?php
namespace Core\Utils\Subscription;

use Core\Utils\Subscription\Subscribe_To_Continue;

class Subscribe_To_Download extends Subscribe_To_Continue {
    private $version = '1.1.0';
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'init', array($this, 'serve_file') );
        add_action( 'wp_enqueue_scripts', function() {
            wp_localize_script('contact-form-7', 'cf7_nonce_data', array(
                'nonce' => wp_create_nonce('secure_download')
            ));
        });
        add_filter( 'subscribe-to-download_filter_response', array( $this, 'filter_response'));
    }

    public static function init() {
        self::getInstance();
    }

    public static function filter_response($response){
        $file_id = get_post_meta($response['data_id'], 'file', true);

        if( empty($file_id) ){
            $response['success'] = false;
            $response['errorType'] = 'fileNotFound';
        } else {
            // Generate a temporal url to download the file
            $query_args = array( 'file_id' => $file_id );
            $file_url = add_query_arg( $query_args, site_url());

            $response['success'] = true;
            $response['file_url'] = $file_url;
        }    
        return $response;
    }

    public static function serve_file() {
        if (isset($_GET['file_id']) && isset($_GET['nonce'])) {
            $file_ID = intval($_GET['file_id']);

            // Validar nonce
            if ( !wp_verify_nonce($_GET['nonce'], 'secure_download')) {
                wp_die('No autorizado');
            }

            $file_path = get_attached_file($file_ID);
            if (!$file_path || !file_exists($file_path)) {
                wp_die('Archivo no encontrado');
            }

            // Forzar la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));

            readfile($file_path);
            exit;
        }
    }
}