<?php
if( IS_MULTILANGUAGE ){
    add_filter('tml_page_id', function( $page_id ){
        $page_id_translated = (function_exists('pll_get_post')) ? pll_get_post($page_id) : $page_id;
        return $page_id_translated;
    });
}