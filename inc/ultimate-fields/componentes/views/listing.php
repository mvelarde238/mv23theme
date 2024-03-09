<?php
$tipo = $componente['__type'];
$postss = $componente['posts'];
$show = $componente['show'];
$items_in_desktop = $componente['items_in_desktop'];
$items_in_laptop = $componente['items_in_laptop'];
$items_in_tablet = $componente['items_in_tablet'];
$items_in_mobile = $componente['items_in_mobile'];
$d_gap = $componente['d_gap'];
$l_gap = $componente['l_gap'];
$t_gap = $componente['t_gap'];
$m_gap = $componente['m_gap'];
$post_template = $componente['post_template'];
$listing_template = $componente['list_template'];
$scrolltop = ( isset($componente['scrolltop']) ) ? $componente['scrolltop'] : '';
$taxonomies = array();
$default_terms = array();
$woocommerce_key = ( WOOCOMMERCE_IS_ACTIVE && isset($componente['woocommerce_key']) ) ? $componente['woocommerce_key'] : '';

if ($show == 'manual') {
    $posttype = '';
    $posts_ids = array();
    foreach ($postss as $post) {
        array_push($posts_ids, str_replace('post_','',$post) );
    };
    
    $args_query = array();
    $args_query['posts_per_page'] = -1;
    $args_query['post_type'] = 'any';
    $args_query['post__in'] = $posts_ids;
    $args_query['orderby'] = 'post__in';
}

if ($show == 'auto') {
    $posttype = $componente['posttype'];
    $qty = (isset($componente['qty'])) ? $componente['qty'] : 3;
    $order = (isset($componente['order'])) ? $componente['order'] : 'DESC';
    $orderby = (isset($componente['orderby'])) ? $componente['orderby'] : 'date';
    $offset = (isset($componente['offset'])) ? $componente['offset'] : 0;
    $args_query = array( 
        'post_type' => $posttype,
        'posts_per_page' => $qty,
        'order' => $order,
        'orderby' => $orderby,
        'offset' => $offset,
        'post_status' => 'publish'
    );

    // check if tax_query is needed 
    $taxonomy_field = ( isset($componente['taxonomies_field']) ) ? $componente['taxonomies_field'] : null;
    $pt_taxonomies = get_object_taxonomies( $posttype ); // get taxonomies for selected posttype 

    if( is_array($taxonomy_field) ){    
        $tax_query = array( 'relation' => 'AND' );
        foreach ($taxonomy_field as $tax => $terms) {
            // create tax_query if tax belongs to selected posttype and there are selected terms
            if( in_array($tax,$pt_taxonomies) && is_array($terms) && count($terms) > 0 ){
                if( !empty($terms[0]) ){            
                    array_push($tax_query, array(
                        'taxonomy' => $tax,
                        'field' => 'term_id',
                        'terms' => $terms,
                        'include_children' => true,
                        'operator' => 'IN'
                    ));
                }
            }
        }

        /* woo featured products */
        if(WOOCOMMERCE_IS_ACTIVE){
            if($woocommerce_key == 'featured'){
                array_push($tax_query, array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN'
                ));
            }
        }
        /* end woo featured products */

        if( count($tax_query) > 1 ) $args_query['tax_query'] = $tax_query;
    }

    // taxonomies and default terms for filter
    foreach($pt_taxonomies as $tax){
        if( isset($componente[$tax.'-filter'])){
            $show_tax = $componente[$tax.'-filter']['show'];
            if($show_tax){
                $default_term = $componente[$tax.'-filter']['default_value'];
                array_push($taxonomies,$tax);
                array_push($default_terms,$default_term);
            }
        }
    }
}

if(WOOCOMMERCE_IS_ACTIVE){
    if($woocommerce_key == 'on_sale'){
        $args_query['meta_query'] = array(
            array(
                'key'           => '_sale_price',
                'value'         => 0,
                'compare'       => '>',
                'type'          => 'numeric'
            )
        );
    }
    if($woocommerce_key == 'best_selling'){
        $args_query['meta_query'] = array(
            array(
                'key' => 'total_sales'
            )
        );
        $args_query['orderby'] = 'meta_value_num';
    }
}

