<?php
use Core\Frontend\Post_Card;

$id = get_the_ID();
$postcard_attributes = Post_Card::build_attributes($post, $args);
?>
<div class="postcard" <?php echo $postcard_attributes ?>>
    <?php echo do_shortcode('[products ids="'.$id.'" columns="1"]'); ?>
</div>