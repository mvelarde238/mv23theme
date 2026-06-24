<?php
namespace Core\Frontend;

use Core\Theme_Options\UF_Container\Posts_Subscription;
use Core\Posttype\Post;
use Core\Utils\Helpers;

class Post_Card {
    public function __construct() {}

    public static function get_link_target($post) {
        $post_format = get_post_meta( $post->ID, 'post_format', true );
        if ( $post_format === 'link' && get_post_meta( $post->ID, 'post_link_new_tab', true ) ) {
            return '_blank';
        }
        return '';
    }

    public static function get_permalink($post) {
        return get_permalink($post->ID);
    }

    public static function get_excerpt($post, $length = null) {
        $excerpt = ( !empty($post->post_excerpt) ) ? $post->post_excerpt : $post->post_content;
        $excerpt = strip_tags($excerpt);
        $excerpt = apply_filters('filter_post_card_excerpt', $excerpt, $post);

        if ($length && strlen($excerpt) > $length) {
            // truncate the excerpt
            $excerptCut = substr($excerpt, 0, $length);
            $endPoint = strrpos($excerptCut, ' ');
            $excerpt = $endPoint ? substr($excerptCut, 0, $endPoint) : substr($excerptCut, 0);
            $excerpt .= '...';
        }

        return do_shortcode(wpautop($excerpt));
    }

    public static function get_thumbnail($post, $size = 'thumbnail') {
        $thumbnail = get_the_post_thumbnail_url($post->ID, $size);
        if (!$thumbnail) {
            // Fallback to a default image if no thumbnail is set
            $thumbnail = get_stylesheet_directory_uri() . '/assets/images/nothumb.jpg';
        }
        return $thumbnail;
    }

    public static function build_attributes($post, $args) {
        $attributes = array();

        $attributes[] = 'data-id="' . esc_attr($post->ID) . '"';
        
        $postcard_settings = $args['postcard_settings'] ?? array();
        if (!empty($postcard_settings['on_click_post'])) {
            $attributes[] = 'data-action="' . esc_attr($postcard_settings['on_click_post']) . '"';
        }
        if (!empty($postcard_settings['on_click_scroll_to'])) {
            $attributes[] = 'data-scroll-to="' . esc_attr($postcard_settings['on_click_scroll_to']) . '"';
        }
        return implode(' ', $attributes);
    }

    public static function get_main_taxonomy_terms($post) {
        $terms = array();
        $posttype = $post->post_type;
        $terms = get_the_terms($post->ID, Helpers::get_main_taxonomy($posttype));
        return $terms;
    }

    public static function get_secondary_taxonomy_terms($post) {
        $terms = array();
        $posttype = $post->post_type;
        $terms = get_the_terms($post->ID, Helpers::get_secondary_taxonomy($posttype));
        return $terms;
    }

    public static function get_featured_video($post) {
        $featured_video = Post::getInstance()->get_featured_video($post);
        return $featured_video;
    }

    public static function display_terms($terms, $separator = '') {
        if (is_array($terms) && count($terms) > 0) {
            $output = [];
            foreach ($terms as $term) {
                $background_color = get_term_meta($term->term_id, 'background_color', true);
                $style = ($background_color) ? ' style="background-color:' . $background_color . ';"' : ' ';

                $output[] = '<a class="' . esc_attr($term->slug) . '" href="' . esc_url(get_term_link($term)) . '"' . $style . '>' . esc_html($term->name) . '</a>';
            }
            return implode($separator, $output);
        }
        return '';
    }

    public static function display_date($post, $date_format =  null) {
        if (is_null($date_format)) {
            $date_format = get_option('date_format');
        }
        if (empty($date_format)) {
            $date_format = 'F j, Y'; // Default format
        }
        return sprintf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', $post->ID) . '" itemprop="datePublished">' . get_the_time($date_format, $post->ID) . '</time>');
    }

    public static function display_actions( $post, $document_link, $actions = array() ) {
        $actions_html = '';
        $subscribe_to_continue = Posts_Subscription::is_active($post);

        $preview_file_url = Posts_Subscription::maybe_obfuscate_link( $subscribe_to_continue, $document_link, 'subscribe-to-preview', $post->ID);

        $download_file_url = Posts_Subscription::maybe_obfuscate_link( $subscribe_to_continue, $document_link, 'subscribe-to-download', $post->ID);

        foreach ($actions as $action) {
            if ($action == 'post_likes') {
                $actions_html .= '<a href="#" class="like-count-js" title="' . __('Like', 'mv23theme') . '"><i class="bi bi-heart"></i> ' . do_shortcode('[post_likes]') . '</a>';

            } elseif ($action == 'post_previsualizations') {
                $actions_html .= '<a href="' . esc_url($preview_file_url) . '"';
                if (!$subscribe_to_continue) {
                    $actions_html .= ' class="previsualization-count-js" data-glightbox data-description="' . esc_attr($post->post_title) . '"';
                }
                $actions_html .= ' title="' . __('Preview', 'mv23theme') . '"><i class="bi bi-arrows-angle-expand"></i> ' . do_shortcode('[post_previsualizations]') . '</a>';
                
            } elseif ($action == 'post_downloads') {
                $actions_html .= '<a href="' . esc_url($download_file_url) . '"';
                if (!$subscribe_to_continue) {
                    $actions_html .= ' class="download-count-js" download';
                }
                $actions_html .= ' title="' . __('Download', 'mv23theme') . '"><i class="bi bi-download"></i> ' . do_shortcode('[post_downloads]') . '</a>';
            }
        }

        return $actions_html;
    }
}