$classes_array = format_classes(array(
    'componente',
    'listing',
    get_color_scheme($componente),
    $componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
$query = new WP_Query( $args_query ); 

if(!function_exists('post_listing_header')){
    function post_listing_header($posttype){ return ''; } 
}
?>
<div <?=$attributes?> 
    data-posttype="<?=$posttype?>" 
    data-taxonomies="<?php echo implode(',',$taxonomies) ?>" 
    data-terms="<?php echo implode(',',$default_terms) ?>" 
    data-qty="<?=$qty?>" 
    data-offset="<?=$offset?>" 
    data-order="<?=$order?>" 
    data-orderby="<?=$orderby?>" 
    post-template="<?=$post_template?>"
    listing-template="<?=$listing_template?>"
    data-wookey="<?=$woocommerce_key?>"
    data-scrolltop="<?=$scrolltop?>">
    <?php if($componente['filter']) {
        $show_month = 0;
        if( isset($componente['month-filter']) ){
            $show_month = $componente['month-filter']['show'];
        }

        $show_year = 0;
        $firstyear = '';
        $default_year = '';
        if( isset($componente['year-filter']) ){
            $show_year = $componente['year-filter']['show'];
            $firstyear = $componente['year-filter']['first_year'];
            $default_year = $componente['year-filter']['default'];
        }
        
        echo do_shortcode('[posts_filter posttype="'.$posttype.'" firstyear="'.$firstyear.'" show_year="'.$show_year.'" show_month="'.$show_month.'" default_year="'.$default_year.'" taxonomies="'.implode(',',$taxonomies).'" default_terms="'.implode(',',$default_terms).'"]');
    };
    
    if(WOOCOMMERCE_IS_ACTIVE && $posttype == 'product') echo do_shortcode('[shop_messages]');
    echo post_listing_header($posttype);

    $css_vars = ($listing_template == 'carrusel') ? ' ' : '--d-gap:'.$d_gap.'px; --l-gap:'.$l_gap.'px; --t-gap:'.$t_gap.'px; --m-gap:'.$m_gap.'px; --d-columns:'.$items_in_desktop.'; --l-columns:'.$items_in_laptop.'; --t-columns:'.$items_in_tablet.'; --m-columns:'.$items_in_mobile;

    if ($query->have_posts()) : 
        $columns_class = ($listing_template == 'carrusel') ? '' : 'has-columns';
        $on_click_post = ( isset($componente['on_click_post']) ) ? $componente['on_click_post'] : 'redirect';
        $post_listing_class = 'posts-listing posts-listing--'.$listing_template . ' ' . $columns_class. ' posts-listing--'.$on_click_post;

        $dataAttrs = '';
        $on_click_scroll_to = ( isset($componente['on_click_scroll_to']) ) ? $componente['on_click_scroll_to'] : '';
        if( $on_click_scroll_to ) $dataAttrs = 'data-on-click-post-scroll-to="'.$on_click_scroll_to.'"';
        ?>
        <div class="<?=$post_listing_class?>" style="<?=$css_vars?>" <?=$dataAttrs?>>
            <?php if($listing_template == 'carrusel'): 
                $show_controls = (!empty($componente['show_controls'])) ? $componente['show_controls'] : 0;
                $show_nav = (!empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
                $show_nav = (!empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
                $autoplay = (!empty($componente['autoplay'])) ? $componente['autoplay'] : 0;

                $carrusel_classes_array = array('carrusel','carrusel-inside-component');
                if( !$show_nav ) array_push($carrusel_classes_array,'without-navigation');
                ?>
                <div class="<?php echo implode(' ', $carrusel_classes_array); ?>" data-controls-position="center"><div class="carrusel__slider" 
                    data-show-controls="<?=$show_controls?>" 
                    data-show-nav="<?=$show_nav?>" 
                    data-autoplay="<?=$autoplay?>" 
                    data-nav-position="bottom"
                    data-mobile="<?=$items_in_mobile?>"
                    data-tablet="<?=$items_in_tablet?>"
                    data-laptop="<?=$items_in_laptop?>"
                    data-desktop="<?=$items_in_desktop?>"
                    data-mobile-gutter="<?=$m_gap?>"
                    data-tablet-gutter="<?=$t_gap?>"
                    data-laptop-gutter="<?=$l_gap?>"
                    data-desktop-gutter="<?=$d_gap?>">
            <?php endif; ?>

            <?php while ( $query->have_posts() ) : $query->the_post();
                if($listing_template == 'carrusel') echo '<div>';
                get_template_part( 'inc/partials/minipost',$post_template);
                if($listing_template == 'carrusel') echo '</div>';
            endwhile; ?>

            <?php if($listing_template == 'carrusel'): ?>
                </div></div>
            <?php endif; ?>
        </div>
    <?php endif;

    if ( $query->max_num_pages > 1 ){
        switch($componente['pagination_type']){
            case 'classic':
                ///////////////////////////////////////////////////////////////////////////////////////////////
                // PAGINATION 
                ///////////////////////////////////////////////////////////////////////////////////////////////
                echo '<br>';
                echo '<div class="pagination">';
                    // mv23_page_navi($query,1); dosnt work
                    $actual_link = home_url();
                    $base = $actual_link.'/page/' .'%#%'. '%_%';
                    echo paginate_links( array(
                        'base'         => $base,
                        'format'       => '?paged=%#%',
                        'current'      => max(1, $paged),
                        'total'        => $query->max_num_pages,
                        'prev_text'    => '<<',
                        'next_text'    => '>>',
                        'type'         => 'list',
                        'end_size'     => 3,
                        'mid_size'     => 3
                    ) );
                echo '</div>';
                ///////////////////////////////////////////////////////////////////////////////////////////////
                break;
                
            case 'load_more':
                $load_more_text = LISTING_LOAD_MORE_TEXT;
                $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';

                echo '<br>';
                echo '<p class="aligncenter"><button class="btn load_more_posts" data-paged="2">'.$load_more_text[$current_lang].'</button></p>'; 
                break;

            default:
                break;
        }
    }

    wp_reset_postdata();
    ?>
</div> 