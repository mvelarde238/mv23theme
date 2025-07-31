<?php 
use Core\Frontend\Taxonomy_Breadcrumbs;

global $post;
$posttype = $post->post_type;
$taxonomy_name = ($posttype == 'post') ? 'category' : $posttype.'-cat';
?>
<div class="breadcrumbs-wrapper">
    <p class="breadcrumbs"><?php Taxonomy_Breadcrumbs::display($taxonomy_name); ?> </p>
</div>