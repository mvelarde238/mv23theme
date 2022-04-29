<?php
if( IS_MULTILANGUAGE ){
    add_filter('tml_page_id', function( $page_id ){
        return pll_get_post($page_id);
    });
}