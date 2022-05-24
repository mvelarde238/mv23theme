<?php
$tipo = $componente['__type'];
$postss = $componente['posts'];
$show = $componente['show'];
$items_in_desktop = $componente['items_in_desktop'];
$items_in_laptop = $componente['items_in_laptop'];
$items_in_tablet = $componente['items_in_tablet'];
$items_in_mobile = $componente['items_in_mobile'];
$d_gap = $componente['d_gap'].'px';
$l_gap = $componente['l_gap'].'px';
$t_gap = $componente['t_gap'].'px';
$m_gap = $componente['m_gap'].'px';
$post_template = $componente['post_template'];
$list_template = $componente['list_template'];

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
    $args_query = array( 
        'post_type' => $posttype,
        'posts_per_page' => $qty
    );
    
    $cpt_terms = $componente[$posttype.'_terms'];
    if( is_array($cpt_terms) && count($cpt_terms) > 0 ){
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
?>
<div <?=$attributes?>>
    <?php
    $query = new WP_Query( $args_query ); 

    if ($query->have_posts()) : ?>
        <div class="posts-listing posts-listing--<?=$list_template?> has-columns" style="--d-gap:<?=$d_gap?>; --l-gap:<?=$l_gap?>; --t-gap:<?=$t_gap?>; --m-gap:<?=$m_gap?>; --d-columns:<?=$items_in_desktop?>; --l-columns:<?=$items_in_laptop?>; --t-columns:<?=$items_in_tablet?>; --m-columns:<?=$items_in_mobile?>;">
            <?php while ( $query->have_posts() ) : $query->the_post();
                get_template_part( 'inc/partials/minipost',$post_template);
            endwhile; ?>
        </div>
    <?php endif;

    if ( $query->max_num_pages > 1 ){
        switch($componente['pagination_type']){
            case 'classic':
                ///////////////////////////////////////////////////////////////////////////////////////////////
                // PAGINATION 
                ///////////////////////////////////////////////////////////////////////////////////////////////
                echo '<br>';
                echo '<div class="pagination" data-posttype="'.$posttype.'" data-taxonomy="'.$taxonomy.'" data-term="'.$terms_in.'" data-qty="'.$qty.'" post-template="'.$post_template.'">';
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
                echo '<p class="aligncenter"><button class="btn load_more_posts" data-paged="2" data-posttype="'.$posttype.'" data-taxonomy="'.$taxonomy.'" data-term="'.$terms_in.'" data-qty="'.$qty.'" post-template="'.$post_template.'">'.$load_more_text[$current_lang].'</button></p>'; 
                break;

            default:
                break;
        }
    }

    wp_reset_postdata();
    ?>
</div> 