<?php
/**
 * Cleanup functionality of this theme.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Core
 * @subpackage Core/CleanUp
 */

namespace Core\CleanUp;

use \Core\Includes\Theme as Theme;

class CleanUp extends Theme {

    public function __construct() { 
        parent::__construct();
    }

    public function disable_emoji_dequeue_script() {
        remove_action( 'wp_head' , 'print_emoji_detection_script' , 7 );
        remove_action( 'wp_print_styles' , 'print_emoji_styles' );
        remove_action( 'admin_print_scripts' , 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles' , 'print_emoji_styles' );
    }

    public function clean_up_header() {
        remove_action( 'wp_head' , 'rsd_link' );
        remove_action( 'wp_head' , 'wp_generator' );
        remove_action( 'wp_head' , 'feed_links' , 2 );
        remove_action( 'wp_head' , 'feed_links_extra' , 3 );
        remove_action( 'wp_head' , 'index_rel_link' );
        remove_action( 'wp_head' , 'wlwmanifest_link' );
        remove_action( 'wp_head' , 'start_post_rel_link' , 10 , 0 );
        remove_action( 'wp_head' , 'parent_post_rel_link' , 10 , 0 );
        remove_action( 'wp_head' , 'adjacent_posts_rel_link' , 10 , 0 );
        remove_action( 'wp_head' , 'adjacent_posts_rel_link_wp_head' , 10 , 0 );
        remove_action( 'wp_head' , 'wp_shortlink_wp_head' , 10 , 0 );
        remove_action( 'wp_head' , 'print_emoji_detection_script' , 7 );
        remove_action( 'wp_head' , 'wp_resource_hints' , 2 );
        remove_action( 'wp_head' , 'rel_canonical' );
    }

    public function remove_wpembed_scripts() {
        wp_deregister_script( 'wp-embed' );
    }

    public function remove_wp_widget_recent_comments_style() {
        if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
            remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
        }
    }

    public function remove_recent_comments_style() {
        global $wp_widget_factory;
        if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
            remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
        }
    }

    public function clean_gallery_style($css) {
        return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
    }

    public function clean_excerpt_more($more) {
        return '';
    }

    public function clean_head_styles() {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wc-block-style' );
        wp_dequeue_style( 'classic-theme-styles' );
        wp_dequeue_style( 'global-styles' );
    }
}
