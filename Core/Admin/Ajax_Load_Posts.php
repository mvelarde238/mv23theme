<?php
namespace Core\Admin;

use WP_Query;
use Core\Posttype\Postcard;
use Core\Builder\Component\Postcard as Postcard_Component;
use Core\Frontend\Pagination;

class Ajax_Load_Posts{

    public function load_posts() {

        // if ( !wp_verify_nonce( $_REQUEST['nonce'], "global-nonce")) {
        //     exit("No naughty business please.");
        // }

        $texts = array(
            array("es" => "No hubieron resultados", "en" => "No matches were found"),
            array("es" => "No se enviaron parámetros", "en" => "No parameters were sent")
        );

        $filter_values = $_REQUEST;
        $paged = (int) ($_REQUEST["paged"] ?? 1);
        $lang = sanitize_key($_REQUEST["lang"] ?? 'es');
        $taxonomies = $_REQUEST["taxonomies"] ?? array();

        $listing_args = json_decode(stripslashes($_REQUEST['listing_args']), true);
        $postcard_template = $listing_args["post_template"];
        $listing_template = $listing_args["listing_template"];
        $on_click_post = $listing_args["on_click_post"];
        $on_click_scroll_to = $listing_args["on_click_scroll_to"];
        $wookey = $listing_args["wookey"] ?? '';
        $pagination_type = $listing_args["pagination_type"];
        $posttype = $listing_args["posttype"] ?? '';

        $query_args = $listing_args['query_args'] ?? null;
        $postcard_cpt_template = null;

        if ( strpos($postcard_template, 'postcard_') === 0 ) {
            $postcard_cpt_template = Postcard::getInstance()->get_data( str_replace('postcard_', '', $postcard_template) );
        }

        if ( $query_args && $paged ) {

            // Whitelist allowed WP_Query keys to prevent arbitrary query injection
            $allowed_keys = array('post_type', 'posts_per_page', 'order', 'orderby', 'tax_query', 'meta_query', 'post_status', 'date_query', 's', 'post__in');
            $query_args = array_intersect_key($query_args, array_flip($allowed_keys));

            $query_args['paged'] = $paged;

            // Override tax_query if the listing-filter component sends taxonomy filters
            if ( is_array($taxonomies) && !empty($taxonomies) ) {
                $tax_query = array( 'relation' => 'AND' );
                foreach ($taxonomies as $taxonomy => $terms) {
                    // term_id 0 means "Todas las categorías" (all), so it must not filter the query
                    $terms = array_filter(array_map('intval', (array) $terms));
                    if (!empty($terms)) {
                        $tax_query[] = array(
                            'taxonomy' => sanitize_key($taxonomy),
                            'field'    => 'term_id',
                            'terms'    => array_values($terms),
                            'include_children' => true,
                            'operator' => 'IN'
                        );
                    }
                }
                if ( count($tax_query) > 1 ) {
                    $query_args['tax_query'] = $tax_query;
                } else {
                    unset($query_args['tax_query']);
                }
            }

            if ( isset($filter_values['search']) && !empty($filter_values['search']) ) {
                $query_args['s'] = sanitize_text_field($filter_values['search']);
            }

            if ( isset($filter_values['year']) || isset($filter_values['month']) ) {
                $year = isset($filter_values['year']) ? intval($filter_values['year']) : null;
                $month = isset($filter_values['month']) ? intval($filter_values['month']) : null;

                switch ($posttype) {
                    case 'event':
                        if ($year && $month) $dates = array( $year.'-'.$month.'-01 01:00:00', $year.'-'.$month.'-31 23:59:59' );
                        if (!$month) $dates = array( $year.'-01-01 01:00:00', $year.'-12-31 23:59:59' );
                        $query_args['meta_query'] = array(
                            'event_start_clause' => array(
                                'key' => '_event_start',
                                'value' => $dates,
                                'compare' => 'BETWEEN',
                                'type' => 'DATE'
                            )
                        );
                        $query_args['orderby'] = 'event_start_clause';
                        break;
                    default:
                        $date_params = array();
                        if ($year) $date_params['year'] = $year;
                        if ($month) $date_params['month'] = $month;
                        $query_args['date_query'] = array( $date_params );
                        break;
                }
            }

            // custom field query (from listing-filter component)
            if ( isset($filter_values['custom']) && is_array($filter_values['custom']) ) {
                $custom_compare  = ( isset($filter_values['custom_compare']) && is_array($filter_values['custom_compare']) ) ? $filter_values['custom_compare'] : array();
                $allowed_compares = array( '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE' );

                $meta_query = isset($query_args['meta_query']) ? $query_args['meta_query'] : array();
                if ( !isset($meta_query['relation']) ) $meta_query['relation'] = 'AND';

                foreach ( $filter_values['custom'] as $meta_key => $value ) {
                    if ( !is_array($value) && $value === '' ) continue;
                    $meta_key = sanitize_key($meta_key);

                    if ( is_array($value) && isset($value['min'], $value['max']) ) {
                        // number_range → BETWEEN
                        if ( $value['min'] === '' && $value['max'] === '' ) continue;
                        $min = floatval($value['min']);
                        $max = floatval($value['max']);
                        if ( $min > $max ) { $t = $min; $min = $max; $max = $t; }
                        $meta_query[] = array(
                            'key'     => $meta_key,
                            'value'   => array( $min, $max ),
                            'compare' => 'BETWEEN',
                            'type'    => 'NUMERIC',
                        );

                    } elseif ( is_array($value) ) {
                        // checkboxes → IN
                        $sanitized = array_values( array_filter( array_map( 'sanitize_text_field', $value ) ) );
                        if ( empty($sanitized) ) continue;
                        $meta_query[] = array(
                            'key'     => $meta_key,
                            'value'   => $sanitized,
                            'compare' => 'IN',
                        );

                    } else {
                        // text / select / radio
                        $sanitized_value = sanitize_text_field($value);
                        if ( $sanitized_value === '' ) continue;
                        $compare = isset($custom_compare[$meta_key]) ? strtoupper( sanitize_text_field( $custom_compare[$meta_key] ) ) : '=';
                        if ( !in_array($compare, $allowed_compares) ) $compare = '=';
                        $meta_query[] = array(
                            'key'     => $meta_key,
                            'value'   => $sanitized_value,
                            'compare' => $compare,
                        );
                    }
                }

                if ( count($meta_query) > 1 ) {
                    $query_args['meta_query'] = $meta_query;
                }
            }
            // end custom field query

            $query_args = apply_filters('filter_listing_query_args', $query_args, $listing_args, $filter_values);
            $query = new WP_Query( $query_args );

            if ($query->have_posts()) {
                $result['status'] = "success";

                if ( $on_click_post === 'none' ) {
                    add_filter('post_link', array($this, 'hide_permalink'), 30, 2);
                    add_filter('post_type_link', array($this, 'hide_permalink'), 30, 2);
                }

                ob_start();
                if ( $postcard_cpt_template && $postcard_cpt_template['has_content'] ) {
                    echo '<style>'.$postcard_cpt_template['styles'].'</style>';
                }
                if ($listing_template == 'masonry') {
                    echo '<div class="masonry-grid-sizer"></div>';
                    echo '<div class="masonry-gutter-sizer"></div>';
                }

                $count = 0;
                while ( $query->have_posts() ) :
                    $query->the_post();

                    if ($listing_template == 'carousel') echo '<div>';
                    if ($listing_template == 'masonry') echo '<div class="masonry-grid-item">';

                    if ( $postcard_cpt_template && $postcard_cpt_template['has_content'] ) {
                        $postcard_cpt_template['component']['postcard_settings'] = array(
                            'on_click_post' => $on_click_post,
                            'on_click_scroll_to' => $on_click_scroll_to
                        );
                        echo Postcard_Component::display( $postcard_cpt_template['component'] );
                    } else {
                        $_postcard_template = apply_filters('filter_listing_postcard_template', $postcard_template, $count);
                        get_template_part( 'partials/card/postcard', $_postcard_template, array(
                            'postcard_settings' => array( 'template' => $_postcard_template ),
                            'on_click_post' => $on_click_post,
                            'on_click_scroll_to' => $on_click_scroll_to,
                            'count' => $count,
                        ));
                    }

                    if ($listing_template == 'carousel') echo '</div>';
                    if ($listing_template == 'masonry') echo '</div>';
                    $count++;
                endwhile;
                $result['posts'] = ob_get_clean();

                if ( $on_click_post === 'none' ) {
                    remove_filter('post_link', array($this, 'hide_permalink'), 30, 2);
                    remove_filter('post_type_link', array($this, 'hide_permalink'), 30, 2);
                }

                if ( $query->max_num_pages > 1 ) {
                    ob_start();
                    if ($pagination_type == 'numeric') {
                        $base_url = $this->generate_base_url($listing_args, $filter_values);
                        Pagination::display($query, $paged, $base_url);
                    }
                    if ($pagination_type == 'load-more') {
                        $load_more_text = LISTING_LOAD_MORE_TEXT;
                        echo '<p class="aligncenter"><button class="btn load_more_posts" data-paged="2">'.$load_more_text[$lang].'</button></p>';
                    }
                    $result['pagination'] = ob_get_clean();
                    $result['max_num_pages'] = $query->max_num_pages;
                } else {
                    $result['pagination'] = '';
                }

            } else {
                $result['status'] = "error";
                $result['message'] = $texts[0][$lang];
            }

        } else {
            $result['status'] = "error";
            $result['message'] = $texts[1][$lang];
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode($result);
        } else {
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
        wp_die();
    }

    public function hide_permalink( $permalink, $post ) {
        return '#';
    }

    /**
     * Generate base URL for pagination links based on listing parameters and context
     *
     * @param array $listing_args Listing configuration (posttype, taxonomies, terms, etc.)
     * @param array $filter_values Current filter values from request
     * @return string Base URL for pagination
     */
    private function generate_base_url($listing_args, $filter_values) {
        $posttype = $listing_args['posttype'] ?? '';
        $taxonomies = $listing_args['taxonomies'] ?? array();
        $terms = $listing_args['terms'] ?? array();
        $source = $listing_args['source'] ?? 'auto';
        $wookey = $listing_args['wookey'] ?? '';
        
        $base_url = home_url('/');
        $query_params = array();
        
        // Manual selection: no meaningful archive URL
        if ($source === 'manual') {
            return home_url('/');
        }
        
        // Single taxonomy term with no filters: use term archive URL
        if (count($taxonomies) === 1 && count($terms) === 1 && empty($filter_values['search']) && !$wookey) {
            $term_link = get_term_link((int)$terms[0], $taxonomies[0]);
            if (!is_wp_error($term_link)) {
                return $term_link;
            }
        }
        
        // Post type archive
        if ($posttype && $posttype !== 'post') {
            $archive_link = get_post_type_archive_link($posttype);
            if ($archive_link) {
                $base_url = $archive_link;
            }
        } else if ($posttype === 'post') {
            // Blog page
            $page_for_posts = get_option('page_for_posts');
            if ($page_for_posts) {
                $blog_page_url = get_permalink($page_for_posts);
                if ($blog_page_url && !is_wp_error($blog_page_url)) {
                    $base_url = $blog_page_url;
                }
            }
        }
        
        // Add filters as query parameters
        if (isset($filter_values['search']) && !empty($filter_values['search'])) {
            $query_params['s'] = sanitize_text_field($filter_values['search']);
        }
        
        if (isset($filter_values['year']) && !empty($filter_values['year'])) {
            $query_params['year'] = intval($filter_values['year']);
        }
        
        if (isset($filter_values['month']) && !empty($filter_values['month'])) {
            $query_params['month'] = intval($filter_values['month']);
        }
        
        // WooCommerce special filters
        if ($wookey) {
            $query_params['wookey'] = sanitize_key($wookey);
        }
        
        // Append query parameters if any
        if (!empty($query_params)) {
            $base_url = add_query_arg($query_params, $base_url);
        }
        
        return $base_url;
    }
}