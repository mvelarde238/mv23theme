<?php 
use Core\Frontend\Taxonomy_Breadcrumbs;

if( is_tax() ){
    $taxonomy_name = get_queried_object()->taxonomy;
} elseif( is_singular() ) {
    global $post;
    $posttype = $post->post_type;
    $taxonomy_name = ($posttype == 'post') ? 'category' : $posttype.'-cat';
} else {
    $taxonomy_name = null;
}

if($taxonomy_name) : ?>
    <div class="breadcrumbs-wrapper">
        <p class="breadcrumbs"><?php echo Taxonomy_Breadcrumbs::display($taxonomy_name); ?></p>
    </div>
<?php endif; ?>