<?php

namespace Core\Frontend;

class Pagination{

    public static function display($query = null, $paged = null){
        if ($query) {
            $wp_query = $query;
        } else {
            global $wp_query;
        }

        $paged = ($paged) ? $paged : get_query_var('paged');

        $bignum = 999999999;
        if ($wp_query->max_num_pages <= 1) return;
        echo paginate_links(array(
            'base'         => str_replace($bignum, '%#%', esc_url(get_pagenum_link($bignum))),
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
