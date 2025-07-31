<?php
namespace Core\Utils\Posts_Data;

class Posts_Views{
    private $version = '1.0.0';
    private static $hooks_added = false;
    private static $registered_posttypes = array();

    public function __construct(){}
    
    public static function implement_in_cpt( $cpt ){
        if(!$cpt) return;

        if( !in_array($cpt, self::$registered_posttypes ) ) self::$registered_posttypes[] = $cpt;

        if (!self::$hooks_added) {
            add_shortcode('post_views', array(__CLASS__, 'shortcode'));
            add_action('template_redirect', array(__CLASS__, 'track_post_views'));
            self::$hooks_added = true;
        }
    }

    public static function shortcode(){
        global $post;
        return self::get_post_views($post->ID);
    }

    public static function track_post_views($post_id) {
        if (!is_single() || !in_array(get_post_type(), self::$registered_posttypes)) return;

        // Evitar contar vistas en solicitudes JSON
        if (wp_is_json_request()) return;
    
        if (empty($post_id)) {
            global $post;
            if (!$post) return;
            $post_id = $post->ID;
        }
    
        $cookie_name = 'post_viewed_' . $post_id;
        
        // Check visualization cookie for this post
        if (!isset($_COOKIE[$cookie_name])) {
            self::set_post_views($post_id);
            // Set a 1 hour cookie
            setcookie($cookie_name, '1', time() + 3600, "/");
        }
    }
    
    private static function set_post_views($postID) {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count == ''){
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }

    private static function get_post_views($postID){
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count == ''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }
}