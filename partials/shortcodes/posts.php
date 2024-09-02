<?php
function print_posts( $atts ){
    if( is_singular( 'archive_page' ) ) return '--posts--';

    if(is_tax()){
        $queried_object = get_taxonomy(get_queried_object()->taxonomy)->object_type;
        $object_type = $queried_object[0];
    } else {
        $queried_object = get_queried_object();
        $object_type = $queried_object->name;
    }

    if (have_posts()) :
        ob_start(); ?>
        <div class="posts-listing has-columns" style="--d-gap:<?=LISTING_DESKTOP_GAP?>; --l-gap:<?=LISTING_LAPTOP_GAP?>; --t-gap:<?=LISTING_TABLET_GAP?>; --m-gap:<?=LISTING_MOBILE_GAP?>; --d-columns:<?=LISTING_DESKTOP_COLUMNS?>; --l-columns:<?=LISTING_LAPTOP_COLUMNS?>; --t-columns:<?=LISTING_TABLET_COLUMNS?>; --m-columns:<?=LISTING_MOBILE_COLUMNS?>;">
            <?php while (have_posts()) : the_post();    
                get_template_part( 'partials/card/minipost', $object_type );
            endwhile; ?>
        </div>
        <?php return ob_get_clean();
    endif;
}
add_shortcode( 'posts', 'print_posts' );