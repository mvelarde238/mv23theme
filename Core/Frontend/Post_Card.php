<?php
namespace Core\Frontend;

use Core\Builder\Template_Engine\Video;
use Core\Theme_Options\UF_Container\Posts_Subscription;

class Post_Card {
    public function __construct() {}

    public static function get_permalink($post) {
        $permalink = get_permalink($post->ID);

        if( $post->post_type == 'post' || $post->post_type == 'portfolio' ) {
            $post_format = get_post_meta( $post->ID, 'post_format', true );
            $post_link = get_post_meta( $post->ID, 'post_link', true );
            if( $post_format == 'link' && !empty($post_link) ) $permalink = $post_link;
        }

        return apply_filters('filter_post_card_permalink', $permalink, $post);
    }

    public static function get_excerpt($post, $length = null) {
        $excerpt = ( !empty($post->post_excerpt) ) ? $post->post_excerpt : $post->post_content;
        $excerpt = strip_tags($excerpt);
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

        if (!empty($args['on_click_post'])) {
            $attributes[] = 'data-action="' . esc_attr($args['on_click_post']) . '"';
        }
        if (!empty($args['on_click_scroll_to'])) {
            $attributes[] = 'data-scroll-to="' . esc_attr($args['on_click_scroll_to']) . '"';
        }
        return implode(' ', $attributes);
    }

    public static function get_main_taxonomy($post) {
        $taxonomy = 'category'; // Default taxonomy
        if ($post->post_type == 'document') {
            $taxonomy = 'document-cat';
        } elseif ($post->post_type == 'portfolio') {
            $taxonomy = 'portfolio-cat';
        }
        return get_the_terms($post->ID, $taxonomy);
    }

    public static function get_secondary_taxonomy($post) {
        $taxonomy = 'post_tag'; // Default taxonomy
        if ($post->post_type == 'document') {
            $taxonomy = 'document-tag';
        } elseif ($post->post_type == 'portfolio') {
            $taxonomy = 'portfolio-tag';
        }
        return get_the_terms($post->ID, $taxonomy);
    }

    public static function get_featured_video($post) {
        $featured_video = null;
        $use_featured_video = get_post_meta($post->ID, 'use_featured_video', true);
        if ($use_featured_video) {
            $featured_video_source = get_post_meta($post->ID, 'featured_video_source', true);
            $video_meta_data = ($featured_video_source == 'selfhosted') ? 'featured_video' : 'featured_video_url';
            $video_data = get_post_meta($post->ID, $video_meta_data, true);

            $video_settings = array(
                'video_source' => $featured_video_source,
                'classes' => 'video-background',
                'controls' => false,
                'muted' => true,
                'autoplay' => true,
                'loop' => true,
                'bgc' => '#000'
            );

            if ($featured_video_source == 'selfhosted') {
                $video_settings['video'] = $video_data;
            }
            if ($featured_video_source == 'external') {
                $video_settings['external_url'] = $video_data;
            }

            $video_data = Video::get_video_data($video_settings);
            if( !empty($video_data['code']) ) {
                $featured_video = $video_data['code'];
            }
        }
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

    public static function display_actions( $post, $actions = array(), $document_link ) {
        $actions_html = '';
        $subscribe_to_continue = Posts_Subscription::post_subscription_is_active($post->ID);

        foreach ($actions as $action) {
            if ($action == 'post_likes') {
                $actions_html .= '<a href="#" class="like-count-js" title="' . __('Like', 'mv23theme') . '"><i class="bi bi-heart"></i> ' . do_shortcode('[post_likes]') . '</a>';

            } elseif ($action == 'post_previsualizations') {
                if ($subscribe_to_continue) {
                    $document_link = add_query_arg(array(
                        'id' => $post->ID,
                        'action' => 'subscribe-to-preview'
                    ), home_url('/'));
                }
                $actions_html .= '<a href="' . esc_url($document_link) . '"';
                if (!$subscribe_to_continue) {
                    $actions_html .= ' class="previsualization-count-js" data-fancybox data-caption="' . esc_attr($post->post_title) . '"';
                }
                $actions_html .= ' title="' . __('Preview', 'mv23theme') . '"><i class="bi bi-arrows-angle-expand"></i> ' . do_shortcode('[post_previsualizations]') . '</a>';
                
            } elseif ($action == 'post_downloads') {
                if ($subscribe_to_continue) {
                    $document_link = add_query_arg(array(
                        'id' => $post->ID,
                        'action' => 'subscribe-to-download'
                    ), home_url('/'));
                }
                $actions_html .= '<a href="' . esc_url($document_link) . '"';
                if (!$subscribe_to_continue) {
                    $actions_html .= ' class="download-count-js" download';
                }
                $actions_html .= ' title="' . __('Download', 'mv23theme') . '"><i class="bi bi-download"></i> ' . do_shortcode('[post_downloads]') . '</a>';
            }
        }

        return $actions_html;
    }
}