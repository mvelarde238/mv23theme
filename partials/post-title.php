<div class="page-module" style="padding-bottom: 0">
    <div class="component single-post__title-wrapper">
        <?php
        $queried_object = get_queried_object();
        $posttype = $queried_object->post_type;
        $id = get_the_ID();
        $category_name = ($posttype == 'post') ? 'category' : 'portfolio-cat';
        $categories = get_the_terms( $id, $category_name );
        
        the_title('<h1 class="single-post__title">', '</h1>');
        if($posttype == 'post') $date = get_the_time(get_option('date_format'), $id);
        echo '<p class="single-post__postdata">';
        if($posttype == 'post') echo $date;
        if (is_array($categories) && count($categories) > 0) {
            if($posttype == 'post') echo ' | ';
            $count = 0;
            foreach ($categories as $c) {
                $cat = get_category($c);
                echo '<a href="' . esc_attr( get_tag_link( $cat->term_id ) ) . '">' . $cat->name . '</a>';
                $count++;
                if ($count < count($categories)) echo ', ';
            }
        }
        echo '</p>';
        ?>
    </div>
    <?php
    $tag_name = ($posttype == 'post') ? 'post_tag' : 'portfolio-tag';
    $tags = get_the_terms( $id, $tag_name );
    if (is_array($tags) && count($tags) > 0) { ?>
        <div class="component">
            <div class="single-post__tags text-color-2">
                <?php foreach ($tags as $tag) {
                    $background_color = get_term_meta($tag->term_id, 'background_color', true);
                    $style = ($background_color) ? ' style="background-color:' . $background_color . ';"' : ' ';
                    echo '<span><a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '" class="'.$tag->slug.'">#' . __( $tag->name ) . '</a></span>';
                } ?>
            </div>
        </div>
    <?php } ?>
</div>