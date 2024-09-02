<div class="page-module" style="padding-bottom: 0">
    <div class="component single-post__title-wrapper">
        <?php
        the_title('<h1 class="single-post__title">', '</h1>');
        $id = get_the_ID();
        $date = get_the_time(get_option('date_format'), $id);
        $categories = wp_get_post_categories($id);

        echo '<p class="single-post__postdata">' . $date;
        if (is_array($categories) && count($categories) > 0) {
            echo ' | ';
            $count = 0;
            foreach ($categories as $c) {
                $cat = get_category($c);
                echo '<a href="' . home_url('/category/' . $cat->slug) . '">' . $cat->name . '</a>';
                $count++;
                if ($count < count($categories)) echo ', ';
            }
        }
        echo '</p>';
        ?>
    </div>
    <?php
    $tags = get_the_tags($id);
    if (is_array($tags) && count($tags) > 0) { ?>
        <div class="component">
            <div class="single-post__tags">
                <?php foreach ($tags as $tag) {
                    $background_color = get_term_meta($tag->term_id, 'background_color', true);
                    $style = ($background_color) ? ' style="background-color:' . $background_color . ';"' : ' ';
                    echo '<span' . $style . '>#' . $tag->name . '</span>';
                } ?>
            </div>
        </div>
    <?php } ?>
</div>