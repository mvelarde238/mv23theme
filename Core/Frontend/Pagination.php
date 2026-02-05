<?php

namespace Core\Frontend;

class Pagination{

    public static function display($query = null, $paged = null, $base_url = null){
        if ($query) {
            $wp_query = $query;
        } else {
            global $wp_query;
        }
        if ($wp_query->max_num_pages <= 1) return;

        $paged = ($paged) ? $paged : get_query_var('paged');
        $bignum = 999999999;

        // Use provided base_url or fallback to current context
        if (!$base_url) {
            $base_url = get_pagenum_link($bignum);
            $base_url = str_replace($bignum, '%#%', esc_url($base_url));
        } else {
            // If custom base_url provided, ensure proper format for pagination
            if (strpos($base_url, '%#%') === false) {
                // Add pagination placeholder to the URL
                $base_url = trailingslashit($base_url) . 'page/%#%/';
            }
            $base_url = esc_url($base_url);
        }

        echo paginate_links(array(
            'base'         => $base_url,
            'format'       => '?paged=%#%',
            'current'      => max(1, $paged),
            'total'        => $wp_query->max_num_pages,
            'prev_text'    => '<<',
            'next_text'    => '>>',
            'type'         => 'list',
            'end_size'     => 3,
            'mid_size'     => 3
        ));
    }
}
