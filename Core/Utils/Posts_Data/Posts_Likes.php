<?php
namespace Core\Utils\Posts_Data;

class Posts_Likes{
    private $version = '1.1.0';
    private static $hooks_added = false;
    private static $registered_posttypes = array();

    public function __construct(){}
    
    public static function implement_in_cpt( $cpt ){
        if(!$cpt) return;

        if( !in_array($cpt, self::$registered_posttypes ) ) self::$registered_posttypes[] = $cpt;

        self::add_hooks();
    }

    private static function add_hooks() {
        if (self::$hooks_added) return;

        add_shortcode('post_likes', array(__CLASS__, 'shortcode'));
        add_action('wp_ajax_nopriv_post_like', array(__CLASS__, 'ajax_post_like'));
        add_action('wp_ajax_post_like', array(__CLASS__, 'ajax_post_like'));

        self::$hooks_added = true;
    }

    public static function shortcode(){
        global $post;
        $count = self::get_post_likes($post->ID);
        return '<span class="post-likes-count">'.$count.'</span>';
    }

    private static function set_post_likes($postID) {
        $count_key = 'post_likes_count';
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

    private static function get_post_likes($postID){
        $count_key = 'post_likes_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count == ''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }

    public static function ajax_post_like() {
        if (!wp_verify_nonce( $_POST['nonce'], "global-nonce")) {
            wp_send_json_error("No naughty business please.");
        }
    
        if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
            wp_send_json_error('Invalid post ID');
        }

        $post_id = intval($_POST['post_id']);
        $post_type = get_post_type($post_id);

        if(!in_array($post_type, self::$registered_posttypes)){
            wp_send_json_error(array(
                'message' => 'Post type not supported for likes.',
                'post_id' => $post_id,
                'post_type' => $post_type
            ));
        }

        $cookie_name = 'post_liked_' . $post_id;

        if (!isset($_COOKIE[$cookie_name])) {
            self::set_post_likes($post_id);
            setcookie($cookie_name, '1', time() + 3600, "/");

            $likes = self::get_post_likes($post_id);
            wp_send_json_success($likes);
        } else {
            wp_send_json_error("Too many likes.");
        }    
    }
}