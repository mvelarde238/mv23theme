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
$list_template = $componente['list_template'];
$scrolltop = ( isset($componente['scrolltop']) ) ? $componente['scrolltop'] : '';
$taxonomy = null;
$terms_in = null;

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
    
    $cpt_terms = ( isset($componente[$posttype.'_terms']) ) ? $componente[$posttype.'_terms'] : null;
    if( is_array($cpt_terms) && count($cpt_terms) > 0 && !empty($cpt_terms[0]) ){
        $taxonomy = get_taxonomy_by_term_id($cpt_terms[0]);
        $terms_in = implode(',',$cpt_terms);
        $args_query['tax_query'] = array(array(
            'taxonomy' => $taxonomy,
            'field' => 'term_id',
            'terms' => array( $terms_in ),
            'include_children' => true,
            'operator' => 'IN'
        ));
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
    data-taxonomy="<?=$taxonomy?>" 
    data-term="<?=$terms_in?>" 
    data-qty="<?=$qty?>" 
    data-offset="<?=$offset?>" 
    data-order="<?=$order?>" 
    data-orderby="<?=$orderby?>" 
    post-template="<?=$post_template?>"
    data-scrolltop="<?=$scrolltop?>">
    <?php if($componente['filter']) {
        $show_tax = 0;
        $default_term = '';
        if( isset($componente['category-filter']) && $taxonomy){
            $show_tax = $componente['category-filter']['show'];
            $default_term = $componente['category-filter'][$taxonomy.'_default'];
        }

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
        
        echo do_shortcode('[posts_filter posttype="'.$posttype.'" firstyear="'.$firstyear.'" show_year="'.$show_year.'" show_month="'.$show_month.'" show_tax="'.$show_tax.'" taxonomy="'.$taxonomy.'" default_term="'.$default_term.'" default_year="'.$default_year.'"]');
    };
    
    if(WOOCOMMERCE_IS_ACTIVE && $posttype == 'product') echo do_shortcode('[shop_messages]');
    echo post_listing_header($posttype);

    $css_vars = ($list_template == 'carrusel') ? ' ' : '--d-gap:'.$d_gap.'px; --l-gap:'.$l_gap.'px; --t-gap:'.$t_gap.'px; --m-gap:'.$m_gap.'px; --d-columns:'.$items_in_desktop.'; --l-columns:'.$items_in_laptop.'; --t-columns:'.$items_in_tablet.'; --m-columns:'.$items_in_mobile;

    if ($query->have_posts()) : 
        $columns_class = ($list_template == 'carrusel') ? '' : 'has-columns';
        ?>
        <div class="posts-listing posts-listing--<?=$list_template?> <?=$columns_class?>" style="<?=$css_vars?>">
            <?php if($list_template == 'carrusel'): 
                $show_controls = (!empty($componente['show_controls'])) ? $componente['show_controls'] : 0;
                $show_nav = (!empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
                $show_nav = (!empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
                $autoplay = (!empty($componente['autoplay'])) ? $componente['autoplay'] : 0;
                ?>
            <div class="carrusel"><div class="carrusel__slider" 
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
                if($list_template == 'carrusel') echo '<div>';
                get_template_part( 'inc/partials/minipost',$post_template);
                if($list_template == 'carrusel') echo '</div>';
            endwhile; ?>

            <?php if($list_template == 'carrusel'): ?>
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