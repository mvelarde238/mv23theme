<?php
add_action( 'init', function() {
    global $wp_post_types;

    $custom_posts = array(
        array( 'slug'=>'archive_page', 'name'=>'Archive Pages' ),
        array( 'slug'=>'megamenu', 'name'=>'Megamenú' ),
        array( 'slug'=>'v23accordion', 'name'=>'Accordions' ),
        array( 'slug'=>'reusable_section', 'name'=>__('Reusable Sections') )
    );

    foreach($custom_posts as $cpt){
        $slug = $cpt['slug'];
        $name = $cpt['name'];

        $notification_count = wp_count_posts( $slug )->publish;
        $labels = $wp_post_types[$slug]->labels;
        $labels->all_items = $notification_count ? sprintf('%s <span class="awaiting-mod">%d</span>', $name, $notification_count) : $name;   
    }
}, 999 );